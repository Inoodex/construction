<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\GoodsReceivedNoteItem;
use App\Models\MaterialIssueSlipItem;
use App\Models\MaterialTransferItem;
use App\Models\MaterialWastage;
use App\Models\Site;
use App\Models\Stock;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MaterialReconciliationController extends Controller
{
    public function index(Request $request)
    {
        $warehouses = Warehouse::all();
        $sites = Site::all();

        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $warehouseId = $request->input('warehouse_id');
        $siteId = $request->input('site_id');

        $rows = [];

        if ($request->hasAny(['warehouse_id', 'site_id'])) {
            $rows = $this->generate($warehouseId, $siteId, $startDate, $endDate);
        }

        return view('admin.procurement.material-reconciliation.index', compact('rows', 'warehouses', 'sites', 'startDate', 'endDate', 'warehouseId', 'siteId'));
    }

    private function generate(?int $warehouseId, ?int $siteId, string $startDate, string $endDate): array
    {
        $query = Stock::with('material', 'warehouse', 'site');

        if ($warehouseId) {
            $query->where('warehouse_id', $warehouseId);
        } elseif ($siteId) {
            $query->where('site_id', $siteId);
        } else {
            return [];
        }

        $stocks = $query->get();
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $grnItems = GoodsReceivedNoteItem::whereHas('goodsReceivedNote', fn($q) => $q->whereBetween('received_date', [$start, $end]))
            ->selectRaw('material_id, SUM(quantity_accepted) as total')
            ->groupBy('material_id')
            ->pluck('total', 'material_id');

        $issueItems = MaterialIssueSlipItem::whereHas('issueSlip', fn($q) => $q->whereBetween('created_at', [$start, $end]))
            ->selectRaw('material_id, SUM(quantity) as total')
            ->groupBy('material_id')
            ->pluck('total', 'material_id');

        $wastageItems = MaterialWastage::whereBetween('reported_date', [$start, $end])
            ->selectRaw('material_id, SUM(quantity) as total')
            ->groupBy('material_id')
            ->pluck('total', 'material_id');

        $transferInItems = MaterialTransferItem::whereHas('transfer', fn($q) => $q->where('to_site_id', $siteId)->whereBetween('transfer_date', [$start, $end]))
            ->selectRaw('material_id, SUM(quantity) as total')
            ->groupBy('material_id')
            ->pluck('total', 'material_id');

        $transferOutItems = MaterialTransferItem::whereHas('transfer', fn($q) => $q->where('from_warehouse_id', $warehouseId)->whereBetween('transfer_date', [$start, $end]))
            ->selectRaw('material_id, SUM(quantity) as total')
            ->groupBy('material_id')
            ->pluck('total', 'material_id');

        $rows = [];

        foreach ($stocks as $stock) {
            $received = (float)($grnItems[$stock->material_id] ?? 0);
            $issued = (float)($issueItems[$stock->material_id] ?? 0);
            $wasted = (float)($wastageItems[$stock->material_id] ?? 0);
            $transferredIn = (float)($transferInItems[$stock->material_id] ?? 0);
            $transferredOut = (float)($transferOutItems[$stock->material_id] ?? 0);

            $actual = (float)$stock->quantity;
            $opening = $actual - $received + $issued + $transferredOut - $transferredIn + $wasted;
            $expected = $opening + $received - $issued - $transferredOut + $transferredIn - $wasted;
            $variance = $actual - $expected;

            $rows[] = [
                'material' => $stock->material->name ?? 'Unknown',
                'material_id' => $stock->material_id,
                'location' => $stock->warehouse?->name ?? $stock->site?->name ?? 'Unknown',
                'opening' => round($opening, 2),
                'received' => $received,
                'issued' => $issued,
                'transferred_in' => $transferredIn,
                'transferred_out' => $transferredOut,
                'wastage' => $wasted,
                'expected' => round($expected, 2),
                'actual' => $actual,
                'variance' => round($variance, 2),
            ];
        }

        usort($rows, fn($a, $b) => $a['material'] <=> $b['material']);

        return $rows;
    }
}
