@extends('admin.reports.financial.pdf._master')
@section('title', 'Retention Tracker Report')
@section('content')
<table class="stats">
    <tr>
        <td><div class="value" style="color:#4361ee">{{ number_format($totalRetention, 2) }}</div><div class="label">Total Retention</div></td>
        <td><div class="value" style="color:#00ab55">{{ number_format($totalReleased, 2) }}</div><div class="label">Released</div></td>
        <td><div class="value" style="color:#e7515a">{{ number_format($totalPending, 2) }}</div><div class="label">Pending</div></td>
    </tr>
</table>

<h5>By Project</h5>
<table class="data">
    <thead>
        <tr><th>Project</th><th class="text-center">Invoices</th><th class="text-right">Retention</th><th class="text-right">Released</th><th class="text-right">Pending</th><th class="text-center">Release %</th></tr>
    </thead>
    <tbody>
        @forelse($byProject as $bp)
        <tr>
            <td class="font-semibold">{{ $bp['project_name'] }}</td>
            <td class="text-center">{{ $bp['invoice_count'] }}</td>
            <td class="text-right">{{ number_format($bp['total_retention'], 2) }}</td>
            <td class="text-right text-success">{{ number_format($bp['released'], 2) }}</td>
            <td class="text-right text-danger">{{ number_format($bp['pending'], 2) }}</td>
            <td class="text-center">{{ $bp['release_pct'] }}%</td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center" style="color:#888ea8">No data</td></tr>
        @endforelse
    </tbody>
</table>

<h5>Invoice Details</h5>
<table class="data">
    <thead>
        <tr><th>Invoice #</th><th>Project</th><th class="text-right">Total</th><th class="text-right">Retention</th><th class="text-right">Paid</th><th class="text-right">Due</th><th>Status</th></tr>
    </thead>
    <tbody>
        @forelse($invoices as $inv)
        <tr>
            <td class="font-mono font-semibold">{{ $inv->invoice_number }}</td>
            <td>{{ $inv->project->name ?? 'N/A' }}</td>
            <td class="text-right">{{ number_format($inv->total_amount, 2) }}</td>
            <td class="text-right">{{ number_format($inv->retention_amount, 2) }}</td>
            <td class="text-right text-success">{{ number_format($inv->paid_amount, 2) }}</td>
            <td class="text-right text-danger">{{ number_format($inv->due_amount, 2) }}</td>
            <td>{{ ucfirst(str_replace('_',' ',$inv->status)) }}</td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center" style="color:#888ea8">No invoices with retention found</td></tr>
        @endforelse
    </tbody>
</table>
@endsection