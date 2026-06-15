@extends('admin.layouts.master')

@section('title', 'Project Cost Summary')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
@endpush

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold dark:text-white">Project Cost Summary</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Overall cost overview per project</p>
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

{{-- Global Totals --}}
<div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-6">
    <div class="panel stat-card"><p class="text-xl font-bold text-primary">৳{{ number_format($totals['budget']/1000000,1) }}M</p><p class="text-xs text-gray-500">Total Budget</p></div>
    <div class="panel stat-card"><p class="text-xl font-bold text-warning">৳{{ number_format($totals['actual_cost']/1000000,1) }}M</p><p class="text-xs text-gray-500">Actual Cost</p></div>
    <div class="panel stat-card"><p class="text-xl font-bold text-info">৳{{ number_format($totals['po_total']/1000000,1) }}M</p><p class="text-xs text-gray-500">PO Spend</p></div>
    <div class="panel stat-card"><p class="text-xl font-bold text-success">৳{{ number_format($totals['invoiced']/1000000,1) }}M</p><p class="text-xs text-gray-500">Invoiced</p></div>
    <div class="panel stat-card"><p class="text-xl font-bold text-secondary">৳{{ number_format($totals['paid']/1000000,1) }}M</p><p class="text-xs text-gray-500">Paid</p></div>
    <div class="panel stat-card"><p class="text-xl font-bold text-danger">৳{{ number_format($totals['due']/1000000,1) }}M</p><p class="text-xs text-gray-500">Due</p></div>
</div>

{{-- Per Project Table --}}
<div class="panel">
    <h5 class="mb-4 text-base font-semibold dark:text-white">Project Breakdown</h5>
    <div class="table-responsive">
        <table class="table-striped table">
            <thead>
                <tr>
                    <th class="text-xs">Project</th>
                    <th class="text-xs">Budget (৳)</th>
                    <th class="text-xs">Actual Cost (৳)</th>
                    <th class="text-xs">PO Spend (৳)</th>
                    <th class="text-xs">Resource (৳)</th>
                    <th class="text-xs">Total Spend (৳)</th>
                    <th class="text-xs">Remaining (৳)</th>
                    <th class="text-xs">Utilization</th>
                </tr>
            </thead>
            <tbody>
                @forelse($summaries as $s)
                <tr>
                    <td class="text-xs font-semibold">{{ $s['project']->name }}</td>
                    <td class="text-xs">{{ number_format($s['budget'], 2) }}</td>
                    <td class="text-xs">{{ number_format($s['actual_cost'], 2) }}</td>
                    <td class="text-xs">{{ number_format($s['po_total'], 2) }}</td>
                    <td class="text-xs">{{ number_format($s['resource_cost'], 2) }}</td>
                    <td class="text-xs font-semibold">{{ number_format($s['total_spend'], 2) }}</td>
                    <td class="text-xs {{ $s['remaining'] >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($s['remaining'], 2) }}</td>
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="progress-bar-outer flex-1">
                                <div class="progress-bar-inner {{ $s['utilization_pct'] > 100 ? 'bg-danger' : ($s['utilization_pct'] > 80 ? 'bg-warning' : 'bg-success') }}" style="width: {{ min($s['utilization_pct'], 100) }}%"></div>
                            </div>
                            <span class="text-xs {{ $s['utilization_pct'] > 100 ? 'text-danger' : '' }}">{{ $s['utilization_pct'] }}%</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-xs text-gray-400">No data found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
