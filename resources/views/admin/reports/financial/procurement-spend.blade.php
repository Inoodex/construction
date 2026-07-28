@extends('admin.layouts.master')

@section('title', 'Procurement Spend Report')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
@endpush

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold uppercase dark:text-white">Procurement Spend Report</h2>
        <p class="mt-0.5 text-xs text-white-dark">Comprehensive purchase order spend analysis by vendor and project</p>
    </div>
    @include('admin.reports.financial._export-buttons')
</div>

{{-- Filter Bar --}}
<div class="panel mb-6">
    <form method="GET" class="flex flex-wrap items-center justify-between gap-4">
        @include('admin.reports.financial._filters')
    </form>
</div>

{{-- Metric Cards Grid --}}
<div class="mb-6 grid gap-6 sm:grid-cols-3">
    <div class="panel text-center flex flex-col items-center justify-center">
        <label class="text-xs text-white-dark uppercase font-semibold">Total PO Spend</label>
        <p class="text-2xl font-extrabold text-primary mt-1">৳{{ number_format($totalSpend, 2) }}</p>
        <span class="text-[11px] text-white-dark mt-0.5">Approved Purchase Commitment</span>
    </div>
    <div class="panel text-center flex flex-col items-center justify-center">
        <label class="text-xs text-white-dark uppercase font-semibold">Total Orders</label>
        <p class="text-2xl font-extrabold text-info mt-1">{{ $totalOrders }}</p>
        <span class="text-[11px] text-white-dark mt-0.5">Issued Purchase Orders</span>
    </div>
    <div class="panel text-center flex flex-col items-center justify-center">
        <label class="text-xs text-white-dark uppercase font-semibold">Vendors Engaged</label>
        <p class="text-2xl font-extrabold text-success mt-1">{{ $byVendor->count() }}</p>
        <span class="text-[11px] text-white-dark mt-0.5">Active Material Suppliers</span>
    </div>
</div>

{{-- Dual Charts --}}
<div class="mb-6 grid gap-6 lg:grid-cols-2">
    <div class="panel flex flex-col justify-between">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-sm font-bold uppercase text-primary">Procurement Spend by Vendor</h5>
            <span class="badge badge-outline-primary text-xs">{{ $byVendor->count() }} Vendors</span>
        </div>
        <div id="vendorChart" class="w-full h-72"></div>
    </div>
    <div class="panel flex flex-col justify-between">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-sm font-bold uppercase text-primary">Procurement Spend by Project</h5>
            <span class="badge badge-outline-primary text-xs">{{ $byProject->count() }} Projects</span>
        </div>
        <div id="projectChart" class="w-full h-72"></div>
    </div>
</div>

{{-- PO Details Table --}}
<div class="panel">
    <div class="flex items-center justify-between mb-4">
        <h5 class="text-sm font-bold uppercase text-primary">Purchase Orders Breakdown</h5>
        <span class="badge badge-outline-primary text-xs">{{ count($orders) }} Records</span>
    </div>
    <div class="overflow-x-auto">
        <table class="table-hover w-full table-auto text-xs">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800 border-b dark:border-gray-700">
                    <th class="py-3 px-4 text-left">PO #</th>
                    <th class="py-3 px-4 text-left">Vendor Name</th>
                    <th class="py-3 px-4 text-left">Project</th>
                    <th class="py-3 px-4 text-right">Order Amount (৳)</th>
                    <th class="py-3 px-4 text-center">Order Date</th>
                    <th class="py-3 px-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $po)
                <tr class="border-t dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                    <td class="py-3 px-4 font-mono font-bold text-primary">{{ $po->po_number }}</td>
                    <td class="py-3 px-4 font-semibold text-sm">{{ $po->vendor->name ?? 'N/A' }}</td>
                    <td class="py-3 px-4 font-medium">{{ $po->project->name ?? 'N/A' }}</td>
                    <td class="py-3 px-4 text-right font-mono font-bold text-primary">৳{{ number_format($po->total_amount, 2) }}</td>
                    <td class="py-3 px-4 text-center font-medium">{{ $po->order_date->format('d M Y') }}</td>
                    <td class="py-3 px-4 text-center">
                        <span class="badge badge-outline-{{ $po->status === 'received' ? 'success' : ($po->status === 'ordered' ? 'info' : 'secondary') }} capitalize text-xs">
                            {{ str_replace('_',' ',$po->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-6 text-gray-400">No purchase orders found for the selected criteria.</td></tr>
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
        series: [{ name: 'Spend (৳)', data: [{{ $byVendor->map(fn($v) => $v['total'])->implode(',') }}] }],
        chart: { type: 'bar', height: 280, toolbar: { show: false }, background: 'transparent' },
        colors: ['#4361ee'],
        plotOptions: { bar: { horizontal: true, barHeight: '55%', borderRadius: 4 } },
        xaxis: { labels: { style: { colors: textColor, fontSize: '11px' } } },
        yaxis: { categories: [{{ $byVendor->map(fn($v) => "'".addslashes($v['vendor_name'])."'")->implode(',') }}], labels: { style: { colors: textColor, fontSize: '11px' } } },
        grid: { borderColor: isDark ? '#1b2e4b' : '#e0e6ed' },
        legend: { show: false },
        dataLabels: { enabled: true, formatter: v => '৳' + (v/1000).toFixed(0) + 'K', style: { fontSize: '10px' } },
        theme: { mode: isDark ? 'dark' : 'light' }
    }).render();

    new ApexCharts(document.querySelector("#projectChart"), {
        series: [{ name: 'Spend (৳)', data: [{{ $byProject->map(fn($p) => $p['total'])->implode(',') }}] }],
        chart: { type: 'bar', height: 280, toolbar: { show: false }, background: 'transparent' },
        colors: ['#00ab55'],
        plotOptions: { bar: { horizontal: true, barHeight: '55%', borderRadius: 4 } },
        xaxis: { labels: { style: { colors: textColor, fontSize: '11px' } } },
        yaxis: { categories: [{{ $byProject->map(fn($p) => "'".addslashes($p['project_name'])."'")->implode(',') }}], labels: { style: { colors: textColor, fontSize: '11px' } } },
        grid: { borderColor: isDark ? '#1b2e4b' : '#e0e6ed' },
        legend: { show: false },
        dataLabels: { enabled: true, formatter: v => '৳' + (v/1000).toFixed(0) + 'K', style: { fontSize: '10px' } },
        theme: { mode: isDark ? 'dark' : 'light' }
    }).render();
});
</script>
@endpush
