@extends('admin.reports.financial.pdf._master')
@section('title', 'Cash Flow Report')
@section('content')
<table class="stats">
    <tr>
        <td><div class="value" style="color:#00ab55">৳{{ number_format($totalInflow, 2) }}</div><div class="label">Total Inflow</div></td>
        <td><div class="value" style="color:#e7515a">৳{{ number_format($totalOutflow, 2) }}</div><div class="label">Total Outflow</div></td>
        <td><div class="value" style="color:{{ $netCashFlow >= 0 ? '#00ab55' : '#e7515a' }}">৳{{ number_format($netCashFlow, 2) }}</div><div class="label">Net Cash Flow</div></td>
    </tr>
</table>

<h5>Monthly Cash Flow Projection</h5>
<table class="data">
    <thead>
        <tr><th>Month</th><th class="text-right">Inflow</th><th class="text-right">Outflow</th><th class="text-right">Net</th></tr>
    </thead>
    <tbody>
        @forelse($allMonths as $m)
        <tr>
            <td class="font-semibold">{{ \Carbon\Carbon::createFromFormat('Y-m', $m['month'])->format('M Y') }}</td>
            <td class="text-right text-success">৳{{ number_format($m['inflow'], 2) }}</td>
            <td class="text-right text-danger">৳{{ number_format($m['outflow'], 2) }}</td>
            <td class="text-right {{ $m['net'] >= 0 ? 'text-success' : 'text-danger' }}">৳{{ number_format($m['net'], 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="4" class="text-center" style="color:#888ea8">No data</td></tr>
        @endforelse
    </tbody>
</table>

@if($payments->isNotEmpty())
<h5>Recent Payments</h5>
<table class="data">
    <thead>
        <tr><th>Invoice</th><th>Project</th><th class="text-right">Amount</th><th>Date</th><th>Method</th></tr>
    </thead>
    <tbody>
        @foreach($payments as $pmt)
        <tr>
            <td class="font-mono">{{ $pmt->invoice->invoice_number ?? 'N/A' }}</td>
            <td>{{ $pmt->invoice->project->name ?? 'N/A' }}</td>
            <td class="text-right">৳{{ number_format($pmt->amount, 2) }}</td>
            <td>{{ $pmt->payment_date ? $pmt->payment_date->format('d M Y') : '—' }}</td>
            <td>{{ ucfirst($pmt->payment_method ?? '—') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection