@extends('admin.layouts.master')

@section('title', 'Budget vs Actual Report')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
@endpush

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold dark:text-white">Budget vs Actual</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Compare budgeted amounts against actual spending by cost code</p>
    </div>
    @include('admin.reports.financial._export-buttons')
</div>

<form method="GET" class="panel mb-6">
    @include('admin.reports.financial._filters')
</form>

{{-- Summary Cards --}}
<div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div class="panel stat-card">
        <p class="text-2xl font-bold text-primary dark:text-white">৳{{ number_format($totalBudgeted, 2) }}</p>
        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Total Budgeted</p>
    </div>
    <div class="panel stat-card">
        <p class="text-2xl font-bold text-warning dark:text-white">৳{{ number_format($totalActual, 2) }}</p>
        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Total Actual</p>
    </div>
    <div class="panel stat-card">
        <p class="text-2xl font-bold {{ $variance >= 0 ? 'text-success' : 'text-danger' }} dark:text-white">৳{{ number_format($variance, 2) }}</p>
        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Variance</p>
    </div>
    <div class="panel stat-card">
        <p class="text-2xl font-bold {{ $variancePct >= 0 ? 'text-success' : 'text-danger' }} dark:text-white">{{ $variancePct }}%</p>
        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Variance %</p>
    </div>
</div>

{{-- Chart --}}
<div class="mb-6 panel">
    <div id="budgetChart" class="min-h-[300px]"></div>
</div>

{{-- Cost Code Breakdown Table --}}
<div class="panel">
    <h5 class="mb-4 text-base font-semibold dark:text-white">Cost Code Breakdown</h5>
    <div class="table-responsive">
        <table class="table-striped table">
            <thead>
                <tr>
                    <th class="text-xs">Cost Code</th>
                    <th class="text-xs">Budgeted (৳)</th>
                    <th class="text-xs">Actual (৳)</th>
                    <th class="text-xs">Variance (৳)</th>
                    <th class="text-xs">Variance %</th>
                    <th class="text-xs">Items</th>
                </tr>
            </thead>
            <tbody>
                @forelse($costCodes as $code => $cc)
                <tr>
                    <td class="text-xs font-semibold font-mono">{{ $code }}</td>
                    <td class="text-xs">{{ number_format($cc['budgeted'], 2) }}</td>
                    <td class="text-xs">{{ number_format($cc['actual'], 2) }}</td>
                    <td class="text-xs {{ $cc['variance'] >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($cc['variance'], 2) }}</td>
                    <td class="text-xs {{ $cc['variance_pct'] >= 0 ? 'text-success' : 'text-danger' }}">{{ $cc['variance_pct'] }}%</td>
                    <td class="text-xs">{{ $cc['count'] }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-xs text-gray-400">No budget data found</td></tr>
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

    new ApexCharts(document.querySelector("#budgetChart"), {
        series: [{
            name: 'Budgeted',
            data: [{{ $costCodes->map(fn($c) => $c['budgeted'])->implode(',') }}]
        }, {
            name: 'Actual',
            data: [{{ $costCodes->map(fn($c) => $c['actual'])->implode(',') }}]
        }],
        chart: { type: 'bar', height: 300, toolbar: { show: false }, background: 'transparent' },
        colors: ['#4361ee', '#e2a03f'],
        plotOptions: { bar: { horizontal: false, columnWidth: '55%', borderRadius: 4 } },
        xaxis: {
            categories: [{{ $costCodes->keys()->map(fn($k) => "'$k'")->implode(',') }}],
            labels: { style: { colors: textColor, fontSize: '11px' } }
        },
        yaxis: { labels: { style: { colors: textColor, fontSize: '11px' } } },
        grid: { borderColor: isDark ? '#1b2e4b' : '#e0e6ed' },
        legend: { labels: { colors: textColor } },
        theme: { mode: isDark ? 'dark' : 'light' }
    }).render();
});
</script>
@endpush
