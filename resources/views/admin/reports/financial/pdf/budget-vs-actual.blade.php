@extends('admin.reports.financial.pdf._master')
@section('title', 'Budget vs Actual Report')
@section('content')
<table class="stats">
    <tr>
        <td><div class="value" style="color:#4361ee">{{ number_format($totalBudgeted, 2) }}</div><div class="label">Total Budgeted</div></td>
        <td><div class="value" style="color:#e2a03f">{{ number_format($totalActual, 2) }}</div><div class="label">Total Actual</div></td>
        <td><div class="value" style="color:{{ $variance >= 0 ? '#00ab55' : '#e7515a' }}">{{ number_format($variance, 2) }}</div><div class="label">Variance</div></td>
        <td><div class="value" style="color:{{ $variancePct >= 0 ? '#00ab55' : '#e7515a' }}">{{ $variancePct }}%</div><div class="label">Variance %</div></td>
    </tr>
</table>
<h5>Cost Code Breakdown</h5>
<table class="data">
    <thead>
        <tr>
            <th>Cost Code</th>
            <th class="text-right">Budgeted</th>
            <th class="text-right">Actual</th>
            <th class="text-right">Variance</th>
            <th class="text-right">Variance %</th>
            <th class="text-center">Items</th>
        </tr>
    </thead>
    <tbody>
        @forelse($costCodes as $code => $cc)
        <tr>
            <td class="font-mono font-semibold">{{ $code }}</td>
            <td class="text-right">{{ number_format($cc['budgeted'], 2) }}</td>
            <td class="text-right">{{ number_format($cc['actual'], 2) }}</td>
            <td class="text-right {{ $cc['variance'] >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($cc['variance'], 2) }}</td>
            <td class="text-right {{ $cc['variance_pct'] >= 0 ? 'text-success' : 'text-danger' }}">{{ $cc['variance_pct'] }}%</td>
            <td class="text-center">{{ $cc['count'] }}</td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center" style="color:#888ea8">No budget data found</td></tr>
        @endforelse
    </tbody>
</table>
@endsection