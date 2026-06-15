@extends('admin.layouts.master')

@section('title', 'Retention Tracker')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
@endpush

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold dark:text-white">Retention Tracker</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Monitor retention amounts held, released, and pending</p>
    </div>
    @include('admin.reports.financial._export-buttons')
</div>

<form method="GET" class="panel mb-6">
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div>
            <label class="mb-1 block text-xs font-semibold text-gray-500">Project</label>
            <select name="project_id" class="form-select text-xs" onchange="this.form.submit()">
                <option value="">All Projects</option>
                @foreach($projects as $p)
                    <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary text-xs">Reset</a>
        </div>
    </div>
</form>

<div class="mb-6 grid gap-4 sm:grid-cols-3">
    <div class="panel stat-card"><p class="text-2xl font-bold text-warning">৳{{ number_format($totalRetention, 2) }}</p><p class="text-xs text-gray-500">Total Retention Held</p></div>
    <div class="panel stat-card"><p class="text-2xl font-bold text-success">৳{{ number_format($totalReleased, 2) }}</p><p class="text-xs text-gray-500">Released</p></div>
    <div class="panel stat-card"><p class="text-2xl font-bold text-danger">৳{{ number_format($totalPending, 2) }}</p><p class="text-xs text-gray-500">Pending Release</p></div>
</div>

<div class="mb-6 grid gap-4 lg:grid-cols-2">
    <div class="panel">
        <h5 class="mb-4 text-base font-semibold dark:text-white">Retention by Project</h5>
        <div id="retentionChart" class="min-h-[250px]"></div>
    </div>
    <div class="panel">
        <h5 class="mb-4 text-base font-semibold dark:text-white">Summary by Project</h5>
        <div class="table-responsive">
            <table class="table-striped table">
                <thead>
                    <tr><th class="text-xs">Project</th><th class="text-xs">Invoices</th><th class="text-xs">Total (৳)</th><th class="text-xs">Released (৳)</th><th class="text-xs">Pending (৳)</th><th class="text-xs">Released %</th></tr>
                </thead>
                <tbody>
                    @forelse($byProject as $bp)
                    <tr>
                        <td class="text-xs font-semibold">{{ $bp['project_name'] }}</td>
                        <td class="text-xs">{{ $bp['invoice_count'] }}</td>
                        <td class="text-xs">{{ number_format($bp['total_retention'], 2) }}</td>
                        <td class="text-xs text-success">{{ number_format($bp['released'], 2) }}</td>
                        <td class="text-xs text-danger">{{ number_format($bp['pending'], 2) }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="progress-bar-outer flex-1">
                                    <div class="progress-bar-inner bg-success" style="width: {{ $bp['release_pct'] }}%"></div>
                                </div>
                                <span class="text-xs">{{ $bp['release_pct'] }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-xs text-gray-400">No retention data found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="panel">
    <h5 class="mb-4 text-base font-semibold dark:text-white">Invoices with Retention</h5>
    <div class="table-responsive">
        <table class="table-striped table">
            <thead>
                <tr>
                    <th class="text-xs">Invoice #</th>
                    <th class="text-xs">Project</th>
                    <th class="text-xs">Total (৳)</th>
                    <th class="text-xs">Retention (৳)</th>
                    <th class="text-xs">Paid (৳)</th>
                    <th class="text-xs">Due (৳)</th>
                    <th class="text-xs">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                <tr>
                    <td class="text-xs font-mono font-semibold">{{ $inv->invoice_number }}</td>
                    <td class="text-xs">{{ $inv->project->name ?? 'N/A' }}</td>
                    <td class="text-xs">{{ number_format($inv->total_amount, 2) }}</td>
                    <td class="text-xs text-warning">{{ number_format($inv->retention_amount, 2) }}</td>
                    <td class="text-xs text-success">{{ number_format($inv->paid_amount, 2) }}</td>
                    <td class="text-xs text-danger">{{ number_format($inv->due_amount, 2) }}</td>
                    <td><span class="badge badge-outline-{{ $inv->status === 'paid' ? 'success' : ($inv->status === 'overdue' ? 'danger' : 'info') }} text-xs">{{ ucfirst(str_replace('_',' ',$inv->status)) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-xs text-gray-400">No invoices with retention found</td></tr>
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

    new ApexCharts(document.querySelector("#retentionChart"), {
        series: [{
            name: 'Released',
            data: [{{ $byProject->map(fn($p) => $p['released'])->implode(',') }}]
        }, {
            name: 'Pending',
            data: [{{ $byProject->map(fn($p) => $p['pending'])->implode(',') }}]
        }],
        chart: { type: 'bar', height: 250, toolbar: { show: false }, background: 'transparent', stacked: true },
        colors: ['#00ab55', '#e7515a'],
        plotOptions: { bar: { horizontal: true, barHeight: '60%', borderRadius: 4 } },
        xaxis: { labels: { style: { colors: textColor, fontSize: '11px' } } },
        yaxis: { categories: [{{ $byProject->map(fn($p) => "'{$p['project_name']}'")->implode(',') }}], labels: { style: { colors: textColor, fontSize: '10px' } } },
        grid: { borderColor: isDark ? '#1b2e4b' : '#e0e6ed' },
        legend: { labels: { colors: textColor } },
        dataLabels: { enabled: true, formatter: v => '৳' + (v/1000).toFixed(0) + 'K', style: { fontSize: '10px' } },
        theme: { mode: isDark ? 'dark' : 'light' }
    }).render();
});
</script>
@endpush
