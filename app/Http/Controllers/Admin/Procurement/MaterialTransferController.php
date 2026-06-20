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
        $query = MaterialTransfer::with(['fromWarehouse', 'fromSite', 'toSite', 'toWarehouse']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('transfer_type')) {
            $query->where('transfer_type', $request->transfer_type);
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
            'transfer_type' => 'required|in:warehouse_to_site,site_to_warehouse,site_to_site,warehouse_to_warehouse',
            'from_warehouse_id' => 'required_if:transfer_type,warehouse_to_site,warehouse_to_warehouse|nullable|exists:warehouses,id',
            'from_site_id' => 'required_if:transfer_type,site_to_warehouse,site_to_site|nullable|exists:sites,id',
            'to_site_id' => 'required_if:transfer_type,warehouse_to_site,site_to_site|nullable|exists:sites,id',
            'to_warehouse_id' => 'required_if:transfer_type,site_to_warehouse,warehouse_to_warehouse|nullable|exists:warehouses,id',
            'transfer_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:materials,id',
            'items.*.quantity' => 'required|numeric|min:0.0001',
        ]);

        DB::transaction(function () use ($validated) {
            $number = 'TRF-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            $transfer = MaterialTransfer::create([
                'transfer_type' => $validated['transfer_type'],
                'from_warehouse_id' => $validated['from_warehouse_id'] ?? null,
                'from_site_id' => $validated['from_site_id'] ?? null,
                'to_site_id' => $validated['to_site_id'] ?? null,
                'to_warehouse_id' => $validated['to_warehouse_id'] ?? null,
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

                $qty = $item['quantity'];

                // Deduct from source
                if (in_array($validated['transfer_type'], ['warehouse_to_site', 'warehouse_to_warehouse'])) {
                    DB::table('stocks')
                        ->where('warehouse_id', $validated['from_warehouse_id'])
                        ->where('material_id', $item['material_id'])
                        ->decrement('quantity', $qty);
                } else {
                    DB::table('stocks')
                        ->where('site_id', $validated['from_site_id'])
                        ->where('material_id', $item['material_id'])
                        ->decrement('quantity', $qty);
                }

                // Add to destination
                if (in_array($validated['transfer_type'], ['warehouse_to_site', 'site_to_site'])) {
                    Stock::updateOrCreate(
                        ['site_id' => $validated['to_site_id'], 'material_id' => $item['material_id'], 'warehouse_id' => null],
                        ['quantity' => DB::raw('quantity + ' . $qty)]
                    );
                } else {
                    Stock::updateOrCreate(
                        ['warehouse_id' => $validated['to_warehouse_id'], 'material_id' => $item['material_id'], 'site_id' => null],
                        ['quantity' => DB::raw('quantity + ' . $qty)]
                    );
                }
            }
        });

        return redirect()->route('admin.procurement.material-transfers.index')
            ->with('success', 'Material transfer created successfully.');
    }

    public function show(MaterialTransfer $materialTransfer)
    {
        $materialTransfer->load(['fromWarehouse', 'fromSite', 'toSite', 'toWarehouse', 'items.material']);
        return view('admin.procurement.material-transfers.show', compact('materialTransfer'));
    }

    public function updateStatus(Request $request, MaterialTransfer $materialTransfer)
    {
        $request->validate([
            'status' => 'required|in:transit,completed,cancelled',
        ]);

        $materialTransfer->update(['status' => $request->status]);

        return back()->with('success', 'Transfer status updated to "' . $request->status . '".');
    }

    public function destroy(MaterialTransfer $materialTransfer)
    {
        $materialTransfer->delete();
        return redirect()->route('admin.procurement.material-transfers.index')
            ->with('success', 'Transfer deleted successfully.');
    }
}
