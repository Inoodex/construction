@extends('admin.layouts.master')

@section('title', 'Wage Slip - ' . $wageSlip->employee->full_name)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Wage Slip</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.hr.wage-slips.print', $wageSlip) }}" target="_blank" class="btn btn-outline-primary gap-2">Print</a>
            <a href="{{ route('admin.hr.wage-slips.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="border-b pb-4 mb-4">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-bold">{{ config('app.name') }}</h3>
                    <p class="text-sm text-gray-500">Wage Slip for {{ $wageSlip->period_start->format('F Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-semibold">Status: <span class="badge badge-{{ $wageSlip->status === 'paid' ? 'success' : 'secondary' }}">{{ ucfirst($wageSlip->status) }}</span></p>
                    <p class="text-sm text-gray-500">Generated: {{ $wageSlip->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <h4 class="font-semibold mb-2">Employee Details</h4>
                <table class="w-full text-sm">
                    <tr><td class="py-1 text-gray-500 w-32">Name</td><td class="font-semibold">{{ $wageSlip->employee->full_name }}</td></tr>
                    <tr><td class="py-1 text-gray-500">Code</td><td>{{ $wageSlip->employee->employee_code }}</td></tr>
                    <tr><td class="py-1 text-gray-500">Designation</td><td>{{ $wageSlip->employee->designation ?? '—' }}</td></tr>
                    <tr><td class="py-1 text-gray-500">Department</td><td>{{ $wageSlip->employee->department ?? '—' }}</td></tr>
                    <tr><td class="py-1 text-gray-500">Type</td><td class="capitalize">{{ $wageSlip->employee->employment_type }}</td></tr>
                </table>
            </div>
            <div>
                <h4 class="font-semibold mb-2">Attendance Summary</h4>
                <table class="w-full text-sm">
                    <tr><td class="py-1 text-gray-500 w-32">Total Days</td><td>{{ $wageSlip->total_days }}</td></tr>
                    <tr><td class="py-1 text-gray-500">Present</td><td>{{ $wageSlip->present_days }}</td></tr>
                    <tr><td class="py-1 text-gray-500">Absent</td><td>{{ $wageSlip->absent_days }}</td></tr>
                    <tr><td class="py-1 text-gray-500">Late</td><td>{{ $wageSlip->late_days }}</td></tr>
                    <tr><td class="py-1 text-gray-500">Half Days</td><td>{{ $wageSlip->half_days }}</td></tr>
                    <tr><td class="py-1 text-gray-500">Holidays</td><td>{{ $wageSlip->holidays }}</td></tr>
                </table>
            </div>
        </div>

        <div class="border-t pt-4">
            <h4 class="font-semibold mb-3">Compensation</h4>
            <table class="w-full text-sm max-w-md">
                <tr>
                    <td class="py-2 text-gray-500">Basic Pay</td>
                    <td class="text-right font-semibold">{{ number_format($wageSlip->basic_pay, 2) }}</td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-500">Overtime Pay</td>
                    <td class="text-right font-semibold">{{ number_format($wageSlip->overtime_pay, 2) }}</td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-500">Allowances</td>
                    <td class="text-right font-semibold">{{ number_format($wageSlip->allowances, 2) }}</td>
                </tr>
                <tr class="border-t">
                    <td class="py-2 text-gray-500">Deductions</td>
                    <td class="text-right font-semibold text-red-600">({{ number_format($wageSlip->deductions, 2) }})</td>
                </tr>
                <tr class="border-t-2 font-bold text-lg">
                    <td class="py-3">Net Pay</td>
                    <td class="text-right">{{ number_format($wageSlip->net_pay, 2) }}</td>
                </tr>
            </table>
        </div>

        @if($wageSlip->notes)
            <div class="mt-4 border-t pt-4">
                <h4 class="font-semibold mb-1">Notes</h4>
                <p class="text-sm text-gray-600">{{ $wageSlip->notes }}</p>
            </div>
        @endif
    </div>
@endsection
