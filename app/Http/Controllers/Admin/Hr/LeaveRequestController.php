<?php

namespace App\Http\Controllers\Admin\Hr;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveRequest::with('employee', 'approver');

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        $leaves = $query->latest()->paginate(15);
        $employees = Employee::pluck('full_name', 'id');

        return view('admin.hr.leaves.index', compact('leaves', 'employees'));
    }

    public function create()
    {
        $employees = Employee::active()->pluck('full_name', 'id');
        return view('admin.hr.leaves.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|in:sick,casual,annual,unpaid,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
        ]);

        $validated['status'] = 'pending';

        LeaveRequest::create($validated);

        return redirect()->route('admin.hr.leaves.index')
            ->with('success', 'Leave request submitted.');
    }

    public function show(LeaveRequest $leave)
    {
        $leave->load('employee', 'approver');
        return view('admin.hr.leaves.show', compact('leave'));
    }

    public function approve(Request $request, LeaveRequest $leave)
    {
        $validated = $request->validate([
            'remarks' => 'nullable|string',
        ]);

        $leave->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return back()->with('success', 'Leave approved.');
    }

    public function reject(Request $request, LeaveRequest $leave)
    {
        $validated = $request->validate([
            'remarks' => 'nullable|string',
        ]);

        $leave->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return back()->with('success', 'Leave rejected.');
    }

    public function destroy(LeaveRequest $leave)
    {
        $leave->delete();
        return redirect()->route('admin.hr.leaves.index')
            ->with('success', 'Leave request deleted.');
    }
}
