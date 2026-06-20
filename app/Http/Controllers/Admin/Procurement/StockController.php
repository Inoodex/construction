<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Site;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Stock::with('material', 'warehouse', 'site');

        if ($request->filled('material_id')) {
            $query->where('material_id', $request->material_id);
        }

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->filled('location')) {
            if ($request->location == 'warehouse') {
                $query->whereNotNull('warehouse_id');
            } elseif ($request->location == 'site') {
                $query->whereNotNull('site_id');
            }
        }

        if ($request->boolean('low_stock')) {
            $query->where('quantity', '>', 0)
                  ->where('min_stock', '>', 0)
                  ->whereColumn('quantity', '<', 'min_stock');
        }

        $stocks = $query->latest()->paginate(15);
        $materials = Material::all();
        $warehouses = Warehouse::where('status', 'active')->get();

        return view('admin.procurement.stocks.index', compact('stocks', 'materials', 'warehouses'));
    }

    public function create()
    {
        $materials = Material::all();
        $warehouses = Warehouse::where('status', 'active')->get();
        $sites = Site::where('status', 'active')->get();
        return view('admin.procurement.stocks.create', compact('materials', 'warehouses', 'sites'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'site_id' => 'nullable|exists:sites,id',
            'quantity' => 'required|numeric|min:0',
            'min_stock' => 'nullable|numeric|min:0',
        ]);

        if (!$validated['warehouse_id'] && !$validated['site_id']) {
            return back()->withErrors(['location' => 'Select either a Warehouse or a Site.'])->withInput();
        }

        Stock::updateOrCreate(
            [
                'warehouse_id' => $validated['warehouse_id'],
                'site_id' => $validated['site_id'],
                'material_id' => $validated['material_id'],
            ],
            [
                'quantity' => DB::raw('quantity + ' . $validated['quantity']),
                'min_stock' => $validated['min_stock'] ?? 0,
            ]
        );

        return redirect()->route('admin.procurement.stocks.index')
            ->with('success', 'Stock updated successfully.');
    }

    public function show(Stock $stock)
    {
        $stock->load('material', 'warehouse', 'site');
        return view('admin.procurement.stocks.show', compact('stock'));
    }

    public function edit(Stock $stock)
    {
        $materials = Material::all();
        $warehouses = Warehouse::where('status', 'active')->get();
        $sites = Site::where('status', 'active')->get();
        return view('admin.procurement.stocks.edit', compact('stock', 'materials', 'warehouses', 'sites'));
    }

    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'quantity' => 'required|numeric|min:0',
            'min_stock' => 'nullable|numeric|min:0',
        ]);

        $stock->update([
            'quantity' => $validated['quantity'],
            'min_stock' => $validated['min_stock'] ?? $stock->min_stock,
        ]);

        return redirect()->route('admin.procurement.stocks.index')
            ->with('success', 'Stock quantity updated successfully.');
    }

    public function destroy(Stock $stock)
    {
        $stock->delete();
        return redirect()->route('admin.procurement.stocks.index')
            ->with('success', 'Stock record deleted successfully.');
    }
}
