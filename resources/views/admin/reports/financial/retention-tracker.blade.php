@extends('admin.layouts.master')

@section('title', 'Retention Tracker Report')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
@endpush

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold uppercase dark:text-white">Retention Tracker Report</h2>
        <p class="mt-0.5 text-xs text-white-dark">Monitor contract retention amounts held, released, and pending across projects</p>
    </div>
    @include('admin.reports.financial._export-buttons')
</div>

{{-- Filter Bar --}}
<div class="panel mb-6">
    <form method="GET" class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="text-xs font-semibold text-white-dark whitespace-nowrap">Filter Project:</span>
            <select name="project_id" class="form-select w-64 text-xs" onchange="this.form.submit()">
                <option value="">All Projects</option>
                @foreach($projects as $p)
                    <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        @if(request()->filled('project_id'))
            <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-danger">Reset Filter</a>
        @endif
    </form>
</div>

{{-- Metric Cards Grid --}}
<div class="mb-6 grid gap-6 sm:grid-cols-3">
    <div class="panel text-center flex flex-col items-center justify-center">
        <label class="text-xs text-white-dark uppercase font-semibold">Total Retention Held</label>
        <p class="text-2xl font-extrabold text-warning mt-1">৳{{ number_format($totalRetention, 2) }}</p>
        <span class="text-[11px] text-white-dark mt-0.5">Contract Guarantee Deductions</span>
    </div>
    <div class="panel text-center flex flex-col items-center justify-center">
        <label class="text-xs text-white-dark uppercase font-semibold">Released Retention</label>
        <p class="text-2xl font-extrabold text-success mt-1">৳{{ number_format($totalReleased, 2) }}</p>
        <span class="text-[11px] text-white-dark mt-0.5">Disbursed Back to Contractors</span>
    </div>
    <div class="panel text-center flex flex-col items-center justify-center">
        <label class="text-xs text-white-dark uppercase font-semibold">Pending Release</label>
        <p class="text-2xl font-extrabold text-danger mt-1">৳{{ number_format($totalPending, 2) }}</p>
        <span class="text-[11px] text-white-dark mt-0.5">Held Until DLP Completion</span>
    </div>
</div>

{{-- Dual Column Chart & Summary --}}
<div class="mb-6 grid gap-6 lg:grid-cols-2">
    <div class="panel flex flex-col justify-between">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-sm font-bold uppercase text-primary">Retention Status by Project</h5>
            <span class="badge badge-outline-primary text-xs">{{ count($byProject) }} Projects</span>
        </div>
        <div id="retentionChart" class="w-full h-72"></div>
    </div>
    <div class="panel flex flex-col justify-between">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-sm font-bold uppercase text-primary">Project Retention Summary</h5>
            <span class="badge badge-outline-primary text-xs">{{ count($byProject) }} Records</span>
        </div>
        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto text-xs">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800 border-b dark:border-gray-700">
                        <th class="py-2.5 px-3 text-left">Project</th>
                        <th class="py-2.5 px-3 text-center">Inv #</th>
                        <th class="py-2.5 px-3 text-right">Total (৳)</th>
                        <th class="py-2.5 px-3 text-right">Released (৳)</th>
                        <th class="py-2.5 px-3 text-right">Pending (৳)</th>
                        <th class="py-2.5 px-3 text-center w-28">Release %</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($byProject as $bp)
                    <tr class="border-t dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="py-2.5 px-3 font-semibold text-primary">{{ $bp['project_name'] }}</td>
                        <td class="py-2.5 px-3 text-center font-mono">{{ $bp['invoice_count'] }}</td>
                        <td class="py-2.5 px-3 text-right font-mono font-semibold">৳{{ number_format($bp['total_retention'], 2) }}</td>
                        <td class="py-2.5 px-3 text-right font-mono text-success font-semibold">৳{{ number_format($bp['released'], 2) }}</td>
                        <td class="py-2.5 px-3 text-right font-mono text-danger font-semibold">৳{{ number_format($bp['pending'], 2) }}</td>
                        <td class="py-2.5 px-3 text-center">
                            <div class="flex items-center gap-1.5">
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 overflow-hidden flex-1">
                                    <div class="h-1.5 rounded-full bg-success" style="width: {{ min($bp['release_pct'], 100) }}%"></div>
                                </div>
                                <span class="text-[11px] font-bold font-mono min-w-[28px] text-right text-success">{{ $bp['release_pct'] }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-6 text-gray-400">No retention summary data found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Invoices Table --}}
<div class="panel">
    <div class="flex items-center justify-between mb-4">
        <h5 class="text-sm font-bold uppercase text-primary">Invoices with Retention Deductions</h5>
        <span class="badge badge-outline-primary text-xs">{{ count($invoices) }} Invoices</span>
    </div>
    <div class="overflow-x-auto">
        <table class="table-hover w-full table-auto text-xs">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800 border-b dark:border-gray-700">
                    <th class="py-3 px-4 text-left">Invoice #</th>
                    <th class="py-3 px-4 text-left">Project</th>
                    <th class="py-3 px-4 text-right">Total Amount (৳)</th>
                    <th class="py-3 px-4 text-right">Retention (৳)</th>
                    <th class="py-3 px-4 text-right">Paid Amount (৳)</th>
                    <th class="py-3 px-4 text-right">Remaining Due (৳)</th>
                    <th class="py-3 px-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                <tr class="border-t dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                    <td class="py-3 px-4 font-mono font-bold text-primary">{{ $inv->invoice_number }}</td>
                    <td class="py-3 px-4 font-semibold text-sm">{{ $inv->project->name ?? 'N/A' }}</td>
                    <td class="py-3 px-4 text-right font-mono font-semibold">৳{{ number_format($inv->total_amount, 2) }}</td>
                    <td class="py-3 px-4 text-right font-mono font-bold text-warning">৳{{ number_format($inv->retention_amount, 2) }}</td>
                    <td class="py-3 px-4 text-right font-mono text-success">৳{{ number_format($inv->paid_amount, 2) }}</td>
                    <td class="py-3 px-4 text-right font-mono font-semibold {{ $inv->due_amount > 0 ? 'text-danger' : 'text-success' }}">
                        ৳{{ number_format($inv->due_amount, 2) }}
                    </td>
                    <td class="py-3 px-4 text-center">
                        <span class="badge badge-outline-{{ $inv->status === 'paid' ? 'success' : ($inv->status === 'overdue' ? 'danger' : 'info') }} capitalize text-xs">
                            {{ str_replace('_', ' ', $inv->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-6 text-gray-400">No invoices with retention deductions found.</td></tr>
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
            name: 'Pending Release',
            data: [{{ $byProject->map(fn($p) => $p['pending'])->implode(',') }}]
        }],
        chart: { type: 'bar', height: 280, toolbar: { show: false }, background: 'transparent', stacked: true },
        colors: ['#00ab55', '#e7515a'],
        plotOptions: { bar: { horizontal: true, barHeight: '55%', borderRadius: 4 } },
        xaxis: { labels: { style: { colors: textColor, fontSize: '11px' } } },
        yaxis: { categories: [{{ $byProject->map(fn($p) => "'".addslashes($p['project_name'])."'")->implode(',') }}], labels: { style: { colors: textColor, fontSize: '11px' } } },
        grid: { borderColor: isDark ? '#1b2e4b' : '#e0e6ed' },
        legend: { labels: { colors: textColor } },
        dataLabels: { enabled: true, formatter: v => '৳' + (v/1000).toFixed(0) + 'K', style: { fontSize: '10px' } },
        theme: { mode: isDark ? 'dark' : 'light' }
    }).render();
});
</script>
@endpush
