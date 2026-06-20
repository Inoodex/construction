<?php

namespace App\Http\Controllers\Admin\Hr;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Timesheet;
use Illuminate\Http\Request;

class TimesheetController extends Controller
{
    public function index(Request $request)
    {
        $query = Timesheet::with('employee', 'project');

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $timesheets = $query->latest('date')->paginate(20);
        $employees = Employee::active()->pluck('full_name', 'id');
        $projects = Project::all();

        return view('admin.hr.timesheets.index', compact('timesheets', 'employees', 'projects'));
    }

    public function create()
    {
        $employees = Employee::active()->orderBy('full_name')->get();
        $projects = Project::all();
        return view('admin.hr.timesheets.create', compact('employees', 'projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'project_id' => 'nullable|exists:projects,id',
            'date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'hours_worked' => 'required|numeric|min:0|max:24',
            'description' => 'nullable|string|max:1000',
        ]);

        Timesheet::create($validated);

        return redirect()->route('admin.hr.timesheets.index')
            ->with('success', 'Timesheet entry created.');
    }

    public function destroy(Timesheet $timesheet)
    {
        $timesheet->delete();
        return back()->with('success', 'Timesheet entry deleted.');
    }
}
