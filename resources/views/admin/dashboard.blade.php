@extends('admin.layouts.master')

@section('title', 'Construction ERP Dashboard')

@push('styles')
<style>
    .stat-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
    }
    .badge-status {
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 20px;
        font-weight: 600;
        letter-spacing: 0.03em;
    }
    .progress-bar-outer {
        height: 6px;
        border-radius: 4px;
        background: #e9ecef;
        overflow: hidden;
    }
    .progress-bar-inner {
        height: 100%;
        border-radius: 4px;
        transition: width 0.6s ease;
    }
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold dark:text-white">Construction ERP Dashboard</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Welcome back, {{ auth()->user()->name }} &mdash; {{ now()->format('l, d M Y') }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('tyro-dashboard.index') }}" class="btn btn-sm btn-outline-primary gap-1">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Refresh
        </a>
    </div>
</div>

{{-- ===== ROW 1: KPI STAT CARDS ===== --}}
<div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

    {{-- Active Projects --}}
    <div class="panel stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-3xl font-bold text-primary dark:text-white">{{ $activeProjects }}</p>
                <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Active Projects</p>
                <p class="mt-2 text-xs text-gray-400">{{ $totalProjects }} total &middot; {{ $planningProjects }} planning</p>
            </div>
            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-primary/10 text-primary">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
        </div>
        <div class="mt-3 progress-bar-outer">
            <div class="progress-bar-inner bg-primary" style="width: {{ $totalProjects > 0 ? round(($activeProjects/$totalProjects)*100) : 0 }}%"></div>
        </div>
        <p class="mt-1 text-right text-xs text-gray-400">{{ $totalProjects > 0 ? round(($activeProjects/$totalProjects)*100) : 0 }}% active</p>
    </div>

    {{-- Total Budget --}}
    <div class="panel stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-3xl font-bold text-success dark:text-white">৳{{ number_format($totalBudget/1000000, 1) }}M</p>
                <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Total Budget</p>
                <p class="mt-2 text-xs text-gray-400">৳{{ number_format($totalPOValue/1000000, 2) }}M in POs raised</p>
            </div>
            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-success/10 text-success">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <div class="mt-3 progress-bar-outer">
            <div class="progress-bar-inner bg-success" style="width: {{ $totalBudget > 0 ? min(round(($totalPOValue/$totalBudget)*100), 100) : 0 }}%"></div>
        </div>
        <p class="mt-1 text-right text-xs text-gray-400">{{ $totalBudget > 0 ? min(round(($totalPOValue/$totalBudget)*100), 100) : 0 }}% procured</p>
    </div>

    {{-- Open Tasks --}}
    <div class="panel stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-3xl font-bold text-warning dark:text-white">{{ $openTasks + $inProgressTasks }}</p>
                <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Active Tasks</p>
                <p class="mt-2 text-xs text-gray-400">{{ $criticalTasks }} critical &middot; {{ $inProgressTasks }} in-progress</p>
            </div>
            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-warning/10 text-warning">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
        </div>
        @if($criticalTasks > 0)
        <div class="mt-3 flex items-center gap-1 rounded-lg bg-danger/10 px-2 py-1">
            <svg class="h-3 w-3 text-danger" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <span class="text-xs font-semibold text-danger">{{ $criticalTasks }} critical task{{ $criticalTasks > 1 ? 's' : '' }} pending</span>
        </div>
        @else
        <div class="mt-3 progress-bar-outer">
            <div class="progress-bar-inner bg-warning" style="width: {{ $totalTasks > 0 ? round((($openTasks+$inProgressTasks)/$totalTasks)*100) : 0 }}%"></div>
        </div>
        <p class="mt-1 text-right text-xs text-gray-400">{{ $totalTasks > 0 ? round((($openTasks+$inProgressTasks)/$totalTasks)*100) : 0 }}% open</p>
        @endif
    </div>

    {{-- Vendors --}}
    <div class="panel stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-3xl font-bold text-info dark:text-white">{{ $approvedVendors }}</p>
                <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Approved Vendors</p>
                <p class="mt-2 text-xs text-gray-400">{{ $pendingVendors }} pending approval &middot; {{ $totalVendors }} total</p>
            </div>
            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-info/10 text-info">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>
        @if($pendingVendors > 0)
        <div class="mt-3 flex items-center gap-1 rounded-lg bg-warning/10 px-2 py-1">
            <svg class="h-3 w-3 text-warning" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
            <span class="text-xs font-semibold text-warning">{{ $pendingVendors }} awaiting approval</span>
        </div>
        @else
        <div class="mt-3 flex items-center gap-1 rounded-lg bg-success/10 px-2 py-1">
            <svg class="h-3 w-3 text-success" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span class="text-xs font-semibold text-success">All vendors approved</span>
        </div>
        @endif
    </div>

