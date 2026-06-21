<?php

namespace App\Http\Controllers\Admin\Hr;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\EquipmentMaintenance;
use App\Models\Project;
use App\Models\Site;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::with('project', 'site');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('acquisition_type')) {
            $query->where('acquisition_type', $request->acquisition_type);
        }
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('site_id')) {
            $query->where('site_id', $request->site_id);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%")
                  ->orWhere('serial_number', 'like', "%{$request->search}%");
            });
        }

        $equipment = $query->latest()->paginate(20);
        $categories = Equipment::select('category')->distinct()->whereNotNull('category')->pluck('category');
        $projects = Project::orderBy('name')->pluck('name', 'id');
        $sites = Site::orderBy('name')->pluck('name', 'id');

        return view('admin.hr.equipment.index', compact('equipment', 'categories', 'projects', 'sites'));
    }

    public function create()
    {
        $projects = Project::orderBy('name')->get();
        $sites = Site::orderBy('name')->get();
        return view('admin.hr.equipment.create', compact('projects', 'sites'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:equipment,code',
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'make' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:1900|max:2099',
            'serial_number' => 'nullable|string|max:100',
            'acquisition_type' => 'required|in:owned,hired',
            'purchase_cost' => 'required|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'useful_life_years' => 'required|integer|min:1|max:50',
            'salvage_value' => 'required|numeric|min:0',
            'status' => 'required|in:active,under-maintenance,retired',
            'location' => 'nullable|string|max:255',
            'operator' => 'nullable|string|max:255',
            'meter_hours' => 'required|integer|min:0',
            'maintenance_interval_hours' => 'nullable|integer|min:0',
            'next_maintenance_hours' => 'nullable|integer|min:0',
            'project_id' => 'nullable|exists:projects,id',
            'site_id' => 'nullable|exists:sites,id',
            'allocated_date' => 'nullable|date',
            'deallocated_date' => 'nullable|date|after_or_equal:allocated_date',
            'hire_rate' => 'nullable|numeric|min:0',
            'hire_rate_period' => 'nullable|in:daily,weekly,monthly',
            'hire_start_date' => 'nullable|date',
            'hire_end_date' => 'nullable|date|after_or_equal:hire_start_date',
            'hire_vendor' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['current_value'] = $request->purchase_cost;

        Equipment::create($validated);

        return redirect()->route('admin.hr.equipment.index')
            ->with('success', 'Equipment registered successfully.');
    }

    public function show(Equipment $equipment)
    {
        $equipment->load('maintenanceRecords', 'project', 'site');
        return view('admin.hr.equipment.show', compact('equipment'));
    }

    public function edit(Equipment $equipment)
    {
        $projects = Project::orderBy('name')->get();
        $sites = Site::orderBy('name')->get();
        return view('admin.hr.equipment.edit', compact('equipment', 'projects', 'sites'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:equipment,code,' . $equipment->id,
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'make' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:1900|max:2099',
            'serial_number' => 'nullable|string|max:100',
            'acquisition_type' => 'required|in:owned,hired',
            'purchase_cost' => 'required|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'useful_life_years' => 'required|integer|min:1|max:50',
            'salvage_value' => 'required|numeric|min:0',
            'status' => 'required|in:active,under-maintenance,retired',
            'location' => 'nullable|string|max:255',
            'operator' => 'nullable|string|max:255',
            'meter_hours' => 'required|integer|min:0',
            'maintenance_interval_hours' => 'nullable|integer|min:0',
            'next_maintenance_hours' => 'nullable|integer|min:0',
            'project_id' => 'nullable|exists:projects,id',
            'site_id' => 'nullable|exists:sites,id',
            'allocated_date' => 'nullable|date',
            'deallocated_date' => 'nullable|date|after_or_equal:allocated_date',
            'hire_rate' => 'nullable|numeric|min:0',
            'hire_rate_period' => 'nullable|in:daily,weekly,monthly',
            'hire_start_date' => 'nullable|date',
            'hire_end_date' => 'nullable|date|after_or_equal:hire_start_date',
            'hire_vendor' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $equipment->update($validated);

        return redirect()->route('admin.hr.equipment.index')
            ->with('success', 'Equipment updated.');
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        return back()->with('success', 'Equipment deleted.');
    }

    // Maintenance

    public function maintenanceStore(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'maintenance_date' => 'required|date',
            'type' => 'required|in:preventive,corrective,inspection',
            'description' => 'required|string|max:1000',
            'meter_hours' => 'nullable|integer|min:0',
            'cost' => 'required|numeric|min:0',
            'vendor' => 'nullable|string|max:255',
            'next_due_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:completed,pending,in-progress',
        ]);

        $validated['equipment_id'] = $equipment->id;
        EquipmentMaintenance::create($validated);

        return back()->with('success', 'Maintenance record added.');
    }

    public function maintenanceDestroy(EquipmentMaintenance $maintenance)
    {
        $maintenance->delete();
        return back()->with('success', 'Maintenance record deleted.');
    }

    public function updateMeter(Request $request, Equipment $equipment)
    {
        $request->validate([
            'meter_hours' => 'required|integer|min:0',
        ]);

        $equipment->update(['meter_hours' => $request->meter_hours]);

        return back()->with('success', 'Meter hours updated.');
    }
}
