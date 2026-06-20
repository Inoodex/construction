<?php

namespace App\Http\Controllers\Admin\Hr;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Timesheet;
use App\Models\WageSlip;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WageSlipController extends Controller
{
    public function index(Request $request)
    {
        $query = WageSlip::with('employee');

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('month')) {
            $query->whereYear('period_start', substr($request->month, 0, 4))
                  ->whereMonth('period_start', substr($request->month, 5, 2));
        }

        $wageSlips = $query->latest('period_start')->paginate(20);
        $employees = Employee::active()->pluck('full_name', 'id');

        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $m = now()->subMonths($i);
            $months[$m->format('Y-m')] = $m->format('F Y');
        }

        return view('admin.hr.wage-slips.index', compact('wageSlips', 'employees', 'months'));
    }

    public function create()
    {
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $m = now()->subMonths($i);
            $months[$m->format('Y-m')] = $m->format('F Y');
        }
        $employees = Employee::active()->orderBy('full_name')->get();
        return view('admin.hr.wage-slips.create', compact('months', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|date_format:Y-m',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
        ]);

        $periodStart = Carbon::parse($validated['month'] . '-01')->startOfMonth();
        $periodEnd = $periodStart->copy()->endOfMonth();
        $totalDays = $periodStart->diffInDays($periodEnd) + 1;
        $generated = 0;

        foreach ($validated['employee_ids'] as $empId) {
            $employee = Employee::findOrFail($empId);

            if (WageSlip::where('employee_id', $empId)
                ->where('period_start', $periodStart)
                ->where('period_end', $periodEnd)
                ->exists()) {
                continue;
            }

            $attendances = Attendance::where('employee_id', $empId)
                ->whereBetween('date', [$periodStart, $periodEnd])
                ->get();

            $presentDays = $attendances->where('status', 'present')->count();
            $absentDays = $attendances->where('status', 'absent')->count();
            $lateDays = $attendances->where('status', 'late')->count();
            $halfDays = $attendances->where('status', 'half-day')->count();
            $holidays = $attendances->where('status', 'holiday')->count();

            $dailyRate = $employee->basic_salary / 30;
            $workedDays = $presentDays + ($halfDays * 0.5) + ($lateDays * 0.75);

            if ($employee->employment_type === 'daily_wage') {
                $basicPay = $dailyRate * $presentDays;
            } else {
                $basicPay = $dailyRate * $workedDays;
            }

            $overtimeHours = Timesheet::where('employee_id', $empId)
                ->whereBetween('date', [$periodStart, $periodEnd])
                ->where('hours_worked', '>', 8)
                ->sum('hours_worked');

            $overtimePay = $overtimeHours > 0 ? ($overtimeHours - ($presentDays * 8)) * ($dailyRate / 8 * 1.5) : 0;
            $overtimePay = max(0, $overtimePay);

            $allowances = $basicPay * 0.10;
            $deductions = $basicPay * 0.05;
            $netPay = $basicPay + $overtimePay + $allowances - $deductions;

            WageSlip::create([
                'employee_id' => $empId,
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
                'total_days' => $totalDays,
                'present_days' => $presentDays,
                'absent_days' => $absentDays,
                'late_days' => $lateDays,
                'half_days' => $halfDays,
                'holidays' => $holidays,
                'basic_pay' => max(0, $basicPay),
                'overtime_pay' => max(0, $overtimePay),
                'allowances' => max(0, $allowances),
                'deductions' => max(0, $deductions),
                'net_pay' => max(0, $netPay),
                'status' => 'generated',
            ]);

            $generated++;
        }

        return redirect()->route('admin.hr.wage-slips.index')
            ->with('success', "{$generated} wage slip(s) generated for {$periodStart->format('F Y')}.");
    }

    public function show(WageSlip $wageSlip)
    {
        $wageSlip->load('employee');
        return view('admin.hr.wage-slips.show', compact('wageSlip'));
    }

    public function print(WageSlip $wageSlip)
    {
        $wageSlip->load('employee');
        return view('admin.hr.wage-slips.print', compact('wageSlip'));
    }

    public function destroy(WageSlip $wageSlip)
    {
        $wageSlip->delete();
        return back()->with('success', 'Wage slip deleted.');
    }
}