</div>

{{-- ===== ROW 2: CHARTS ROW ===== --}}
<div class="mb-6 grid gap-4 lg:grid-cols-3">

    {{-- Project Status Donut Chart --}}
    <div class="panel">
        <div class="mb-4 flex items-center justify-between">
            <h5 class="text-base font-semibold dark:text-white">Project Status</h5>
            <span class="badge-status bg-primary/10 text-primary">{{ $totalProjects }} Total</span>
        </div>
        <div id="projectStatusChart" class="min-h-[220px]"></div>
        <div class="mt-4 grid grid-cols-2 gap-2 text-center text-xs">
            <div class="rounded-lg bg-primary/10 p-2">
                <p class="font-bold text-primary">{{ $activeProjects }}</p>
                <p class="text-gray-500">Active</p>
            </div>
            <div class="rounded-lg bg-warning/10 p-2">
                <p class="font-bold text-warning">{{ $planningProjects }}</p>
                <p class="text-gray-500">Planning</p>
            </div>
            <div class="rounded-lg bg-success/10 p-2">
                <p class="font-bold text-success">{{ $completedProjects }}</p>
                <p class="text-gray-500">Completed</p>
            </div>
            <div class="rounded-lg bg-gray-100 p-2 dark:bg-gray-800">
                <p class="font-bold text-gray-500">{{ $totalProjects - $activeProjects - $planningProjects - $completedProjects }}</p>
                <p class="text-gray-500">On Hold</p>
            </div>
        </div>
    </div>

    {{-- Task Priority Bar Chart --}}
    <div class="panel">
        <div class="mb-4 flex items-center justify-between">
            <h5 class="text-base font-semibold dark:text-white">Tasks by Priority</h5>
            <span class="badge-status bg-warning/10 text-warning">{{ $totalTasks }} Tasks</span>
        </div>
        <div id="taskPriorityChart" class="min-h-[220px]"></div>
        <div class="mt-4 grid grid-cols-2 gap-2 text-center text-xs">
            <div class="rounded-lg bg-danger/10 p-2">
                <p class="font-bold text-danger">{{ $tasksByPriority['critical'] ?? 0 }}</p>
                <p class="text-gray-500">Critical</p>
            </div>
            <div class="rounded-lg bg-warning/10 p-2">
                <p class="font-bold text-warning">{{ $tasksByPriority['high'] ?? 0 }}</p>
                <p class="text-gray-500">High</p>
            </div>
            <div class="rounded-lg bg-info/10 p-2">
                <p class="font-bold text-info">{{ $tasksByPriority['medium'] ?? 0 }}</p>
                <p class="text-gray-500">Medium</p>
            </div>
            <div class="rounded-lg bg-success/10 p-2">
                <p class="font-bold text-success">{{ $tasksByPriority['low'] ?? 0 }}</p>
                <p class="text-gray-500">Low</p>
            </div>
        </div>
    </div>

    {{-- Procurement Summary --}}
    <div class="panel">
        <div class="mb-4 flex items-center justify-between">
            <h5 class="text-base font-semibold dark:text-white">Procurement Overview</h5>
            <span class="badge-status bg-info/10 text-info">{{ $totalPOs }} POs</span>
        </div>

        {{-- PO Summary Stats --}}
        <div class="mb-4 space-y-3">
            <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-primary"></span>
                    <span class="text-xs text-gray-600 dark:text-gray-300">Total POs Raised</span>
                </div>
                <span class="text-sm font-bold dark:text-white">{{ $totalPOs }}</span>
            </div>
            <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-warning"></span>
                    <span class="text-xs text-gray-600 dark:text-gray-300">Pending / Ordered</span>
                </div>
                <span class="text-sm font-bold dark:text-white">{{ $pendingPOs }}</span>
            </div>
            <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-success"></span>
                    <span class="text-xs text-gray-600 dark:text-gray-300">Total PO Value</span>
                </div>
                <span class="text-sm font-bold dark:text-white">৳{{ number_format($totalPOValue) }}</span>
            </div>
            <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-info"></span>
                    <span class="text-xs text-gray-600 dark:text-gray-300">Active Sites</span>
                </div>
                <span class="text-sm font-bold dark:text-white">{{ $totalSites }}</span>
            </div>
        </div>
    </div>

