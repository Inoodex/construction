<?php

namespace App\Http\Controllers\Admin\Hr;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('employee');

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $records = $query->latest('date')->paginate(30);
        $employees = Employee::active()->pluck('full_name', 'id');

        return view('admin.hr.attendance.index', compact('records', 'employees'));
    }

    public function create()
    {
        $employees = Employee::active()->orderBy('full_name')->get();
        return view('admin.hr.attendance.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.employee_id' => 'required|exists:employees,id',
            'attendances.*.status' => 'required|in:present,absent,late,half-day,holiday',
            'attendances.*.note' => 'nullable|string|max:255',
        ]);

        $date = $validated['date'];
        $count = 0;

        foreach ($validated['attendances'] as $att) {
            Attendance::updateOrCreate(
                ['employee_id' => $att['employee_id'], 'date' => $date],
                ['status' => $att['status'], 'note' => $att['note'] ?? null]
            );
            $count++;
        }

        return redirect()->route('admin.hr.attendance.index')
            ->with('success', "Attendance recorded for {$count} employees on {$date}.");
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return back()->with('success', 'Attendance record deleted.');
    }
}
