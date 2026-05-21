<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MaterialTransfer;
use App\Models\MaterialTransferItem;
use App\Models\Site;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialTransferController extends Controller
{
    public function index(Request $request)
    {
        $query = MaterialTransfer::with('fromWarehouse', 'toSite');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transfers = $query->latest()->paginate(15);

        return view('admin.procurement.material-transfers.index', compact('transfers'));
    }

    public function create()
    {
        $warehouses = Warehouse::where('status', 'active')->get();
        $sites = Site::where('status', 'active')->get();
        $materials = Material::all();
        return view('admin.procurement.material-transfers.create', compact('warehouses', 'sites', 'materials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_site_id' => 'required|exists:sites,id',
            'transfer_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:materials,id',
            'items.*.quantity' => 'required|numeric|min:0.0001',
        ]);

        DB::transaction(function () use ($validated) {
            $number = 'TRF-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            $transfer = MaterialTransfer::create([
                'from_warehouse_id' => $validated['from_warehouse_id'],
                'to_site_id' => $validated['to_site_id'],
                'transfer_number' => $number,
                'status' => 'pending',
                'transfer_date' => $validated['transfer_date'],
            ]);

            foreach ($validated['items'] as $item) {
                MaterialTransferItem::create([
                    'material_transfer_id' => $transfer->id,
                    'material_id' => $item['material_id'],
                    'quantity' => $item['quantity'],
                ]);

                // Deduct from warehouse stock
                DB::table('stocks')
                    ->where('warehouse_id', $validated['from_warehouse_id'])
                    ->where('material_id', $item['material_id'])
                    ->decrement('quantity', $item['quantity']);

                // Add to site stock
                Stock::updateOrCreate(
                    ['site_id' => $validated['to_site_id'], 'material_id' => $item['material_id'], 'warehouse_id' => null],
                    ['quantity' => DB::raw('quantity + ' . $item['quantity'])]
                );
            }
        });

        return redirect()->route('admin.procurement.material-transfers.index')
            ->with('success', 'Material transfer created successfully.');
    }

    public function show(MaterialTransfer $materialTransfer)
    {
        $materialTransfer->load('fromWarehouse', 'toSite', 'items.material');
        return view('admin.procurement.material-transfers.show', compact('materialTransfer'));
    }

    public function destroy(MaterialTransfer $materialTransfer)
    {
        $materialTransfer->delete();
        return redirect()->route('admin.procurement.material-transfers.index')
            ->with('success', 'Transfer deleted successfully.');
    }
}
