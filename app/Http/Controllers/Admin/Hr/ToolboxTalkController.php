<?php

namespace App\Http\Controllers\Admin\Hr;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\ToolboxTalk;
use Illuminate\Http\Request;

class ToolboxTalkController extends Controller
{
    public function index(Request $request)
    {
        $query = ToolboxTalk::with('employee');

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $records = $query->latest('date')->paginate(20);
        $employees = Employee::active()->pluck('full_name', 'id');

        return view('admin.hr.toolbox-talks.index', compact('records', 'employees'));
    }

    public function create()
    {
        $employees = Employee::active()->orderBy('full_name')->get();
        return view('admin.hr.toolbox-talks.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'nullable|exists:employees,id',
            'date' => 'required|date',
            'topic' => 'required|string|max:255',
            'duration_minutes' => 'nullable|integer|min:1',
            'location' => 'nullable|string|max:255',
            'attendees' => 'nullable|string',
            'discussion_points' => 'nullable|string',
            'action_items' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        ToolboxTalk::create($validated);

        return redirect()->route('admin.hr.toolbox-talks.index')
            ->with('success', 'Toolbox talk recorded.');
    }

    public function show(ToolboxTalk $toolboxTalk)
    {
        $toolboxTalk->load('employee');
        return view('admin.hr.toolbox-talks.show', compact('toolboxTalk'));
    }

    public function edit(ToolboxTalk $toolboxTalk)
    {
        $employees = Employee::active()->orderBy('full_name')->get();
        return view('admin.hr.toolbox-talks.edit', compact('toolboxTalk', 'employees'));
    }

    public function update(Request $request, ToolboxTalk $toolboxTalk)
    {
        $validated = $request->validate([
            'employee_id' => 'nullable|exists:employees,id',
            'date' => 'required|date',
            'topic' => 'required|string|max:255',
            'duration_minutes' => 'nullable|integer|min:1',
            'location' => 'nullable|string|max:255',
            'attendees' => 'nullable|string',
            'discussion_points' => 'nullable|string',
            'action_items' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $toolboxTalk->update($validated);

        return redirect()->route('admin.hr.toolbox-talks.index')
            ->with('success', 'Toolbox talk updated.');
    }

    public function destroy(ToolboxTalk $toolboxTalk)
    {
        $toolboxTalk->delete();
        return back()->with('success', 'Toolbox talk deleted.');
    }
}
