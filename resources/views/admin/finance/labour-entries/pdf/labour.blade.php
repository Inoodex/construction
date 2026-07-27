<!DOCTYPE html>
@php
    $bgPath = public_path('assets/images/inoodex_invoice.jpg');
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Labour Entries</title>
    <style>
        @page { margin: 0; }
        body { margin: 0; padding: 0; font-family: sans-serif; color: #333; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        .wrapper { position: relative; width: 100%; }
        .bg-img { position: absolute; top: 0; left: 0; width: 210mm; height: 297mm; }
        .content { position: relative; padding: 120px 25px 0 25px; box-sizing: border-box; }
        .doc-number { display: inline-block; background-color: #263a79; color: white; padding: 6px 14px; font-size: 14px; font-weight: bold; margin-bottom: 8px; }
        .meta-table { width: 100%; margin-bottom: 15px; font-size: 11px; }
        .meta-table th { text-align: left; padding: 6px 10px; background-color: #eaecf2; color: #263a79; width: 22%; font-weight: bold; border: 1px solid #ddd; }
        .meta-table td { padding: 6px 10px; border: 1px solid #ddd; }
        .summary-table { width: 100%; margin-top: 10px; font-size: 10px; }
        .summary-table th { background-color: #c09f5a; color: white; padding: 5px 8px; text-align: left; font-weight: bold; border: 1px solid #c09f5a; }
        .summary-table td { padding: 5px 8px; border: 1px solid #ddd; }
        .summary-table tr:nth-child(even) { background-color: #f5f7fa; }
        .items-table { width: 100%; margin-top: 15px; font-size: 10px; }
        .items-table th { background-color: #263a79; color: white; padding: 6px 5px; text-align: left; font-weight: bold; border: 1px solid #263a79; }
        .items-table td { padding: 5px; border: 1px solid #ddd; }
        .items-table tr:nth-child(even) { background-color: #f5f7fa; }
        .summary-total td { padding: 6px 10px; border: 1px solid #263a79; font-weight: bold; color: #fff; background-color: #263a79; font-size: 11px; }
        .text-right { text-align: right; }
        .section-title { color: #263a79; font-size: 12px; font-weight: bold; margin-top: 15px; margin-bottom: 5px; border-bottom: 2px solid #263a79; padding-bottom: 4px; }
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
            <div style="margin-bottom:15px;">
                <div class="doc-number">LABOUR COST ALLOCATION</div>
            </div>

            <table class="meta-table">
                <tr>
                    <th>Report Date</th>
                    <td>{{ now()->format('d M Y') }}</td>
                    <th>Total Entries</th>
                    <td>{{ $entries->count() }}</td>
                </tr>
                <tr>
                    <th>Filters Applied</th>
                    <td colspan="3">
                        @if(request('project_id')) Project: {{ \App\Models\Project::find(request('project_id'))->name ?? '-' }} | @endif
                        @if(request('employee_id')) Employee: {{ \App\Models\Employee::find(request('employee_id'))->full_name ?? '-' }} | @endif
                        @if(request('date_from')) From: {{ request('date_from') }} | @endif
                        @if(request('date_to')) To: {{ request('date_to') }} | @endif
                        @if(!request()->anyFilled(['project_id', 'employee_id', 'date_from', 'date_to'])) No filters (all entries) @endif
                    </td>
                </tr>
            </table>

            @if($summary->count() > 0)
                <div class="section-title">Summary by Project</div>
                <table class="summary-table">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th class="text-right">Total Hours</th>
                            <th class="text-right">Total Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($summary as $s)
                            <tr>
                                <td>{{ $s->project?->name ?? 'Unknown' }}</td>
                                <td class="text-right">{{ number_format($s->total_hours, 2) }}</td>
                                <td class="text-right">{{ number_format($s->total_cost, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="summary-total">
                            <td>GRAND TOTAL</td>
                            <td class="text-right">{{ number_format($totalHours, 2) }} hrs</td>
                            <td class="text-right">{{ number_format($totalCost, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif

            <div class="section-title">Detailed Entries</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Project</th>
                        <th>Employee</th>
                        <th class="text-right">Hours</th>
                        <th class="text-right">Rate</th>
                        <th class="text-right">Cost</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $e)
                        <tr>
                            <td>{{ $e->date->format('d/m/Y') }}</td>
                            <td>{{ $e->project->name ?? '—' }}</td>
                            <td>{{ $e->employee->full_name ?? '—' }}</td>
                            <td class="text-right">{{ number_format($e->hours, 2) }}</td>
                            <td class="text-right">{{ number_format($e->hourly_rate, 2) }}</td>
                            <td class="text-right" style="font-weight:bold;">{{ number_format($e->hours * $e->hourly_rate, 2) }}</td>
                            <td>{{ $e->description ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-right" style="text-align:center;">No entries found.</td></tr>
                    @endforelse
                    <tr class="summary-total">
                        <td colspan="3">GRAND TOTAL</td>
                        <td class="text-right">{{ number_format($totalHours, 2) }} hrs</td>
                        <td></td>
                        <td class="text-right">{{ number_format($totalCost, 2) }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <div class="notes-box">
                <strong>Notes:</strong><br/>
                1. This is a system-generated labour cost report.<br/>
                2. Total Cost = Hours &times; Hourly Rate for each entry.<br/>
                3. Report includes all filtered entries as of {{ now()->format('d M Y H:i') }}.
            </div>
        </div>
    </div>
</body>
</html>
