<!DOCTYPE html>
@php
    $bgPath = public_path('assets/images/inoodex_invoice.jpg');
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wage Slip - {{ $wageSlip->employee->full_name ?? 'Employee' }}</title>
    <style>
        @page { margin: 0; }
        body { margin: 0; padding: 0; font-family: sans-serif; color: #333; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        .wrapper { position: relative; width: 100%; }
        .bg-img { position: absolute; top: 0; left: 0; width: 210mm; height: 297mm; }
        .content { position: relative; padding: 120px 25px 0 25px; box-sizing: border-box; }
        .doc-number { display: inline-block; background-color: #263a79; color: white; padding: 6px 14px; font-size: 14px; font-weight: bold; margin-bottom: 8px; }
        .meta-table { width: 100%; margin-bottom: 15px; font-size: 11px; }
        .meta-table th { text-align: left; padding: 6px 10px; background-color: #eaecf2; color: #263a79; width: 30%; font-weight: bold; border: 1px solid #ddd; }
        .meta-table td { padding: 6px 10px; border: 1px solid #ddd; }
        .section-title { color: #263a79; font-size: 13px; font-weight: bold; margin: 15px 0 8px 0; padding-bottom: 4px; border-bottom: 2px solid #263a79; }
        .detail-table { width: 100%; font-size: 11px; margin-bottom: 15px; }
        .detail-table th { text-align: left; padding: 5px 10px; background-color: #eaecf2; border: 1px solid #ddd; color: #263a79; width: 50%; }
        .detail-table td { padding: 5px 10px; border: 1px solid #ddd; text-align: right; }
        .earnings-table, .deductions-table { width: 48%; font-size: 11px; }
        .earnings-table th, .deductions-table th { padding: 6px 8px; color: white; text-align: left; }
        .earnings-table th { background-color: #00ab55; }
        .deductions-table th { background-color: #e7515a; }
        .earnings-table td, .deductions-table td { padding: 5px 8px; border: 1px solid #ddd; }
        .earnings-table td:last-child, .deductions-table td:last-child { text-align: right; font-weight: bold; }
        .net-pay-box { margin-top: 15px; padding: 15px; border: 3px solid #263a79; background-color: #f0f3f9; text-align: center; }
        .net-pay-box .label { font-size: 12px; color: #666; text-transform: uppercase; }
        .net-pay-box .amount { font-size: 28px; font-weight: bold; color: #263a79; margin-top: 4px; }
    </style>
</head>
<body>
    <div class="wrapper">
        @if (file_exists($bgPath))
            <img class="bg-img" src="{{ $bgPath }}" alt="" />
        @endif
        <div class="content">
            <div style="margin-bottom:15px;">
                <div class="doc-number">WS-{{ str_pad($wageSlip->id, 5, '0', STR_PAD_LEFT) }}</div>
            </div>

            <table class="meta-table">
                <tr>
                    <th>Employee Name</th>
                    <td>{{ $wageSlip->employee->full_name ?? 'N/A' }}</td>
                    <th>Employee ID</th>
                    <td>{{ $wageSlip->employee->employee_id ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Designation</th>
                    <td>{{ $wageSlip->employee->designation ?? 'N/A' }}</td>
                    <th>Department</th>
                    <td>{{ $wageSlip->employee->department ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Period From</th>
                    <td>{{ $wageSlip->period_start->format('d M Y') }}</td>
                    <th>Period To</th>
                    <td>{{ $wageSlip->period_end->format('d M Y') }}</td>
                </tr>
            </table>

            <div class="section-title">Attendance Summary</div>
            <table class="detail-table">
                <tr><th>Total Days</th><td>{{ $wageSlip->total_days }}</td></tr>
                <tr><th>Present Days</th><td>{{ $wageSlip->present_days }}</td></tr>
                <tr><th>Absent Days</th><td>{{ $wageSlip->absent_days }}</td></tr>
                <tr><th>Late Days</th><td>{{ $wageSlip->late_days }}</td></tr>
                <tr><th>Half Days</th><td>{{ $wageSlip->half_days }}</td></tr>
                <tr><th>Holidays</th><td>{{ $wageSlip->holidays }}</td></tr>
            </table>

            <table style="width:100%;margin-bottom:15px;">
                <tr>
                    <td style="width:48%;vertical-align:top;">
                        <div class="section-title">Earnings</div>
                        <table class="earnings-table" style="width:100%;">
                            <tr><th>Description</th><th>Amount</th></tr>
                            <tr><td>Basic Pay</td><td>{{ number_format($wageSlip->basic_pay, 2) }}</td></tr>
                            <tr><td>Overtime Pay</td><td>{{ number_format($wageSlip->overtime_pay, 2) }}</td></tr>
                            <tr><td>Allowances</td><td>{{ number_format($wageSlip->allowances, 2) }}</td></tr>
                        </table>
                    </td>
                    <td style="width:4%;"></td>
                    <td style="width:48%;vertical-align:top;">
                        <div class="section-title">Deductions</div>
                        <table class="deductions-table" style="width:100%;">
                            <tr><th>Description</th><th>Amount</th></tr>
                            <tr><td>Deductions</td><td>{{ number_format($wageSlip->deductions, 2) }}</td></tr>
                        </table>
                    </td>
                </tr>
            </table>

            <div class="net-pay-box">
                <div class="label">Net Pay</div>
                <div class="amount">{{ number_format($wageSlip->net_pay, 2) }}</div>
            </div>
        </div>
    </div>
</body>
</html>
