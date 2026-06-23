@extends('admin.layouts.master')

@section('title', 'Construction ERP Dashboard')

@push('styles')
<style>
    .stat-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,0.12); }
    .badge-status { font-size: 0.7rem; padding: 2px 8px; border-radius: 20px; font-weight: 600; letter-spacing: 0.03em; }
    .progress-bar-outer { height: 6px; border-radius: 4px; background: #e9ecef; overflow: hidden; }
    .progress-bar-inner { height: 100%; border-radius: 4px; transition: width 0.6s ease; }
    .mini-stat { @apply flex items-center gap-3 rounded-lg p-3; }
    .mini-stat-icon { @apply flex h-10 w-10 items-center justify-center rounded-full; }
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold dark:text-white">Construction ERP Dashboard</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Welcome back, {{ auth()->user()?->name }} &mdash; {{ now()->format('l, d M Y') }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('tyro-dashboard.index') }}" class="btn btn-sm btn-outline-primary gap-1">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Refresh
        </a>
    </div>
</div>

{{-- ===== ROW 1: KPI STAT CARDS ===== --}}
<div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">

    {{-- 1. Active Projects --}}
    <div class="panel stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-3xl font-bold text-primary">{{ $activeProjects }}</p>
                <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Active Projects</p>
                <p class="mt-2 text-xs text-gray-400">{{ $totalProjects }} total</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
        </div>
    </div>

    {{-- 2. Total Budget --}}
    <div class="panel stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-3xl font-bold text-success">BDT{{ number_format($totalBudget/10000000, 2) }}Cr</p>
                <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Total Budget</p>
                <p class="mt-2 text-xs text-gray-400">{{ $totalSites }} sites</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-success/10 text-success">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    {{-- 3. Active Tasks --}}
    <div class="panel stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-3xl font-bold text-warning">{{ $inProgressTasks }}</p>
                <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">In Progress Tasks</p>
                <p class="mt-2 text-xs text-gray-400">{{ $openTasks }} open &middot; {{ $criticalTasks }} critical</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-warning/10 text-warning">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
        </div>
    </div>

    {{-- 4. Employees --}}
    <div class="panel stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-3xl font-bold text-info">{{ $activeEmployees }}</p>
                <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Active Employees</p>
                <p class="mt-2 text-xs text-gray-400">{{ $totalEmployees }} total &middot; {{ $todayAttendance }} today</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-info">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>
    </div>

    {{-- 5. Vendors --}}
    <div class="panel stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-3xl font-bold text-primary">{{ $approvedVendors }}</p>
                <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Approved Vendors</p>
                <p class="mt-2 text-xs text-gray-400">{{ $pendingVendors }} pending &middot; {{ $totalVendors }} total</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>
    </div>

    {{-- 6. Pending Actions --}}
    <div class="panel stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-3xl font-bold text-danger">{{ $pendingApprovals + $totalLeavePending + $activeMaintenance }}</p>
                <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Pending Actions</p>
                <p class="mt-2 text-xs text-gray-400">{{ $pendingApprovals }} approvals &middot; {{ $totalLeavePending }} leaves &middot; {{ $activeMaintenance }} maintenance</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-danger/10 text-danger">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

</div>

{{-- ===== ROW 2: DOMAIN SUMMARY CARDS ===== --}}
<div class="mb-6 grid gap-4 lg:grid-cols-4">

    {{-- Core Summary --}}
    <div class="panel">
        <h5 class="mb-4 text-sm font-bold uppercase tracking-wider text-primary">Core</h5>
        <div class="space-y-3 text-sm">
            <div class="flex items-center justify-between">
                <span class="text-gray-500">Projects</span>
                <span class="font-semibold">{{ $totalProjects }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-500">Sites</span>
                <span class="font-semibold">{{ $totalSites }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-500">Tasks</span>
                <span class="font-semibold">{{ $totalTasks }}</span>
            </div>
            <div class="mt-2">
                <div class="flex items-center justify-between text-xs">
                    <span>Active</span><span>{{ $activeProjects }}/{{ $totalProjects }}</span>
                </div>
                <div class="progress-bar-outer mt-1">
                    <div class="progress-bar-inner bg-primary" style="width: {{ $totalProjects > 0 ? round(($activeProjects/$totalProjects)*100) : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Procurement Summary --}}
    <div class="panel">
        <h5 class="mb-4 text-sm font-bold uppercase tracking-wider text-warning">Procurement</h5>
        <div class="space-y-3 text-sm">
            <div class="flex items-center justify-between">
                <span class="text-gray-500">Vendors</span>
                <span class="font-semibold">{{ $totalVendors }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-500">Purchase Orders</span>
                <span class="font-semibold">{{ $totalPOs }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-500">PO Value</span>
                <span class="font-semibold">BDT{{ number_format($totalPOValue) }}</span>
            </div>
            <a href="{{ route('admin.procurement.purchase-orders.index') }}" class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-primary hover:underline">
                View POs <span>&rarr;</span>
            </a>
        </div>
    </div>

    {{-- HR Summary --}}
    <div class="panel">
        <h5 class="mb-4 text-sm font-bold uppercase tracking-wider text-info">Human Resources</h5>
        <div class="space-y-3 text-sm">
            <div class="flex items-center justify-between">
                <span class="text-gray-500">Employees</span>
                <span class="font-semibold">{{ $totalEmployees }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-500">Equipment</span>
                <span class="font-semibold">{{ $totalEquipment }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-500">Open Incidents</span>
                <span class="font-semibold text-{{ $openIncidents > 0 ? 'danger' : 'success' }}">{{ $openIncidents }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-500">Training Records</span>
                <span class="font-semibold">{{ $totalTraining }}</span>
            </div>
            @if($expiredCert > 0)
            <div class="mt-1 rounded bg-danger/10 px-2 py-1 text-xs text-danger">
                {{ $expiredCert }} expired certification{{ $expiredCert > 1 ? 's' : '' }}
            </div>
            @endif
        </div>
    </div>

    {{-- Finance Summary --}}
    <div class="panel">
        <h5 class="mb-4 text-sm font-bold uppercase tracking-wider text-success">Finance</h5>
        <div class="space-y-3 text-sm">
            <div class="flex items-center justify-between">
                <span class="text-gray-500">Invoices</span>
                <span class="font-semibold">{{ $totalInvoices }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-500">Unpaid</span>
                <span class="font-semibold text-{{ $unpaidInvoices > 0 ? 'danger' : 'success' }}">{{ $unpaidInvoices }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-500">Bills (AP)</span>
                <span class="font-semibold">{{ $totalBills }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-500">Pending Approvals</span>
                <span class="font-semibold text-{{ $pendingApprovals > 0 ? 'warning' : 'success' }}">{{ $pendingApprovals }}</span>
            </div>
        </div>
    </div>

</div>

{{-- ===== ROW 3: RECENT RECORDS TABLES ===== --}}
<div class="mb-6 grid gap-4 lg:grid-cols-3">

    {{-- Recent Projects --}}
    <div class="panel">
        <div class="mb-4 flex items-center justify-between">
            <h5 class="text-sm font-bold uppercase tracking-wider dark:text-white">Recent Projects</h5>
            <a href="{{ route('admin.core.projects.index') }}" class="text-xs text-primary hover:underline">View All &rarr;</a>
        </div>
        <div class="table-responsive">
            <table class="table-striped table">
                <thead><tr><th class="text-xs">Name</th><th class="text-xs">Budget</th><th class="text-xs">Status</th></tr></thead>
                <tbody>
                    @forelse($recentProjects as $project)
                    <tr>
                        <td>
                            <div class="text-xs font-semibold dark:text-white">{{ Str::limit($project->name, 25) }}</div>
                            <div class="text-xs text-gray-400">by {{ $project->creator->name ?? 'N/A' }}</div>
                        </td>
                        <td class="text-xs font-semibold">BDT{{ number_format($project->budget/1000000, 1) }}M</td>
                        <td>
                            @php $c = ['active'=>'success','planning'=>'warning','completed'=>'primary','on_hold'=>'danger']; @endphp
                            <span class="badge badge-outline-{{ $c[$project->status] ?? 'secondary' }} text-xs">{{ ucfirst(str_replace('_',' ',$project->status)) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center text-xs text-gray-400">No projects yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent POs --}}
    <div class="panel">
        <div class="mb-4 flex items-center justify-between">
            <h5 class="text-sm font-bold uppercase tracking-wider dark:text-white">Recent Purchase Orders</h5>
            <a href="{{ route('admin.procurement.purchase-orders.index') }}" class="text-xs text-primary hover:underline">View All &rarr;</a>
        </div>
        <div class="table-responsive">
            <table class="table-striped table">
                <thead><tr><th class="text-xs">PO#</th><th class="text-xs">Vendor</th><th class="text-xs">Amount</th><th class="text-xs">Status</th></tr></thead>
                <tbody>
                    @forelse($recentPOs as $po)
                    <tr>
                        <td class="text-xs font-semibold font-mono text-primary">{{ $po->po_number }}</td>
                        <td class="text-xs">{{ Str::limit($po->vendor->name ?? 'N/A', 18) }}</td>
                        <td class="text-xs font-semibold">BDT{{ number_format($po->total_amount) }}</td>
                        <td>
                            @php $c = ['draft'=>'secondary','ordered'=>'info','partially_received'=>'warning','received'=>'success','cancelled'=>'danger']; @endphp
                            <span class="badge badge-outline-{{ $c[$po->status] ?? 'secondary' }} text-xs">{{ ucfirst(str_replace('_',' ',$po->status)) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-xs text-gray-400">No POs yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Incidents --}}
    <div class="panel">
        <div class="mb-4 flex items-center justify-between">
            <h5 class="text-sm font-bold uppercase tracking-wider dark:text-white">Recent Incidents</h5>
            <a href="{{ route('admin.hr.incident-reports.index') }}" class="text-xs text-primary hover:underline">View All &rarr;</a>
        </div>
        <div class="table-responsive">
            <table class="table-striped table">
                <thead><tr><th class="text-xs">Type</th><th class="text-xs">Severity</th><th class="text-xs">Status</th><th class="text-xs">Date</th></tr></thead>
                <tbody>
                    @forelse($recentIncidents as $inc)
                    <tr>
                        <td class="text-xs font-semibold">{{ ucfirst(str_replace('_',' ',$inc->type)) }}</td>
                        <td>
                            @php
                                $sc = ['minor'=>'success','moderate'=>'warning','serious'=>'warning','critical'=>'danger','fatal'=>'danger'];
                            @endphp
                            <span class="badge badge-outline-{{ $sc[$inc->severity] ?? 'secondary' }} text-xs">{{ ucfirst($inc->severity) }}</span>
                        </td>
                        <td>
                            @php $stc = ['open'=>'danger','under_investigation'=>'warning','closed'=>'success']; @endphp
                            <span class="badge badge-outline-{{ $stc[$inc->status] ?? 'secondary' }} text-xs">{{ ucfirst(str_replace('_',' ',$inc->status)) }}</span>
                        </td>
                        <td class="text-xs text-gray-500">{{ $inc->date?->format('d M') ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-xs text-gray-400">No incidents recorded</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ===== ROW 4: ALERTS ROW ===== --}}
<div class="grid gap-4 lg:grid-cols-2">

    {{-- Low Stock Alerts --}}
    <div class="{{ $lowStockItems->count() > 0 ? 'panel border-l-4 border-danger' : 'panel' }}">
        <div class="mb-4 flex items-center gap-2">
            <svg class="h-5 w-5 text-danger" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <h5 class="text-sm font-bold uppercase tracking-wider dark:text-white">Low Stock Items</h5>
            @if($lowStockItems->count() > 0)
                <span class="badge-status bg-danger/10 text-danger">{{ $lowStockItems->count() }} item{{ $lowStockItems->count() > 1 ? 's' : '' }}</span>
            @endif
        </div>
        @if($lowStockItems->count() > 0)
        <div class="grid gap-2 sm:grid-cols-3">
            @foreach($lowStockItems as $stock)
            <div class="rounded-lg border border-danger/20 bg-danger/5 p-2 text-center">
                <p class="text-xs font-bold dark:text-white">{{ $stock->material->name ?? 'Unknown' }}</p>
                <p class="text-xs text-gray-500">{{ $stock->warehouse->name ?? $stock->site->name ?? '' }}</p>
                <p class="text-lg font-black text-danger">{{ number_format($stock->quantity, 1) }}</p>
                <p class="text-xs text-gray-400">{{ $stock->material->unit ?? 'units' }}</p>
            </div>
            @endforeach
        </div>
        @else
        <div class="flex items-center gap-2 rounded-lg bg-success/10 px-3 py-2 text-xs text-success">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            All stock levels are healthy
        </div>
        @endif
    </div>

    {{-- Quick Stats Grid --}}
    <div class="panel">
        <h5 class="mb-4 text-sm font-bold uppercase tracking-wider dark:text-white">Module Quick Stats</h5>
        <div class="grid grid-cols-2 gap-3">
            <div class="rounded-lg bg-primary/5 p-3 text-center">
                <p class="text-lg font-bold text-primary">{{ $totalTasks }}</p>
                <p class="text-xs text-gray-500">Total Tasks</p>
            </div>
            <div class="rounded-lg bg-success/5 p-3 text-center">
                <p class="text-lg font-bold text-success">BDT{{ number_format($totalInvoiceAmount/1000, 0) }}K</p>
                <p class="text-xs text-gray-500">Invoiced Amount</p>
            </div>
            <div class="rounded-lg bg-warning/5 p-3 text-center">
                <p class="text-lg font-bold text-warning">{{ $todayAttendance }}</p>
                <p class="text-xs text-gray-500">Present Today</p>
            </div>
            <div class="rounded-lg bg-info/5 p-3 text-center">
                <p class="text-lg font-bold text-info">{{ $totalEquipment }}</p>
                <p class="text-xs text-gray-500">Equipment</p>
            </div>
        </div>
    </div>

</div>

{{-- ===== ROW 5: CHARTS ===== --}}
<div class="mt-6 grid gap-4 lg:grid-cols-2">

    <div class="panel">
        <div class="mb-4 flex items-center justify-between">
            <h5 class="text-sm font-bold uppercase tracking-wider dark:text-white">Project Status</h5>
            <span class="badge-status bg-primary/10 text-primary">{{ $totalProjects }} Total</span>
        </div>
        <div id="projectStatusChart" class="min-h-[220px]"></div>
    </div>

    <div class="panel">
        <div class="mb-4 flex items-center justify-between">
            <h5 class="text-sm font-bold uppercase tracking-wider dark:text-white">Tasks by Priority</h5>
            <span class="badge-status bg-warning/10 text-warning">{{ $totalTasks }} Tasks</span>
        </div>
        <div id="taskPriorityChart" class="min-h-[220px]"></div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.documentElement.classList.contains('dark') || localStorage.getItem('theme') === 'dark';
    const textColor = isDark ? '#888ea8' : '#515365';
    const gridColor = isDark ? '#1b2e4b' : '#e0e6ed';

    // Project Status Donut
    const projectChart = new ApexCharts(document.querySelector("#projectStatusChart"), {
        series: [{{ $projectsByStatus['active'] ?? 0 }}, {{ $projectsByStatus['planning'] ?? 0 }}, {{ $projectsByStatus['completed'] ?? 0 }}, {{ $projectsByStatus['on_hold'] ?? 0 }}],
        labels: ['Active', 'Planning', 'Completed', 'On Hold'],
        chart: { type: 'donut', height: 220, toolbar: { show: false }, background: 'transparent' },
        colors: ['#4361ee', '#e2a03f', '#00ab55', '#e7515a'],
        legend: { position: 'bottom', fontSize: '11px', labels: { colors: textColor } },
        dataLabels: { enabled: true, formatter: (val) => Math.round(val) + '%', style: { fontSize: '10px' } },
        stroke: { width: 2 },
        plotOptions: { pie: { donut: { size: '60%', labels: { show: true, total: { show: true, label: 'Total', color: textColor, fontSize: '11px', formatter: () => {{ $totalProjects }} } } } } },
        theme: { mode: isDark ? 'dark' : 'light' }
    });
    projectChart.render();

    // Task Priority Bar
    const taskChart = new ApexCharts(document.querySelector("#taskPriorityChart"), {
        series: [{ name: 'Tasks', data: [{{ $tasksByPriority['critical'] ?? 0 }}, {{ $tasksByPriority['high'] ?? 0 }}, {{ $tasksByPriority['medium'] ?? 0 }}, {{ $tasksByPriority['low'] ?? 0 }}] }],
        chart: { type: 'bar', height: 220, toolbar: { show: false }, background: 'transparent' },
        colors: ['#e7515a', '#e2a03f', '#4361ee', '#00ab55'],
        plotOptions: { bar: { distributed: true, horizontal: true, barHeight: '55%', borderRadius: 4 } },
        xaxis: { categories: ['Critical', 'High', 'Medium', 'Low'], labels: { style: { colors: textColor, fontSize: '11px' } } },
        yaxis: { labels: { style: { colors: textColor, fontSize: '11px' } } },
        grid: { borderColor: gridColor },
        legend: { show: false },
        dataLabels: { enabled: true, style: { fontSize: '11px' } },
        theme: { mode: isDark ? 'dark' : 'light' }
    });
    taskChart.render();
});
</script>
@endpush
