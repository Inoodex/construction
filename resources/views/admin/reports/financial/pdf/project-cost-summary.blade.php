@extends('admin.reports.financial.pdf._master')
@section('title', 'Project Cost Summary')
@section('content')
<table class="stats">
    <tr>
        <td><div class="value" style="color:#4361ee">৳{{ number_format($totals['budget']/1000000,1) }}M</div><div class="label">Total Budget</div></td>
        <td><div class="value" style="color:#e2a03f">৳{{ number_format($totals['actual_cost']/1000000,1) }}M</div><div class="label">Actual Cost</div></td>
        <td><div class="value" style="color:#2196f3">৳{{ number_format($totals['po_total']/1000000,1) }}M</div><div class="label">PO Spend</div></td>
        <td><div class="value" style="color:#00ab55">৳{{ number_format($totals['invoiced']/1000000,1) }}M</div><div class="label">Invoiced</div></td>
        <td><div class="value" style="color:#805dca">৳{{ number_format($totals['paid']/1000000,1) }}M</div><div class="label">Paid</div></td>
        <td><div class="value" style="color:#e7515a">৳{{ number_format($totals['due']/1000000,1) }}M</div><div class="label">Due</div></td>
    </tr>
</table>
<h5>Project Breakdown</h5>
<table class="data">
    <thead>
        <tr>
            <th>Project</th>
            <th class="text-right">Budget</th>
            <th class="text-right">Actual Cost</th>
            <th class="text-right">PO Spend</th>
            <th class="text-right">Resource</th>
            <th class="text-right">Total Spend</th>
            <th class="text-right">Remaining</th>
            <th class="text-center">Utilization</th>
        </tr>
    </thead>
    <tbody>
        @forelse($summaries as $s)
        <tr>
            <td class="font-semibold">{{ $s['project']->name }}</td>
            <td class="text-right">{{ number_format($s['budget'], 2) }}</td>
            <td class="text-right">{{ number_format($s['actual_cost'], 2) }}</td>
            <td class="text-right">{{ number_format($s['po_total'], 2) }}</td>
            <td class="text-right">{{ number_format($s['resource_cost'], 2) }}</td>
            <td class="text-right font-semibold">{{ number_format($s['total_spend'], 2) }}</td>
            <td class="text-right {{ $s['remaining'] >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($s['remaining'], 2) }}</td>
            <td class="text-center">{{ $s['utilization_pct'] }}%</td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center" style="color:#888ea8">No data found</td></tr>
        @endforelse
    </tbody>
</table>
@endsection