@extends('admin.reports.financial.pdf._master')
@section('title', 'Invoice Status Report')
@section('content')
<table class="stats">
    <tr>
        <td><div class="value" style="color:#4361ee">৳{{ number_format($summary['total_invoiced'], 2) }}</div><div class="label">Total Invoiced</div></td>
        <td><div class="value" style="color:#00ab55">৳{{ number_format($summary['total_paid'], 2) }}</div><div class="label">Total Paid</div></td>
        <td><div class="value" style="color:#e7515a">৳{{ number_format($summary['total_due'], 2) }}</div><div class="label">Total Due</div></td>
        <td><div class="value" style="color:#e2a03f">৳{{ number_format($summary['total_retention'], 2) }}</div><div class="label">Retention Held</div></td>
        <td><div class="value" style="color:{{ $summary['overdue_count'] > 0 ? '#e7515a' : '#00ab55' }}">{{ $summary['overdue_count'] }}</div><div class="label">Overdue</div></td>
    </tr>
</table>

<h5>By Project</h5>
<table class="data">
    <thead>
        <tr><th>Project</th><th class="text-center">Invoices</th><th class="text-right">Total</th><th class="text-right">Paid</th><th class="text-right">Due</th></tr>
    </thead>
    <tbody>
        @forelse($byProject as $bp)
        <tr>
            <td class="font-semibold">{{ $bp['project_name'] }}</td>
            <td class="text-center">{{ $bp['count'] }}</td>
            <td class="text-right">{{ number_format($bp['total'], 2) }}</td>
            <td class="text-right text-success">{{ number_format($bp['paid'], 2) }}</td>
            <td class="text-right text-danger">{{ number_format($bp['due'], 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center" style="color:#888ea8">No data</td></tr>
        @endforelse
    </tbody>
</table>

<h5>Invoice Register</h5>
<table class="data">
    <thead>
        <tr><th>Invoice #</th><th>Project</th><th class="text-right">Total</th><th class="text-right">Paid</th><th class="text-right">Due</th><th class="text-right">Retention</th><th>Due Date</th><th>Status</th></tr>
    </thead>
    <tbody>
        @forelse($invoices as $inv)
        <tr>
            <td class="font-mono font-semibold">{{ $inv->invoice_number }}</td>
            <td>{{ $inv->project->name ?? 'N/A' }}</td>
            <td class="text-right">{{ number_format($inv->total_amount, 2) }}</td>
            <td class="text-right text-success">{{ number_format($inv->paid_amount, 2) }}</td>
            <td class="text-right text-danger">{{ number_format($inv->due_amount, 2) }}</td>
            <td class="text-right text-warning">{{ number_format($inv->retention_amount, 2) }}</td>
            <td>{{ $inv->due_date->format('d M Y') }}</td>
            <td>{{ ucfirst(str_replace('_',' ',$inv->status)) }}</td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center" style="color:#888ea8">No invoices found</td></tr>
        @endforelse
    </tbody>
</table>
@endsection