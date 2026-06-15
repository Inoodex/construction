@extends('admin.layouts.master')

@section('title', 'Invoice Status Report')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
@endpush

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold dark:text-white">Invoice Status</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Client invoice tracking — sent, paid, overdue</p>
    </div>
    @include('admin.reports.financial._export-buttons')
</div>

<form method="GET" class="panel mb-6">
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div>
            <label class="mb-1 block text-xs font-semibold text-gray-500">Project</label>
            <select name="project_id" class="form-select text-xs" onchange="this.form.submit()">
                <option value="">All Projects</option>
                @foreach($projects as $p)
                    <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-semibold text-gray-500">Status</label>
            <select name="status" class="form-select text-xs" onchange="this.form.submit()">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Statuses</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                <option value="partially_paid" {{ request('status') == 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div class="flex items-end">
            <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary text-xs">Reset</a>
        </div>
    </div>
</form>

<div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
    <div class="panel stat-card"><p class="text-2xl font-bold text-primary">৳{{ number_format($summary['total_invoiced'], 2) }}</p><p class="text-xs text-gray-500">Total Invoiced</p></div>
    <div class="panel stat-card"><p class="text-2xl font-bold text-success">৳{{ number_format($summary['total_paid'], 2) }}</p><p class="text-xs text-gray-500">Total Paid</p></div>
    <div class="panel stat-card"><p class="text-2xl font-bold text-danger">৳{{ number_format($summary['total_due'], 2) }}</p><p class="text-xs text-gray-500">Total Due</p></div>
    <div class="panel stat-card"><p class="text-2xl font-bold text-warning">৳{{ number_format($summary['total_retention'], 2) }}</p><p class="text-xs text-gray-500">Retention Held</p></div>
    <div class="panel stat-card"><p class="text-2xl font-bold {{ $summary['overdue_count'] > 0 ? 'text-danger' : 'text-success' }}">{{ $summary['overdue_count'] }}</p><p class="text-xs text-gray-500">Overdue</p></div>
</div>

<div class="mb-6 grid gap-4 lg:grid-cols-2">
    <div class="panel">
        <h5 class="mb-4 text-base font-semibold dark:text-white">Invoice Status Breakdown</h5>
        <div id="invoiceStatusChart" class="min-h-[250px]"></div>
    </div>
    <div class="panel">
        <h5 class="mb-4 text-base font-semibold dark:text-white">By Project</h5>
        <div class="table-responsive">
            <table class="table-striped table">
                <thead>
                    <tr><th class="text-xs">Project</th><th class="text-xs">Invoices</th><th class="text-xs">Total (৳)</th><th class="text-xs">Paid (৳)</th><th class="text-xs">Due (৳)</th></tr>
                </thead>
                <tbody>
                    @forelse($byProject as $bp)
                    <tr>
                        <td class="text-xs font-semibold">{{ $bp['project_name'] }}</td>
                        <td class="text-xs">{{ $bp['count'] }}</td>
                        <td class="text-xs">{{ number_format($bp['total'], 2) }}</td>
                        <td class="text-xs text-success">{{ number_format($bp['paid'], 2) }}</td>
                        <td class="text-xs text-danger">{{ number_format($bp['due'], 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-xs text-gray-400">No data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="panel">
    <h5 class="mb-4 text-base font-semibold dark:text-white">Invoice Register</h5>
    <div class="table-responsive">
        <table class="table-striped table">
            <thead>
                <tr>
                    <th class="text-xs">Invoice #</th>
                    <th class="text-xs">Project</th>
                    <th class="text-xs">Total (৳)</th>
                    <th class="text-xs">Paid (৳)</th>
                    <th class="text-xs">Due (৳)</th>
                    <th class="text-xs">Retention (৳)</th>
                    <th class="text-xs">Due Date</th>
                    <th class="text-xs">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                <tr>
                    <td class="text-xs font-mono font-semibold">{{ $inv->invoice_number }}</td>
                    <td class="text-xs">{{ $inv->project->name ?? 'N/A' }}</td>
                    <td class="text-xs">{{ number_format($inv->total_amount, 2) }}</td>
                    <td class="text-xs text-success">{{ number_format($inv->paid_amount, 2) }}</td>
                    <td class="text-xs text-danger">{{ number_format($inv->due_amount, 2) }}</td>
                    <td class="text-xs text-warning">{{ number_format($inv->retention_amount, 2) }}</td>
                    <td class="text-xs">{{ $inv->due_date->format('d M Y') }}</td>
                    <td><span class="badge badge-outline-{{ $inv->status === 'paid' ? 'success' : ($inv->status === 'overdue' ? 'danger' : ($inv->status === 'draft' ? 'secondary' : 'info')) }} text-xs">{{ ucfirst(str_replace('_',' ',$inv->status)) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-xs text-gray-400">No invoices found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.documentElement.classList.contains('dark');
    const textColor = isDark ? '#888ea8' : '#515365';

    new ApexCharts(document.querySelector("#invoiceStatusChart"), {
        series: [{{ $summary['draft_count'] }}, {{ $summary['sent_count'] }}, {{ $summary['paid_count'] }}, {{ $summary['overdue_count'] }}],
        labels: ['Draft', 'Sent/Partial', 'Paid', 'Overdue'],
        chart: { type: 'donut', height: 250, toolbar: { show: false }, background: 'transparent' },
        colors: ['#6c757d', '#4361ee', '#00ab55', '#e7515a'],
        legend: { position: 'bottom', fontSize: '11px', labels: { colors: textColor } },
        dataLabels: { enabled: true, formatter: v => Math.round(v) + '%', style: { fontSize: '10px' } },
        stroke: { width: 2 },
        theme: { mode: isDark ? 'dark' : 'light' }
    }).render();
});
</script>
@endpush
