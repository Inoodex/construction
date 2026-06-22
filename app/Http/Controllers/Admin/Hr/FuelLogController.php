<?php

namespace App\Http\Controllers\Admin\Hr;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\FuelLog;
use Illuminate\Http\Request;

class FuelLogController extends Controller
{
    public function index(Request $request)
    {
        $query = FuelLog::with('equipment');

        if ($request->filled('equipment_id')) {
            $query->where('equipment_id', $request->equipment_id);
        }
        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->fuel_type);
        }

        $records = $query->latest('date')->paginate(20);
        $equipment = Equipment::pluck('name', 'id');

        return view('admin.hr.fuel-logs.index', compact('records', 'equipment'));
    }

    public function create()
    {
        $equipment = Equipment::orderBy('name')->get();
        return view('admin.hr.fuel-logs.create', compact('equipment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'date' => 'required|date',
            'fuel_type' => 'required|in:diesel,petrol,gas,other',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|in:liters,gallons',
            'unit_cost' => 'required|numeric|min:0',
            'meter_hours' => 'nullable|integer|min:0',
            'vendor' => 'nullable|string|max:255',
            'receipt_no' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $validated['total_cost'] = $request->quantity * $request->unit_cost;

        FuelLog::create($validated);

        return redirect()->route('admin.hr.fuel-logs.index')
            ->with('success', 'Fuel log entry created.');
    }

    public function show(FuelLog $fuelLog)
    {
        $fuelLog->load('equipment');
        return view('admin.hr.fuel-logs.show', compact('fuelLog'));
    }

    public function edit(FuelLog $fuelLog)
    {
        $equipment = Equipment::orderBy('name')->get();
        return view('admin.hr.fuel-logs.edit', compact('fuelLog', 'equipment'));
    }

    public function update(Request $request, FuelLog $fuelLog)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'date' => 'required|date',
            'fuel_type' => 'required|in:diesel,petrol,gas,other',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|in:liters,gallons',
            'unit_cost' => 'required|numeric|min:0',
            'meter_hours' => 'nullable|integer|min:0',
            'vendor' => 'nullable|string|max:255',
            'receipt_no' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $validated['total_cost'] = $request->quantity * $request->unit_cost;

        $fuelLog->update($validated);

        return redirect()->route('admin.hr.fuel-logs.index')
            ->with('success', 'Fuel log entry updated.');
    }

    public function destroy(FuelLog $fuelLog)
    {
        $fuelLog->delete();
        return back()->with('success', 'Fuel log entry deleted.');
    }

    public function equipmentDetails(Equipment $equipment)
    {
        $lastVendor = FuelLog::where('equipment_id', $equipment->id)
            ->whereNotNull('vendor')
            ->latest('date')
            ->value('vendor');

        return response()->json([
            'hire_vendor' => $equipment->hire_vendor,
            'last_vendor' => $lastVendor,
        ]);
    }
}
