@extends('admin.layouts.master')

@section('title', 'Labour & Equipment Utilisation Report')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
@endpush

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold dark:text-white">Labour & Equipment Utilisation</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Resource cost analysis by type and project</p>
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
            <label class="mb-1 block text-xs font-semibold text-gray-500">Resource Type</label>
            <select name="resource_type" class="form-select text-xs" onchange="this.form.submit()">
                <option value="all" {{ request('resource_type') == 'all' ? 'selected' : '' }}>All Types</option>
                <option value="labor" {{ request('resource_type') == 'labor' ? 'selected' : '' }}>Labour</option>
                <option value="equipment" {{ request('resource_type') == 'equipment' ? 'selected' : '' }}>Equipment</option>
                <option value="material" {{ request('resource_type') == 'material' ? 'selected' : '' }}>Material</option>
            </select>
        </div>
        <div class="flex items-end">
            <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary text-xs">Reset</a>
        </div>
    </div>
</form>

{{-- Summary Cards --}}
<div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div class="panel stat-card">
        <p class="text-2xl font-bold text-info">৳{{ number_format($summary['total_labour_cost'], 2) }}</p>
        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Labour Cost</p>
    </div>
    <div class="panel stat-card">
        <p class="text-2xl font-bold text-warning">৳{{ number_format($summary['total_equipment_cost'], 2) }}</p>
        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Equipment Cost</p>
    </div>
    <div class="panel stat-card">
        <p class="text-2xl font-bold text-secondary">৳{{ number_format($summary['total_material_cost'], 2) }}</p>
        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Material Cost</p>
    </div>
    <div class="panel stat-card">
        <p class="text-2xl font-bold text-primary">৳{{ number_format($summary['grand_total'], 2) }}</p>
        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Grand Total</p>
    </div>
</div>

{{-- Charts --}}
<div class="mb-6 grid gap-4 lg:grid-cols-2">
    <div class="panel">
        <h5 class="mb-4 text-base font-semibold dark:text-white">Cost by Resource Type</h5>
        <div id="costByTypeChart" class="min-h-[250px]"></div>
    </div>
    <div class="panel">
        <h5 class="mb-4 text-base font-semibold dark:text-white">Cost by Project</h5>
        <div id="costByProjectChart" class="min-h-[250px]"></div>
    </div>
</div>

{{-- Resource Type Breakdown --}}
<div class="mb-6 panel">
    <h5 class="mb-4 text-base font-semibold dark:text-white">Resource Type Summary</h5>
    <div class="table-responsive">
        <table class="table-striped table">
            <thead>
                <tr>
                    <th class="text-xs">Type</th>
                    <th class="text-xs">Items</th>
                    <th class="text-xs">Total Quantity</th>
                    <th class="text-xs">Total Cost (৳)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($byType as $bt)
                <tr>
                    <td class="text-xs font-semibold capitalize">{{ $bt['type'] }}</td>
                    <td class="text-xs">{{ $bt['count'] }}</td>
                    <td class="text-xs">{{ number_format($bt['total_qty'], 2) }}</td>
                    <td class="text-xs">{{ number_format($bt['total_cost'], 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-xs text-gray-400">No resource data found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Per Project Breakdown --}}
<div class="panel">
    <h5 class="mb-4 text-base font-semibold dark:text-white">Per Project Breakdown</h5>
    <div class="table-responsive">
        <table class="table-striped table">
            <thead>
                <tr>
                    <th class="text-xs">Project</th>
                    <th class="text-xs">Labour Cost (৳)</th>
                    <th class="text-xs">Labour Qty</th>
                    <th class="text-xs">Equipment Cost (৳)</th>
                    <th class="text-xs">Equipment Qty</th>
                    <th class="text-xs">Material Cost (৳)</th>
                    <th class="text-xs">Total (৳)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($byProject as $bp)
                <tr>
                    <td class="text-xs font-semibold">{{ $bp['project_name'] }}</td>
                    <td class="text-xs">{{ number_format($bp['labour_cost'], 2) }}</td>
                    <td class="text-xs">{{ number_format($bp['labour_qty'], 2) }}</td>
                    <td class="text-xs">{{ number_format($bp['equipment_cost'], 2) }}</td>
                    <td class="text-xs">{{ number_format($bp['equipment_qty'], 2) }}</td>
                    <td class="text-xs">{{ number_format($bp['material_cost'], 2) }}</td>
                    <td class="text-xs font-semibold">{{ number_format($bp['total_cost'], 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-xs text-gray-400">No data found</td></tr>
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
        chart: { type: 'donut', height: 250, toolbar: { show: false }, background: 'transparent' },
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
        chart: { type: 'bar', height: 250, toolbar: { show: false }, background: 'transparent', stacked: true },
        colors: ['#4361ee', '#e2a03f', '#00ab55'],
        plotOptions: { bar: { horizontal: true, barHeight: '60%', borderRadius: 4 } },
        xaxis: { labels: { style: { colors: textColor, fontSize: '11px' }, formatter: v => '৳' + (v/1000).toFixed(0) + 'K' } },
        yaxis: { categories: [{{ $byProject->map(fn($p) => "'{$p['project_name']}'")->implode(',') }}], labels: { style: { colors: textColor, fontSize: '10px' } } },
        grid: { borderColor: isDark ? '#1b2e4b' : '#e0e6ed' },
        legend: { labels: { colors: textColor } },
        dataLabels: { enabled: false },
        theme: { mode: isDark ? 'dark' : 'light' }
    }).render();
});
</script>
@endpush