</div>

{{-- ===== ROW 3: TABLES ROW ===== --}}
<div class="mb-6 grid gap-4 lg:grid-cols-2">

    {{-- Recent Projects Table --}}
    <div class="panel">
        <div class="mb-4 flex items-center justify-between">
            <h5 class="text-base font-semibold dark:text-white">Recent Projects</h5>
            {{-- <a href="#" class="text-xs text-primary hover:underline">View All →</a> --}}
        </div>
        <div class="table-responsive">
            <table class="table-striped table">
                <thead>
                    <tr>
                        <th class="text-xs">Project</th>
                        <th class="text-xs">Budget</th>
                        <th class="text-xs">Status</th>
                        <th class="text-xs">End Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentProjects as $project)
                    <tr>
                        <td>
                            <div class="font-semibold text-xs dark:text-white">{{ Str::limit($project->name, 30) }}</div>
                            <div class="text-xs text-gray-400">by {{ $project->creator->name ?? 'N/A' }}</div>
                        </td>
                        <td class="text-xs font-semibold">৳{{ number_format($project->budget/1000000, 1) }}M</td>
                        <td>
                            @php
                                $statusColors = ['active' => 'success', 'planning' => 'warning', 'completed' => 'primary', 'on_hold' => 'danger'];
                                $color = $statusColors[$project->status] ?? 'secondary';
                            @endphp
                            <span class="badge badge-outline-{{ $color }} text-xs">{{ ucfirst(str_replace('_',' ',$project->status)) }}</span>
                        </td>
                        <td class="text-xs text-gray-500">{{ $project->end_date->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-xs text-gray-400">No projects yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Purchase Orders --}}
    <div class="panel">
        <div class="mb-4 flex items-center justify-between">
            <h5 class="text-base font-semibold dark:text-white">Recent Purchase Orders</h5>
            {{-- <a href="#" class="text-xs text-primary hover:underline">View All →</a> --}}
        </div>
        <div class="table-responsive">
            <table class="table-striped table">
                <thead>
                    <tr>
                        <th class="text-xs">PO Number</th>
                        <th class="text-xs">Vendor</th>
                        <th class="text-xs">Amount</th>
                        <th class="text-xs">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPOs as $po)
                    <tr>
                        <td class="text-xs font-semibold font-mono text-primary">{{ $po->po_number }}</td>
                        <td class="text-xs">{{ Str::limit($po->vendor->name ?? 'N/A', 22) }}</td>
                        <td class="text-xs font-semibold">৳{{ number_format($po->total_amount) }}</td>
                        <td>
                            @php
                                $poColors = ['draft'=>'secondary','ordered'=>'info','partially_received'=>'warning','received'=>'success','cancelled'=>'danger'];
                                $poColor = $poColors[$po->status] ?? 'secondary';
                            @endphp
                            <span class="badge badge-outline-{{ $poColor }} text-xs">{{ ucfirst(str_replace('_',' ',$po->status)) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-xs text-gray-400">No purchase orders yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ===== ROW 4: LOW STOCK ALERTS ===== --}}
@if($lowStockItems->count() > 0)
<div class="mb-6">
    <div class="panel border-l-4 border-danger">
        <div class="mb-4 flex items-center gap-2">
            <svg class="h-5 w-5 text-danger" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <h5 class="text-base font-semibold text-danger">Low Stock Alerts</h5>
            <span class="badge-status bg-danger/10 text-danger">{{ $lowStockItems->count() }} item{{ $lowStockItems->count() > 1 ? 's' : '' }}</span>
        </div>
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
            @foreach($lowStockItems as $stock)
            <div class="rounded-lg border border-danger/20 bg-danger/5 p-3">
                <p class="text-xs font-bold dark:text-white">{{ $stock->material->name ?? 'Unknown' }}</p>
                <p class="mt-1 text-xs text-gray-500">
                    {{ $stock->warehouse ? $stock->warehouse->name : ($stock->site ? $stock->site->name : 'Unknown location') }}
                </p>
                <p class="mt-2 text-lg font-black text-danger">{{ number_format($stock->quantity, 2) }}</p>
                <p class="text-xs text-gray-400">{{ $stock->material->unit ?? 'units' }} remaining</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Detect dark mode
    const isDark = document.documentElement.classList.contains('dark') ||
                   localStorage.getItem('theme') === 'dark';
    const textColor = isDark ? '#888ea8' : '#515365';
    const gridColor = isDark ? '#1b2e4b' : '#e0e6ed';

    // --- PROJECT STATUS DONUT ---
    const projectChart = new ApexCharts(document.querySelector("#projectStatusChart"), {
        series: [
            {{ $projectsByStatus['active'] ?? 0 }},
            {{ $projectsByStatus['planning'] ?? 0 }},
            {{ $projectsByStatus['completed'] ?? 0 }},
            {{ $projectsByStatus['on_hold'] ?? 0 }}
        ],
        labels: ['Active', 'Planning', 'Completed', 'On Hold'],
        chart: {
            type: 'donut',
            height: 220,
            toolbar: { show: false },
            background: 'transparent',
        },
        colors: ['#4361ee', '#e2a03f', '#00ab55', '#e7515a'],
        legend: {
            position: 'bottom',
            fontSize: '11px',
            labels: { colors: textColor },
        },
        dataLabels: {
            enabled: true,
            formatter: (val) => Math.round(val) + '%',
            style: { fontSize: '10px' }
        },
        stroke: { width: 2 },
        plotOptions: {
            pie: {
                donut: {
                    size: '60%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            color: textColor,
                            fontSize: '11px',
                            formatter: () => {{ $totalProjects }}
                        }
                    }
                }
            }
        },
        theme: { mode: isDark ? 'dark' : 'light' }
    });
    projectChart.render();

    // --- TASK PRIORITY BAR ---
    const taskChart = new ApexCharts(document.querySelector("#taskPriorityChart"), {
        series: [{
            name: 'Tasks',
            data: [
                {{ $tasksByPriority['critical'] ?? 0 }},
                {{ $tasksByPriority['high'] ?? 0 }},
                {{ $tasksByPriority['medium'] ?? 0 }},
                {{ $tasksByPriority['low'] ?? 0 }}
            ]
        }],
        chart: {
            type: 'bar',
            height: 220,
            toolbar: { show: false },
            background: 'transparent',
        },
        colors: ['#e7515a', '#e2a03f', '#4361ee', '#00ab55'],
        plotOptions: {
            bar: {
                distributed: true,
                horizontal: true,
                barHeight: '55%',
                borderRadius: 4,
            }
        },
        xaxis: {
            categories: ['Critical', 'High', 'Medium', 'Low'],
            labels: { style: { colors: textColor, fontSize: '11px' } },
        },
        yaxis: {
            labels: { style: { colors: textColor, fontSize: '11px' } },
        },
        grid: { borderColor: gridColor },
        legend: { show: false },
        dataLabels: {
            enabled: true,
            style: { fontSize: '11px' }
        },
        theme: { mode: isDark ? 'dark' : 'light' }
    });
    taskChart.render();

});
</script>
@endpush