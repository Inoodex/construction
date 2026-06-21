<?php

namespace App\Http\Controllers\Admin\Hr;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\IncidentReport;
use Illuminate\Http\Request;

class IncidentReportController extends Controller
{
    public function index(Request $request)
    {
        $query = IncidentReport::with('employee');

        if ($request->filled('incident_type')) {
            $query->where('incident_type', $request->incident_type);
        }
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $records = $query->latest('incident_date')->paginate(20);

        return view('admin.hr.incident-reports.index', compact('records'));
    }

    public function create()
    {
        $employees = Employee::active()->orderBy('full_name')->get();
        return view('admin.hr.incident-reports.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'nullable|exists:employees,id',
            'incident_date' => 'required|date',
            'incident_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'incident_type' => 'required|string|max:100',
            'severity' => 'required|in:minor,moderate,serious,critical,fatal',
            'description' => 'required|string',
            'immediate_action' => 'nullable|string',
            'root_cause' => 'nullable|string',
            'corrective_action' => 'nullable|string',
            'affected_persons' => 'nullable|string|max:500',
            'property_damage' => 'nullable|string',
            'reported_by' => 'nullable|string|max:255',
            'status' => 'required|in:open,under-investigation,closed',
            'investigation_notes' => 'nullable|string',
        ]);

        IncidentReport::create($validated);

        return redirect()->route('admin.hr.incident-reports.index')
            ->with('success', 'Incident report created.');
    }

    public function show(IncidentReport $incidentReport)
    {
        $incidentReport->load('employee');
        return view('admin.hr.incident-reports.show', compact('incidentReport'));
    }

    public function edit(IncidentReport $incidentReport)
    {
        $employees = Employee::active()->orderBy('full_name')->get();
        return view('admin.hr.incident-reports.edit', compact('incidentReport', 'employees'));
    }

    public function update(Request $request, IncidentReport $incidentReport)
    {
        $validated = $request->validate([
            'employee_id' => 'nullable|exists:employees,id',
            'incident_date' => 'required|date',
            'incident_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'incident_type' => 'required|string|max:100',
            'severity' => 'required|in:minor,moderate,serious,critical,fatal',
            'description' => 'required|string',
            'immediate_action' => 'nullable|string',
            'root_cause' => 'nullable|string',
            'corrective_action' => 'nullable|string',
            'affected_persons' => 'nullable|string|max:500',
            'property_damage' => 'nullable|string',
            'reported_by' => 'nullable|string|max:255',
            'status' => 'required|in:open,under-investigation,closed',
            'investigation_notes' => 'nullable|string',
        ]);

        $incidentReport->update($validated);

        return redirect()->route('admin.hr.incident-reports.index')
            ->with('success', 'Incident report updated.');
    }

    public function destroy(IncidentReport $incidentReport)
    {
        $incidentReport->delete();
        return back()->with('success', 'Incident report deleted.');
    }
}
