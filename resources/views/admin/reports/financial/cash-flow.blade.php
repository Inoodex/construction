@extends('admin.layouts.master')

@section('title', 'Cash Flow Report')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold dark:text-white">Cash Flow</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Expected inflows vs outflows over time</p>
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
            <label class="mb-1 block text-xs font-semibold text-gray-500">Months</label>
            <select name="months" class="form-select text-xs" onchange="this.form.submit()">
                @foreach([6, 12, 18, 24] as $m)
                    <option value="{{ $m }}" {{ (request('months') ?: 12) == $m ? 'selected' : '' }}>{{ $m }} months</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary text-xs">Reset</a>
        </div>
    </div>
</form>

<div class="mb-6 grid gap-4 sm:grid-cols-3">
    <div class="panel stat-card"><p class="text-2xl font-bold text-success">৳{{ number_format($totalInflow, 2) }}</p><p class="text-xs text-gray-500">Expected Inflow</p></div>
    <div class="panel stat-card"><p class="text-2xl font-bold text-danger">৳{{ number_format($totalOutflow, 2) }}</p><p class="text-xs text-gray-500">Expected Outflow</p></div>
    <div class="panel stat-card"><p class="text-2xl font-bold {{ $netCashFlow >= 0 ? 'text-success' : 'text-danger' }}">৳{{ number_format($netCashFlow, 2) }}</p><p class="text-xs text-gray-500">Net Cash Flow</p></div>
</div>

<div class="mb-6 panel">
    <h5 class="mb-4 text-base font-semibold dark:text-white">Cash Flow Projection</h5>
    <div id="cashFlowChart" class="min-h-[300px]"></div>
</div>

<div class="panel">
    <h5 class="mb-4 text-base font-semibold dark:text-white">Monthly Breakdown</h5>
    <div class="table-responsive">
        <table class="table-striped table">
            <thead>
                <tr>
                    <th class="text-xs">Month</th>
                    <th class="text-xs">Inflow (৳)</th>
                    <th class="text-xs">Outflow (৳)</th>
                    <th class="text-xs">Net (৳)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allMonths as $m)
                <tr>
                    <td class="text-xs font-semibold">{{ \Carbon\Carbon::createFromFormat('Y-m', $m['month'])->format('M Y') }}</td>
                    <td class="text-xs text-success">{{ number_format($m['inflow'], 2) }}</td>
                    <td class="text-xs text-danger">{{ number_format($m['outflow'], 2) }}</td>
                    <td class="text-xs {{ $m['net'] >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($m['net'], 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-xs text-gray-400">No data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.documentElement.classList.contains('dark');
    const textColor = isDark ? '#888ea8' : '#515365';

    new ApexCharts(document.querySelector("#cashFlowChart"), {
        series: [{
            name: 'Inflow',
            data: [{{ $allMonths->map(fn($m) => $m['inflow'])->implode(',') }}]
        }, {
            name: 'Outflow',
            data: [{{ $allMonths->map(fn($m) => $m['outflow'])->implode(',') }}]
        }],
        chart: { type: 'bar', height: 300, toolbar: { show: false }, background: 'transparent', stacked: false },
        colors: ['#00ab55', '#e7515a'],
        plotOptions: { bar: { horizontal: false, columnWidth: '55%', borderRadius: 4 } },
        xaxis: {
            categories: [{{ $allMonths->map(fn($m) => "'".\Carbon\Carbon::createFromFormat('Y-m', $m['month'])->format('M Y')."'")->implode(',') }}],
            labels: { style: { colors: textColor, fontSize: '11px' } }
        },
        yaxis: { labels: { style: { colors: textColor, fontSize: '11px' }, formatter: v => '৳' + (v/1000000).toFixed(1) + 'M' } },
        grid: { borderColor: isDark ? '#1b2e4b' : '#e0e6ed' },
        legend: { labels: { colors: textColor } },
        dataLabels: { enabled: false },
        theme: { mode: isDark ? 'dark' : 'light' }
    }).render();
});
</script>
@endpush
