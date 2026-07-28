@extends('admin.layouts.master')

@section('title', 'Labour & Equipment Utilisation Report')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
@endpush

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold uppercase dark:text-white">Labour &amp; Equipment Utilisation Report</h2>
        <p class="mt-0.5 text-xs text-white-dark">Comprehensive resource cost analysis by type, quantity, and project allocation</p>
    </div>
    @include('admin.reports.financial._export-buttons')
</div>

{{-- Filter Bar --}}
<div class="panel mb-6">
    <form method="GET" class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-white-dark whitespace-nowrap">Filter Project:</span>
                <select name="project_id" class="form-select w-56 text-xs" onchange="this.form.submit()">
                    <option value="">All Projects</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-white-dark whitespace-nowrap">Resource Type:</span>
                <select name="resource_type" class="form-select w-44 text-xs" onchange="this.form.submit()">
                    <option value="all" {{ request('resource_type') == 'all' ? 'selected' : '' }}>All Types</option>
                    @foreach(\App\Models\Category::resourceTypes()->get() as $cat)
                        <option value="{{ $cat->value }}" {{ request('resource_type') == $cat->value ? 'selected' : '' }}>{{ $cat->label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @if(request()->filled('project_id') || (request()->filled('resource_type') && request('resource_type') !== 'all'))
            <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-danger">Reset Filters</a>
        @endif
    </form>
</div>

{{-- Summary Cards Grid --}}
<div class="mb-6 grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
    <div class="panel text-center flex flex-col items-center justify-center">
        <label class="text-xs text-white-dark uppercase font-semibold">Labour Cost</label>
        <p class="text-2xl font-extrabold text-info mt-1">৳{{ number_format($summary['total_labour_cost'], 2) }}</p>
        <span class="text-[11px] text-white-dark mt-0.5">Manpower &amp; Site Wages</span>
    </div>
    <div class="panel text-center flex flex-col items-center justify-center">
        <label class="text-xs text-white-dark uppercase font-semibold">Equipment Cost</label>
        <p class="text-2xl font-extrabold text-warning mt-1">৳{{ number_format($summary['total_equipment_cost'], 2) }}</p>
        <span class="text-[11px] text-white-dark mt-0.5">Machinery &amp; Plant Rentals</span>
    </div>
    <div class="panel text-center flex flex-col items-center justify-center">
        <label class="text-xs text-white-dark uppercase font-semibold">Material Cost</label>
        <p class="text-2xl font-extrabold text-secondary mt-1">৳{{ number_format($summary['total_material_cost'], 2) }}</p>
        <span class="text-[11px] text-white-dark mt-0.5">Raw Materials Consumed</span>
    </div>
    <div class="panel text-center flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-800">
        <label class="text-xs text-white-dark uppercase font-semibold">Grand Total Resource Spend</label>
        <p class="text-2xl font-extrabold text-primary dark:text-white mt-1">৳{{ number_format($summary['grand_total'], 2) }}</p>
        <span class="text-[11px] text-white-dark mt-0.5">Combined Resource Utilization</span>
    </div>
</div>

{{-- Dual Charts --}}
<div class="mb-6 grid gap-6 lg:grid-cols-2">
    <div class="panel flex flex-col justify-between">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-sm font-bold uppercase text-primary">Cost Distribution by Resource Type</h5>
            <span class="badge badge-outline-primary text-xs">{{ count($byType) }} Types</span>
        </div>
        <div id="costByTypeChart" class="w-full h-72 flex items-center justify-center"></div>
    </div>
    <div class="panel flex flex-col justify-between">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-sm font-bold uppercase text-primary">Resource Spend Allocation by Project</h5>
            <span class="badge badge-outline-primary text-xs">{{ count($byProject) }} Projects</span>
        </div>
        <div id="costByProjectChart" class="w-full h-72"></div>
    </div>
</div>

{{-- Resource Type Summary Table --}}
<div class="mb-6 panel">
    <div class="flex items-center justify-between mb-4">
        <h5 class="text-sm font-bold uppercase text-primary">Resource Type Summary Breakdown</h5>
        <span class="badge badge-outline-primary text-xs">{{ count($byType) }} Categories</span>
    </div>
    <div class="overflow-x-auto">
        <table class="table-hover w-full table-auto text-xs">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800 border-b dark:border-gray-700">
                    <th class="py-3 px-4 text-left">Resource Type</th>
                    <th class="py-3 px-4 text-center">Items Count</th>
                    <th class="py-3 px-4 text-right">Total Quantity</th>
                    <th class="py-3 px-4 text-right">Total Cost (৳)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($byType as $bt)
                <tr class="border-t dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                    <td class="py-3 px-4 font-semibold text-sm capitalize text-primary">{{ $bt['type'] }}</td>
                    <td class="py-3 px-4 text-center font-mono">{{ $bt['count'] }}</td>
                    <td class="py-3 px-4 text-right font-mono">{{ number_format($bt['total_qty'], 2) }}</td>
                    <td class="py-3 px-4 text-right font-mono font-bold text-primary">৳{{ number_format($bt['total_cost'], 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-6 text-gray-400">No resource type summary data found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Per Project Breakdown Table --}}
<div class="panel">
    <div class="flex items-center justify-between mb-4">
        <h5 class="text-sm font-bold uppercase text-primary">Per Project Resource Allocation Breakdown</h5>
        <span class="badge badge-outline-primary text-xs">{{ count($byProject) }} Projects</span>
    </div>
    <div class="overflow-x-auto">
        <table class="table-hover w-full table-auto text-xs">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800 border-b dark:border-gray-700">
                    <th class="py-3 px-4 text-left">Project Name</th>
                    <th class="py-3 px-4 text-right">Labour Cost (৳)</th>
                    <th class="py-3 px-4 text-right">Labour Qty</th>
                    <th class="py-3 px-4 text-right">Equipment Cost (৳)</th>
                    <th class="py-3 px-4 text-right">Equipment Qty</th>
                    <th class="py-3 px-4 text-right">Material Cost (৳)</th>
                    <th class="py-3 px-4 text-right">Grand Total (৳)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($byProject as $bp)
                <tr class="border-t dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                    <td class="py-3 px-4 font-semibold text-sm text-primary">{{ $bp['project_name'] }}</td>
                    <td class="py-3 px-4 text-right font-mono text-info">৳{{ number_format($bp['labour_cost'], 2) }}</td>
                    <td class="py-3 px-4 text-right font-mono text-white-dark">{{ number_format($bp['labour_qty'], 2) }}</td>
                    <td class="py-3 px-4 text-right font-mono text-warning">৳{{ number_format($bp['equipment_cost'], 2) }}</td>
                    <td class="py-3 px-4 text-right font-mono text-white-dark">{{ number_format($bp['equipment_qty'], 2) }}</td>
                    <td class="py-3 px-4 text-right font-mono text-secondary">৳{{ number_format($bp['material_cost'], 2) }}</td>
                    <td class="py-3 px-4 text-right font-mono font-extrabold text-primary">৳{{ number_format($bp['total_cost'], 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-6 text-gray-400">No project breakdown data found.</td></tr>
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

    new ApexCharts(document.querySelector("#costByTypeChart"), {
        series: [{{ $byType->map(fn($t) => $t['total_cost'])->implode(',') }}],
        labels: [{{ $byType->keys()->map(fn($k) => "'".ucfirst($k)."'")->implode(',') }}],
        chart: { type: 'donut', height: 280, toolbar: { show: false }, background: 'transparent' },
        colors: ['#4361ee', '#e2a03f', '#00ab55'],
        legend: { position: 'bottom', fontSize: '11px', labels: { colors: textColor } },
        dataLabels: { enabled: true, formatter: v => Math.round(v) + '%', style: { fontSize: '10px' } },
        stroke: { width: 2 },
        theme: { mode: isDark ? 'dark' : 'light' }
    }).render();

    new ApexCharts(document.querySelector("#costByProjectChart"), {
        series: [{
            name: 'Labour',
            data: [{{ $byProject->map(fn($p) => $p['labour_cost'])->implode(',') }}]
        }, {
            name: 'Equipment',
            data: [{{ $byProject->map(fn($p) => $p['equipment_cost'])->implode(',') }}]
        }, {
            name: 'Material',
            data: [{{ $byProject->map(fn($p) => $p['material_cost'])->implode(',') }}]
        }],
        chart: { type: 'bar', height: 280, toolbar: { show: false }, background: 'transparent', stacked: true },
        colors: ['#4361ee', '#e2a03f', '#00ab55'],
        plotOptions: { bar: { horizontal: true, barHeight: '55%', borderRadius: 4 } },
        xaxis: { labels: { style: { colors: textColor, fontSize: '11px' }, formatter: v => '৳' + (v/1000).toFixed(0) + 'K' } },
        yaxis: { categories: [{{ $byProject->map(fn($p) => "'".addslashes($p['project_name'])."'")->implode(',') }}], labels: { style: { colors: textColor, fontSize: '11px' } } },
        grid: { borderColor: isDark ? '#1b2e4b' : '#e0e6ed' },
        legend: { labels: { colors: textColor } },
        dataLabels: { enabled: false },
        theme: { mode: isDark ? 'dark' : 'light' }
    }).render();
});
</script>
@endpush
