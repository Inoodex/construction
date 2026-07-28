@extends('admin.layouts.master')

@section('title', 'Project Cost Summary Report')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
@endpush

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold uppercase dark:text-white">Project Cost Summary Report</h2>
        <p class="mt-0.5 text-xs text-white-dark">Comprehensive project budget vs. actual cost, PO spend, and utilization analysis</p>
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

{{-- Global Totals Metric Cards --}}
<div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
    <div class="panel text-center flex flex-col items-center justify-center">
        <label class="text-xs text-white-dark uppercase font-semibold">Total Budget</label>
        <p class="text-xl font-extrabold text-primary mt-1">৳{{ number_format($totals['budget'], 2) }}</p>
        <span class="text-[11px] text-white-dark mt-0.5">Approved Project Cap</span>
    </div>
    <div class="panel text-center flex flex-col items-center justify-center">
        <label class="text-xs text-white-dark uppercase font-semibold">Actual Cost</label>
        <p class="text-xl font-extrabold text-warning mt-1">৳{{ number_format($totals['actual_cost'], 2) }}</p>
        <span class="text-[11px] text-white-dark mt-0.5">Direct Expenses &amp; Bills</span>
    </div>
    <div class="panel text-center flex flex-col items-center justify-center">
        <label class="text-xs text-white-dark uppercase font-semibold">PO Commitments</label>
        <p class="text-xl font-extrabold text-info mt-1">৳{{ number_format($totals['po_total'], 2) }}</p>
        <span class="text-[11px] text-white-dark mt-0.5">Committed Orders</span>
    </div>
    <div class="panel text-center flex flex-col items-center justify-center">
        <label class="text-xs text-white-dark uppercase font-semibold">Total Invoiced</label>
        <p class="text-xl font-extrabold text-success mt-1">৳{{ number_format($totals['invoiced'], 2) }}</p>
        <span class="text-[11px] text-white-dark mt-0.5">Supplier Invoices</span>
    </div>
    <div class="panel text-center flex flex-col items-center justify-center">
        <label class="text-xs text-white-dark uppercase font-semibold">Total Paid</label>
        <p class="text-xl font-extrabold text-secondary mt-1">৳{{ number_format($totals['paid'], 2) }}</p>
        <span class="text-[11px] text-white-dark mt-0.5">Disbursed Cash</span>
    </div>
    <div class="panel text-center flex flex-col items-center justify-center">
        <label class="text-xs text-white-dark uppercase font-semibold">Outstanding Due</label>
        <p class="text-xl font-extrabold text-danger mt-1">৳{{ number_format($totals['due'], 2) }}</p>
        <span class="text-[11px] text-white-dark mt-0.5">Unpaid Liabilities</span>
    </div>
</div>

{{-- Cost Overview Chart --}}
@if(count($summaries) > 0)
<div class="panel mb-6">
    <div class="flex items-center justify-between mb-4">
        <h5 class="text-sm font-bold uppercase text-primary">Budget vs. Total Spend Comparison</h5>
        <span class="badge badge-outline-primary text-xs">{{ count($summaries) }} Projects</span>
    </div>
    <div id="costComparisonChart" class="w-full h-80"></div>
</div>
@endif

{{-- Per Project Table --}}
<div class="panel">
    <div class="flex items-center justify-between mb-4">
        <h5 class="text-sm font-bold uppercase text-primary">Project Cost Breakdown</h5>
        <span class="badge badge-outline-primary text-xs">{{ count($summaries) }} Records</span>
    </div>
    <div class="overflow-x-auto">
        <table class="table-hover w-full table-auto text-xs">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800 border-b dark:border-gray-700">
                    <th class="py-3 px-4 text-left">Project Name</th>
                    <th class="py-3 px-4 text-right">Budget (৳)</th>
                    <th class="py-3 px-4 text-right">Actual Cost (৳)</th>
                    <th class="py-3 px-4 text-right">PO Spend (৳)</th>
                    <th class="py-3 px-4 text-right">Resource (৳)</th>
                    <th class="py-3 px-4 text-right">Total Spend (৳)</th>
                    <th class="py-3 px-4 text-right">Remaining (৳)</th>
                    <th class="py-3 px-4 text-center w-48">Utilization</th>
                </tr>
            </thead>
            <tbody>
                @forelse($summaries as $s)
                <tr class="border-t dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                    <td class="py-3 px-4 font-semibold text-sm text-primary">{{ $s['project']->name }}</td>
                    <td class="py-3 px-4 text-right font-mono font-semibold">৳{{ number_format($s['budget'], 2) }}</td>
                    <td class="py-3 px-4 text-right font-mono">৳{{ number_format($s['actual_cost'], 2) }}</td>
                    <td class="py-3 px-4 text-right font-mono">৳{{ number_format($s['po_total'], 2) }}</td>
                    <td class="py-3 px-4 text-right font-mono">৳{{ number_format($s['resource_cost'], 2) }}</td>
                    <td class="py-3 px-4 text-right font-mono font-bold text-primary">৳{{ number_format($s['total_spend'], 2) }}</td>
                    <td class="py-3 px-4 text-right font-mono font-bold {{ $s['remaining'] >= 0 ? 'text-success' : 'text-danger' }}">
                        ৳{{ number_format($s['remaining'], 2) }}
                    </td>
                    <td class="py-3 px-4 text-center">
                        <div class="flex items-center gap-2">
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden flex-1">
                                <div class="h-2 rounded-full {{ $s['utilization_pct'] > 100 ? 'bg-danger' : ($s['utilization_pct'] > 80 ? 'bg-warning' : 'bg-success') }}" style="width: {{ min($s['utilization_pct'], 100) }}%"></div>
                            </div>
                            <span class="text-xs font-bold font-mono min-w-[36px] text-right {{ $s['utilization_pct'] > 100 ? 'text-danger' : ($s['utilization_pct'] > 80 ? 'text-warning' : 'text-success') }}">
                                {{ $s['utilization_pct'] }}%
                            </span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-6 text-gray-400">No project cost data found for the selected filter.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if(count($summaries) > 0)
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const options = {
            series: [{
                name: 'Approved Budget',
                data: [ @foreach($summaries as $s) {{ $s['budget'] }}, @endforeach ]
            }, {
                name: 'Total Spend',
                data: [ @foreach($summaries as $s) {{ $s['total_spend'] }}, @endforeach ]
            }],
            chart: {
                type: 'bar',
                height: 320,
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '45%',
                    borderRadius: 4
                },
            },
            dataLabels: { enabled: false },
            colors: ['#4361ee', '#e7515a'],
            stroke: { show: true, width: 2, colors: ['transparent'] },
            xaxis: {
                categories: [ @foreach($summaries as $s) "{{ addslashes($s['project']->name) }}", @endforeach ],
                labels: { style: { fontSize: '11px' } }
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return "৳" + (val / 1000000).toFixed(1) + "M";
                    }
                }
            },
            fill: { opacity: 1 },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "৳" + val.toLocaleString('en-US', {minimumFractionDigits: 2});
                    }
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#costComparisonChart"), options);
        chart.render();
    });
</script>
@endif
@endsection
