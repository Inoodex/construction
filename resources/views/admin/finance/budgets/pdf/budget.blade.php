<!DOCTYPE html>
@php
    $bgPath = public_path('assets/images/inoodex_invoice.jpg');
    $variance = $budget->budgeted_amount - $budget->actual_cost;
    $utilPct = $budget->budgeted_amount > 0 ? round(($budget->actual_cost / $budget->budgeted_amount) * 100, 1) : 0;
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Budget - {{ $budget->cost_code }}</title>
    <style>
        @page { margin: 0; }
        body { margin: 0; padding: 0; font-family: sans-serif; color: #333; font-size: 11px; }
        .wrapper { position: relative; width: 100%; }
        .bg-img { position: absolute; top: 0; left: 0; width: 210mm; height: 297mm; }
        .content { position: relative; padding: 120px 25px 0 25px; box-sizing: border-box; }
        .doc-number { display: inline-block; background-color: #263a79; color: white; padding: 6px 14px; font-size: 14px; font-weight: bold; }
        .meta-table { width: 100%; margin-top: 15px; font-size: 11px; }
        .meta-table th { text-align: left; padding: 6px 10px; background-color: #eaecf2; color: #263a79; width: 22%; font-weight: bold; border: 1px solid #ddd; }
        .meta-table td { padding: 6px 10px; border: 1px solid #ddd; }
        .financial-table { width: 100%; margin-top: 15px; font-size: 11px; }
        .financial-table th { background-color: #263a79; color: white; padding: 6px 10px; text-align: left; border: 1px solid #263a79; }
        .financial-table td { padding: 6px 10px; border: 1px solid #ddd; }
        .financial-table tr:nth-child(even) td { background-color: #f5f7fa; }
        .summary-total td { font-weight: bold; color: white; background-color: #263a79; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .variance-positive { color: #28a745; font-weight: bold; }
        .variance-negative { color: #dc3545; font-weight: bold; }
        .notes-box { margin-top: 20px; padding: 10px 14px; border-left: 4px solid #263a79; background-color: #eaecf2; font-size: 11px; line-height: 1.5; }
        .notes-box strong { color: #263a79; }
    </style>
</head>
<body>
    <div class="wrapper">
        @if (file_exists($bgPath))
            <img class="bg-img" src="{{ $bgPath }}" alt="" />
        @endif
        <div class="content">
            <!-- <div style="margin-bottom:15px;">
                <div class="doc-number">{{ $budget->cost_code }}</div>
            </div> -->

            <table class="meta-table">
                <tr>
                    <th>Project</th>
                    <td>{{ $budget->project->name ?? 'N/A' }} ({{ $budget->cost_code }})</td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>{{ $budget->description ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Financial Year</th>
                    <td>{{ $budget->financial_year ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Created By</th>
                    <td>{{ $budget->creator->name ?? 'N/A' }}</td>
                </tr>
            </table>

            <table class="financial-table">
                <thead>
                    <tr>
                        <th style="width:60%;">Item</th>
                        <th style="width:40%; text-align:right;">Amount (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Budgeted Amount</td>
                        <td class="text-right" style="font-weight:bold;">{{ number_format($budget->budgeted_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Planned Value</td>
                        <td class="text-right">{{ number_format($budget->planned_value, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Earned Value</td>
                        <td class="text-right">{{ number_format($budget->earned_value, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Actual Cost</td>
                        <td class="text-right" style="font-weight:bold;">{{ number_format($budget->actual_cost, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Variance (Budget - Actual)</td>
                        <td class="text-right {{ $variance >= 0 ? 'variance-positive' : 'variance-negative' }}">
                            @if($variance >= 0)+@endif{{ number_format($variance, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td>Utilization %</td>
                        <td class="text-right">{{ $utilPct }}%</td>
                    </tr>
                    <tr>
                        <td>SPI (Earned / Planned)</td>
                        <td class="text-right">{{ $budget->planned_value > 0 ? number_format($budget->earned_value / $budget->planned_value, 2) : '—' }}</td>
                    </tr>
                    <tr>
                        <td>CPI (Earned / Actual)</td>
                        <td class="text-right">{{ $budget->actual_cost > 0 ? number_format($budget->earned_value / $budget->actual_cost, 2) : '—' }}</td>
                    </tr>
                </tbody>
            </table>

            @if($budget->notes)
                <div class="notes-box">
                    <strong>Notes:</strong><br/>
                    {{ $budget->notes }}
                </div>
            @endif

            <div class="notes-box">
                <strong>Generated:</strong> {{ now()->format('d M Y H:i') }}<br/>
                This is a system-generated budget document.
            </div>
        </div>
    </div>
</body>
</html>
