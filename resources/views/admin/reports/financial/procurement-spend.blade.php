@extends('admin.layouts.master')

@section('title', 'Procurement Spend Report')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
@endpush

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold dark:text-white">Procurement Spend</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">PO spend analysis by vendor and project</p>
    </div>
    @include('admin.reports.financial._export-buttons')
</div>

<form method="GET" class="panel mb-6">
    @include('admin.reports.financial._filters')
</form>

<div class="mb-6 grid gap-4 sm:grid-cols-3">
    <div class="panel stat-card"><p class="text-2xl font-bold text-primary">৳{{ number_format($totalSpend, 2) }}</p><p class="text-xs text-gray-500">Total Spend</p></div>
    <div class="panel stat-card"><p class="text-2xl font-bold text-info">{{ $totalOrders }}</p><p class="text-xs text-gray-500">Total POs</p></div>
    <div class="panel stat-card"><p class="text-2xl font-bold text-success">{{ $byVendor->count() }}</p><p class="text-xs text-gray-500">Vendors Used</p></div>
</div>

<div class="mb-6 grid gap-4 lg:grid-cols-2">
    <div class="panel">
        <h5 class="mb-4 text-base font-semibold dark:text-white">Spend by Vendor</h5>
        <div id="vendorChart" class="min-h-[250px]"></div>
    </div>
    <div class="panel">
        <h5 class="mb-4 text-base font-semibold dark:text-white">Spend by Project</h5>
        <div id="projectChart" class="min-h-[250px]"></div>
    </div>
</div>

<div class="panel">
    <h5 class="mb-4 text-base font-semibold dark:text-white">PO Details</h5>
    <div class="table-responsive">
        <table class="table-striped table">
            <thead>
                <tr>
                    <th class="text-xs">PO #</th>
                    <th class="text-xs">Vendor</th>
                    <th class="text-xs">Project</th>
                    <th class="text-xs">Amount (৳)</th>
                    <th class="text-xs">Date</th>
                    <th class="text-xs">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $po)
                <tr>
                    <td class="text-xs font-mono font-semibold">{{ $po->po_number }}</td>
                    <td class="text-xs">{{ $po->vendor->name ?? 'N/A' }}</td>
                    <td class="text-xs">{{ $po->project->name ?? 'N/A' }}</td>
                    <td class="text-xs">{{ number_format($po->total_amount, 2) }}</td>
                    <td class="text-xs">{{ $po->order_date->format('d M Y') }}</td>
                    <td><span class="badge badge-outline-{{ $po->status === 'received' ? 'success' : ($po->status === 'ordered' ? 'info' : 'secondary') }} text-xs">{{ ucfirst(str_replace('_',' ',$po->status)) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-xs text-gray-400">No purchase orders found</td></tr>
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

    new ApexCharts(document.querySelector("#vendorChart"), {
        series: [{ data: [{{ $byVendor->map(fn($v) => $v['total'])->implode(',') }}] }],
        chart: { type: 'bar', height: 250, toolbar: { show: false }, background: 'transparent' },
        colors: ['#4361ee'],
        plotOptions: { bar: { horizontal: true, barHeight: '60%', borderRadius: 4 } },
        xaxis: { labels: { style: { colors: textColor, fontSize: '11px' } } },
        yaxis: { categories: [{{ $byVendor->map(fn($v) => "'{$v['vendor_name']}'")->implode(',') }}], labels: { style: { colors: textColor, fontSize: '10px' } } },
        grid: { borderColor: isDark ? '#1b2e4b' : '#e0e6ed' },
        legend: { show: false },
        dataLabels: { enabled: true, formatter: v => '৳' + (v/1000000).toFixed(1) + 'M', style: { fontSize: '10px' } },
        theme: { mode: isDark ? 'dark' : 'light' }
    }).render();

    new ApexCharts(document.querySelector("#projectChart"), {
        series: [{ data: [{{ $byProject->map(fn($p) => $p['total'])->implode(',') }}] }],
        chart: { type: 'bar', height: 250, toolbar: { show: false }, background: 'transparent' },
        colors: ['#00ab55'],
        plotOptions: { bar: { horizontal: true, barHeight: '60%', borderRadius: 4 } },
        xaxis: { labels: { style: { colors: textColor, fontSize: '11px' } } },
        yaxis: { categories: [{{ $byProject->map(fn($p) => "'{$p['project_name']}'")->implode(',') }}], labels: { style: { colors: textColor, fontSize: '10px' } } },
        grid: { borderColor: isDark ? '#1b2e4b' : '#e0e6ed' },
        legend: { show: false },
        dataLabels: { enabled: true, formatter: v => '৳' + (v/1000000).toFixed(1) + 'M', style: { fontSize: '10px' } },
        theme: { mode: isDark ? 'dark' : 'light' }
    }).render();
});
</script>
@endpush
