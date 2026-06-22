@extends('admin.reports.financial.pdf._master')
@section('title', 'Procurement Spend Report')
@section('content')
<table class="stats">
    <tr>
        <td><div class="value" style="color:#4361ee">{{ number_format($totalSpend, 2) }}</div><div class="label">Total Spend</div></td>
        <td><div class="value" style="color:#2196f3">{{ $totalOrders }}</div><div class="label">Total POs</div></td>
        <td><div class="value" style="color:#00ab55">{{ $byVendor->count() }}</div><div class="label">Vendors Used</div></td>
    </tr>
</table>

<h5>Spend by Vendor</h5>
<table class="data">
    <thead>
        <tr><th>Vendor</th><th class="text-center">PO(s)</th><th class="text-right">Total</th></tr>
    </thead>
    <tbody>
        @forelse($byVendor as $v)
        <tr>
            <td class="font-semibold">{{ $v['vendor_name'] }}</td>
            <td class="text-center">{{ $v['count'] }}</td>
            <td class="text-right">{{ number_format($v['total'], 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="3" class="text-center" style="color:#888ea8">No data</td></tr>
        @endforelse
    </tbody>
</table>

<h5>Spend by Project</h5>
<table class="data">
    <thead>
        <tr><th>Project</th><th class="text-center">PO(s)</th><th class="text-right">Total</th></tr>
    </thead>
    <tbody>
        @forelse($byProject as $p)
        <tr>
            <td class="font-semibold">{{ $p['project_name'] }}</td>
            <td class="text-center">{{ $p['count'] }}</td>
            <td class="text-right">{{ number_format($p['total'], 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="3" class="text-center" style="color:#888ea8">No data</td></tr>
        @endforelse
    </tbody>
</table>

<h5>PO Details</h5>
<table class="data">
    <thead>
        <tr><th>PO #</th><th>Vendor</th><th>Project</th><th class="text-center">Amount</th><th>Date</th><th>Status</th></tr>
    </thead>
    <tbody>
        @forelse($orders as $po)
        <tr>
            <td class="font-mono font-semibold">{{ $po->po_number }}</td>
            <td>{{ $po->vendor->name ?? 'N/A' }}</td>
            <td>{{ $po->project->name ?? 'N/A' }}</td>
            <td class="text-center">{{ number_format($po->total_amount, 2) }}</td>
            <td>{{ $po->order_date->format('d M Y') }}</td>
            <td>{{ ucfirst(str_replace('_',' ',$po->status)) }}</td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center" style="color:#888ea8">No purchase orders found</td></tr>
        @endforelse
    </tbody>
</table>
@endsection