<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LabourEntry;
use App\Models\Project;
use Illuminate\Http\Request;

class LabourEntryController extends Controller
{
    public function index(Request $request)
    {
        $query = LabourEntry::with('project', 'employee');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $entries = $query->latest('date')->paginate(20);

        $projects = Project::pluck('name', 'id');
        $employees = Employee::active()->pluck('full_name', 'id');

        // Summary per project
        $summary = LabourEntry::selectRaw('project_id, SUM(hours) as total_hours, SUM(hours * hourly_rate) as total_cost')
            ->when($request->filled('project_id'), fn($q) => $q->where('project_id', $request->project_id))
            ->groupBy('project_id')
            ->with('project')
            ->get();

        return view('admin.finance.labour-entries.index', compact('entries', 'projects', 'employees', 'summary'));
    }

    public function create()
    {
        $projects = Project::pluck('name', 'id');
        $employees = Employee::active()->orderBy('full_name')->pluck('full_name', 'id');
        return view('admin.finance.labour-entries.create', compact('projects', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'hours' => 'required|numeric|min:0.25|max:24',
            'hourly_rate' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        LabourEntry::create($validated);

        return redirect()->route('admin.finance.labour-entries.index')
            ->with('success', 'Labour entry recorded successfully.');
    }

    public function destroy(LabourEntry $labourEntry)
    {
        $labourEntry->delete();
        return redirect()->route('admin.finance.labour-entries.index')
            ->with('success', 'Labour entry deleted.');
    }
}
