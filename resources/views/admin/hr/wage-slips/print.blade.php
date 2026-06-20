<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wage Slip - {{ $wageSlip->employee->full_name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 13px; color: #333; padding: 40px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { font-size: 22px; text-transform: uppercase; }
        .header p { color: #666; margin-top: 4px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .col { width: 48%; }
        .col h4 { font-size: 14px; border-bottom: 1px solid #ddd; padding-bottom: 6px; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; }
        table td { padding: 4px 0; }
        table td:last-child { text-align: right; }
        .label { color: #666; }
        .comp-table { width: 100%; max-width: 400px; margin-top: 10px; }
        .comp-table td { padding: 6px 0; }
        .comp-table tr:last-child { border-top: 2px solid #333; font-weight: bold; font-size: 15px; }
        .footer { margin-top: 40px; padding-top: 15px; border-top: 1px solid #ddd; text-align: center; color: #999; font-size: 11px; }
        .status { display: inline-block; padding: 2px 10px; border-radius: 3px; font-size: 11px; background: #eee; }
        .text-right { text-align: right; }
        .text-red { color: #dc2626; }
        .fw-bold { font-weight: bold; }
        .mt-4 { margin-top: 16px; }
        .mb-2 { margin-bottom: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <p>Wage Slip for {{ $wageSlip->period_start->format('F Y') }}</p>
        <p style="margin-top:4px"><span class="status">{{ ucfirst($wageSlip->status) }}</span></p>
    </div>

    <div class="row">
        <div class="col">
            <h4>Employee Details</h4>
            <table>
                <tr><td class="label">Name</td><td class="fw-bold">{{ $wageSlip->employee->full_name }}</td></tr>
                <tr><td class="label">Code</td><td>{{ $wageSlip->employee->employee_code }}</td></tr>
                <tr><td class="label">Designation</td><td>{{ $wageSlip->employee->designation ?? '—' }}</td></tr>
                <tr><td class="label">Department</td><td>{{ $wageSlip->employee->department ?? '—' }}</td></tr>
                <tr><td class="label">Type</td><td class="capitalize">{{ ucfirst($wageSlip->employee->employment_type) }}</td></tr>
            </table>
        </div>
        <div class="col">
            <h4>Attendance Summary</h4>
            <table>
                <tr><td class="label">Total Days</td><td>{{ $wageSlip->total_days }}</td></tr>
                <tr><td class="label">Present</td><td>{{ $wageSlip->present_days }}</td></tr>
                <tr><td class="label">Absent</td><td>{{ $wageSlip->absent_days }}</td></tr>
                <tr><td class="label">Late</td><td>{{ $wageSlip->late_days }}</td></tr>
                <tr><td class="label">Half Days</td><td>{{ $wageSlip->half_days }}</td></tr>
                <tr><td class="label">Holidays</td><td>{{ $wageSlip->holidays }}</td></tr>
            </table>
        </div>
    </div>

    <h4 style="margin-bottom:8px">Compensation</h4>
    <table class="comp-table">
        <tr><td class="label">Basic Pay</td><td>{{ number_format($wageSlip->basic_pay, 2) }}</td></tr>
        <tr><td class="label">Overtime Pay</td><td>{{ number_format($wageSlip->overtime_pay, 2) }}</td></tr>
        <tr><td class="label">Allowances</td><td>{{ number_format($wageSlip->allowances, 2) }}</td></tr>
        <tr><td class="label text-red">Deductions</td><td class="text-red">({{ number_format($wageSlip->deductions, 2) }})</td></tr>
        <tr><td class="label">Net Pay</td><td>{{ number_format($wageSlip->net_pay, 2) }}</td></tr>
    </table>

    @if($wageSlip->notes)
        <div class="mt-4">
            <h4>Notes</h4>
            <p style="color:#666;margin-top:4px">{{ $wageSlip->notes }}</p>
        </div>
    @endif

    <div class="footer">
        <p>Generated on {{ now()->format('d M Y, h:i A') }} | {{ config('app.name') }}</p>
    </div>

    <script>window.print();</script>
</body>
</html>
