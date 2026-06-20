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
            'attendances.*.clock_in' => 'nullable|date_format:H:i',
            'attendances.*.clock_out' => 'nullable|date_format:H:i',
            'attendances.*.note' => 'nullable|string|max:255',
        ]);

        $date = $validated['date'];
        $count = 0;

        foreach ($validated['attendances'] as $att) {
            $data = [
                'status' => $att['status'],
                'note' => $att['note'] ?? null,
            ];

            if (!empty($att['clock_in'])) {
                $data['clock_in'] = $date . ' ' . $att['clock_in'];
            }
            if (!empty($att['clock_out'])) {
                $data['clock_out'] = $date . ' ' . $att['clock_out'];
            }

            Attendance::updateOrCreate(
                ['employee_id' => $att['employee_id'], 'date' => $date],
                $data
            );
            $count++;
        }

        return redirect()->route('admin.hr.attendance.index')
            ->with('success', "Attendance recorded for {$count} employees on {$date}.");
    }

    public function summary(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));

        $employees = Employee::active()->orderBy('full_name')->get();
        $summary = [];

        foreach ($employees as $emp) {
            $records = Attendance::where('employee_id', $emp->id)
                ->whereYear('date', substr($month, 0, 4))
                ->whereMonth('date', substr($month, 5, 2))
                ->get();

            $summary[] = [
                'employee' => $emp,
                'present' => $records->where('status', 'present')->count(),
                'absent' => $records->where('status', 'absent')->count(),
                'late' => $records->where('status', 'late')->count(),
                'half_day' => $records->where('status', 'half-day')->count(),
                'holiday' => $records->where('status', 'holiday')->count(),
                'total_hours' => $records->sum(fn($r) =>
                    $r->clock_in && $r->clock_out
                        ? max(0, $r->clock_out->diffInMinutes($r->clock_in) / 60)
                        : 0
                ),
            ];
        }

        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $m = now()->subMonths($i);
            $months[$m->format('Y-m')] = $m->format('F Y');
        }

        return view('admin.hr.attendance.summary', compact('summary', 'month', 'months'));
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return back()->with('success', 'Attendance record deleted.');
    }
}
