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

    @if(auth()->user()?->hasRole('client'))
        {{-- Client Portal Dashboard --}}
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold dark:text-white">Client Portal Dashboard</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Welcome back, {{ auth()->user()?->name }} &mdash; {{ now()->format('l, d M Y') }}</p>
            </div>
        </div>

        <div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="panel stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-3xl font-bold text-primary">{{ $totalProjects }}</p>
                        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">My Projects</p>
                        <p class="mt-2 text-xs text-gray-400">{{ $activeProjects }} active</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                </div>
            </div>

            <div class="panel stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-3xl font-bold text-success">৳{{ number_format($totalBudget/10000000, 2) }}Cr</p>
                        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Project Budget</p>
                        <p class="mt-2 text-xs text-gray-400">{{ $totalSites }} site(s)</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-success/10 text-success">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            <div class="panel stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-3xl font-bold text-warning">{{ $unpaidInvoices }}</p>
                        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Open Invoices</p>
                        <p class="mt-2 text-xs text-gray-400">{{ $totalInvoices }} total</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-warning/10 text-warning">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M7 3h10a2 2 0 012 2v14l-3-2-3 2-3-2-3 2V5a2 2 0 012-2z"/></svg>
                    </div>
                </div>
            </div>

            <div class="panel stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-3xl font-bold text-danger font-mono">৳{{ number_format($totalARDue, 2) }}</p>
                        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Outstanding Amount</p>
                        <p class="mt-2 text-xs text-gray-400">Across your invoices</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-danger/10 text-danger">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 8v4l2 2m-2-8a8 8 0 100 16 8 8 0 000-16z"/></svg>
                    </div>
                </div>
            </div>
        </div>
    @endif

@if(auth()->user()?->hasRole('admin') || auth()->user()?->hasRole('super-admin') || auth()->user()?->hasRole('accountant'))

{{-- Page Header --}}
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold dark:text-white">Executive Construction ERP Dashboard</h1>
        <p class="mt-0.5 text-xs text-white-dark">Real-time overview &amp; operational metrics &mdash; {{ now()->format('l, d M Y') }}</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('tyro-dashboard.index') }}" class="btn btn-sm btn-outline-primary gap-1">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Refresh Data
        </a>
    </div>
</div>

{{-- ===== QUICK ACTION LAUNCHPAD WIDGET ===== --}}
<div class="panel mb-6 border-l-4 border-primary">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <svg class="h-5 w-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path></svg>
            <span class="text-xs font-bold uppercase tracking-wider text-primary">Quick Launchpad</span>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.core.projects.index') }}" class="btn btn-xs btn-outline-primary">+ New Project</a>
            <a href="{{ route('admin.finance.invoices.index') }}" class="btn btn-xs btn-outline-success">+ Create Invoice</a>
            <a href="{{ route('admin.finance.bills.index') }}" class="btn btn-xs btn-outline-warning">+ Record Bill</a>
            <a href="{{ route('admin.procurement.purchase-orders.index') }}" class="btn btn-xs btn-outline-info">+ Issue PO</a>
            <a href="{{ route('admin.hr.attendance.index') }}" class="btn btn-xs btn-outline-secondary">Daily Register</a>
            <a href="{{ route('admin.hr.incident-reports.index') }}" class="btn btn-xs btn-outline-danger">Safety Report</a>
        </div>
    </div>
</div>

{{-- ===== ROW 1: EXECUTIVE KPI STAT CARDS ===== --}}
<div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">

    {{-- 1. Active Projects --}}
    <div class="panel stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-3xl font-extrabold text-primary">{{ $activeProjects }}</p>
                <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Active Projects</p>
                <p class="mt-2 text-xs text-gray-400">{{ $totalProjects }} total projects</p>
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
                <p class="text-2xl font-extrabold text-success">৳{{ number_format($totalBudget/10000000, 2) }}Cr</p>
                <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Total Portfolio Budget</p>
                <p class="mt-2 text-xs text-gray-400">{{ $totalSites }} active sites</p>
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
                <p class="text-3xl font-extrabold text-warning">{{ $inProgressTasks }}</p>
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
                <p class="text-3xl font-extrabold text-info">{{ $activeEmployees }}</p>
                <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Active Workforce</p>
                <p class="mt-2 text-xs text-gray-400">{{ $totalEmployees }} total &middot; {{ $todayAttendance }} present</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-info/10 text-info">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>
    </div>

    {{-- 5. Vendors --}}
    <div class="panel stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-3xl font-extrabold text-primary">{{ $approvedVendors }}</p>
                <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Approved Suppliers</p>
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
                <p class="text-3xl font-extrabold text-danger">{{ $pendingApprovals + $totalLeavePending + $activeMaintenance }}</p>
                <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Pending Actions</p>
                <p class="mt-2 text-xs text-gray-400">{{ $pendingApprovals }} approvals &middot; {{ $totalLeavePending }} leaves</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-danger/10 text-danger">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

</div>

{{-- ===== EXECUTIVE CASH FLOW & WORKING CAPITAL BANNER ===== --}}
@php
    $netWorkingCapital = $totalARDue - $totalAPDue;
@endphp
<div class="panel mb-6 bg-gradient-to-r from-gray-50 via-white to-gray-50 dark:from-gray-800 dark:via-gray-800/80 dark:to-gray-800 border dark:border-gray-700">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-white-dark block">Working Capital &amp; Liquidity Overview</span>
            <h4 class="text-lg font-extrabold text-primary mt-0.5">Accounts Receivable (AR) vs. Accounts Payable (AP)</h4>
        </div>
        <div class="flex flex-wrap items-center gap-6 text-xs">
            <div class="text-right">
                <span class="text-white-dark block">Total Receivables (AR):</span>
                <a href="{{ route('admin.finance.aging.ar') }}" class="font-mono font-bold text-success text-sm hover:underline">
                    ৳{{ number_format($totalARDue, 2) }}
                </a>
            </div>
            <div class="text-right">
                <span class="text-white-dark block">Total Payables (AP):</span>
                <a href="{{ route('admin.finance.aging.ap') }}" class="font-mono font-bold text-danger text-sm hover:underline">
                    ৳{{ number_format($totalAPDue, 2) }}
                </a>
            </div>
            <div class="text-right border-l pl-4 dark:border-gray-700">
                <span class="text-white-dark block">Net Difference:</span>
                <span class="font-mono font-extrabold text-sm {{ $netWorkingCapital >= 0 ? 'text-success' : 'text-danger' }}">
                    ৳{{ number_format($netWorkingCapital, 2) }}
                </span>
            </div>
            <div>
                <span class="badge {{ $netWorkingCapital >= 0 ? 'badge-outline-success' : 'badge-outline-danger' }} uppercase px-3 py-1 font-bold">
                    {{ $netWorkingCapital >= 0 ? 'Positive Capital' : 'Payables Surge' }}
                </span>
            </div>
        </div>
    </div>
</div>

{{-- ===== ROW 2: DOMAIN SUMMARY CARDS ===== --}}
<div class="mb-6 grid gap-4 lg:grid-cols-4">

    {{-- Core Summary --}}
    <div class="panel">
        <h5 class="mb-4 text-sm font-bold uppercase tracking-wider text-primary">Core Operations</h5>
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
                    <span>Active Projects Ratio</span><span>{{ $activeProjects }}/{{ $totalProjects }}</span>
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
                <span class="text-gray-500">Suppliers &amp; Vendors</span>
                <span class="font-semibold">{{ $totalVendors }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-500">Purchase Orders</span>
                <span class="font-semibold">{{ $totalPOs }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-500">Total PO Commitment</span>
                <span class="font-mono font-bold text-primary">৳{{ number_format($totalPOValue, 2) }}</span>
            </div>
            <a href="{{ route('admin.procurement.purchase-orders.index') }}" class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-primary hover:underline">
                View Purchase Orders <span>&rarr;</span>
            </a>
        </div>
    </div>

    {{-- HR Summary --}}
    <div class="panel">
        <h5 class="mb-4 text-sm font-bold uppercase tracking-wider text-info">Human Resources &amp; Safety</h5>
        <div class="space-y-3 text-sm">
            <div class="flex items-center justify-between">
                <span class="text-gray-500">Active Personnel</span>
                <span class="font-semibold">{{ $totalEmployees }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-500">Equipment Fleet</span>
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
            <div class="mt-1 rounded bg-danger/10 px-2 py-1 text-xs text-danger font-semibold">
                {{ $expiredCert }} expired certification{{ $expiredCert > 1 ? 's' : '' }}
            </div>
            @endif
        </div>
    </div>

    {{-- Finance Summary --}}
    <div class="panel">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-sm font-bold uppercase tracking-wider text-success">Finance &amp; Accounts</h5>
            <a href="{{ route('admin.finance.aging.ar') }}" class="text-xs text-primary hover:underline font-semibold">Aging Reports &rarr;</a>
        </div>
        <div class="space-y-3 text-sm">
            <div class="flex items-center justify-between">
                <span class="text-gray-500">Receivables (AR)</span>
                <a href="{{ route('admin.finance.aging.ar') }}" class="font-mono font-bold text-success hover:underline">৳{{ number_format($totalARDue, 2) }}</a>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-500">Payables (AP)</span>
                <a href="{{ route('admin.finance.aging.ap') }}" class="font-mono font-bold text-danger hover:underline">৳{{ number_format($totalAPDue, 2) }}</a>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-500">Invoices Billed</span>
                <span class="font-semibold">{{ $totalInvoices }} ({{ $unpaidInvoices }} open)</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-500">Vendor Bills</span>
                <span class="font-semibold">{{ $totalBills }} ({{ $unpaidBills }} open)</span>
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
        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto text-xs">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800">
                        <th class="py-2.5 px-3 text-left">Project Name</th>
                        <th class="py-2.5 px-3 text-right">Budget</th>
                        <th class="py-2.5 px-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentProjects as $project)
                    <tr class="border-t dark:border-gray-800">
                        <td class="py-2.5 px-3">
                            <div class="font-semibold text-primary">{{ Str::limit($project->name, 22) }}</div>
                            <div class="text-[11px] text-white-dark">by {{ $project->creator->name ?? 'N/A' }}</div>
                        </td>
                        <td class="py-2.5 px-3 text-right font-mono font-bold">৳{{ number_format($project->budget/10000000, 2) }}Cr</td>
                        <td class="py-2.5 px-3 text-center">
                            @php $c = ['active'=>'success','planning'=>'warning','completed'=>'primary','on_hold'=>'danger']; @endphp
                            <span class="badge badge-outline-{{ $c[$project->status] ?? 'secondary' }} text-xs capitalize">{{ str_replace('_',' ',$project->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center py-4 text-gray-400">No recent projects</td></tr>
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
        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto text-xs">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800">
                        <th class="py-2.5 px-3 text-left">PO #</th>
                        <th class="py-2.5 px-3 text-left">Vendor</th>
                        <th class="py-2.5 px-3 text-right">Amount (৳)</th>
                        <th class="py-2.5 px-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPOs as $po)
                    <tr class="border-t dark:border-gray-800">
                        <td class="py-2.5 px-3 font-mono font-bold text-primary">{{ $po->po_number }}</td>
                        <td class="py-2.5 px-3 font-semibold">{{ Str::limit($po->vendor->name ?? 'N/A', 16) }}</td>
                        <td class="py-2.5 px-3 text-right font-mono font-bold">৳{{ number_format($po->total_amount, 2) }}</td>
                        <td class="py-2.5 px-3 text-center">
                            @php $c = ['draft'=>'secondary','ordered'=>'info','partially_received'=>'warning','received'=>'success','cancelled'=>'danger']; @endphp
                            <span class="badge badge-outline-{{ $c[$po->status] ?? 'secondary' }} text-xs capitalize">{{ str_replace('_',' ',$po->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-4 text-gray-400">No purchase orders found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Incidents --}}
    <div class="panel">
        <div class="mb-4 flex items-center justify-between">
            <h5 class="text-sm font-bold uppercase tracking-wider dark:text-white">Recent HSE Incidents</h5>
            <a href="{{ route('admin.hr.incident-reports.index') }}" class="text-xs text-primary hover:underline">View All &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto text-xs">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800">
                        <th class="py-2.5 px-3 text-left">Type</th>
                        <th class="py-2.5 px-3 text-center">Severity</th>
                        <th class="py-2.5 px-3 text-center">Status</th>
                        <th class="py-2.5 px-3 text-right">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentIncidents as $inc)
                    <tr class="border-t dark:border-gray-800">
                        <td class="py-2.5 px-3 font-semibold">{{ ucfirst(str_replace('_',' ',$inc->incident_type)) }}</td>
                        <td class="py-2.5 px-3 text-center">
                            @php
                                $sc = ['minor'=>'success','moderate'=>'warning','serious'=>'warning','critical'=>'danger','fatal'=>'danger'];
                            @endphp
                            <span class="badge badge-outline-{{ $sc[$inc->severity] ?? 'secondary' }} text-xs capitalize">{{ $inc->severity }}</span>
                        </td>
                        <td class="py-2.5 px-3 text-center">
                            @php $stc = ['open'=>'danger','under_investigation'=>'warning','closed'=>'success']; @endphp
                            <span class="badge badge-outline-{{ $stc[$inc->status] ?? 'secondary' }} text-xs capitalize">{{ str_replace('_',' ',$inc->status) }}</span>
                        </td>
                        <td class="py-2.5 px-3 text-right text-white-dark">{{ $inc->incident_date?->format('d M Y') ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-4 text-gray-400">No incidents recorded</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ===== ROW 4: ALERTS & STATS ===== --}}
<div class="grid gap-4 lg:grid-cols-2 mb-6">

    {{-- Low Stock Alerts --}}
    <div class="{{ $lowStockItems->count() > 0 ? 'panel border-l-4 border-danger' : 'panel' }}">
        <div class="mb-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-danger" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <h5 class="text-sm font-bold uppercase tracking-wider dark:text-white">Material Low Stock Alerts</h5>
            </div>
            @if($lowStockItems->count() > 0)
                <span class="badge badge-outline-danger text-xs font-bold">{{ $lowStockItems->count() }} Items</span>
            @endif
        </div>
        @if($lowStockItems->count() > 0)
        <div class="grid gap-3 sm:grid-cols-3">
            @foreach($lowStockItems as $stock)
            <div class="rounded-lg border border-danger/20 bg-danger/5 p-3 text-center">
                <p class="text-xs font-bold dark:text-white">{{ $stock->material->name ?? 'Unknown' }}</p>
                <p class="text-[11px] text-white-dark mt-0.5">{{ $stock->warehouse->name ?? $stock->site->name ?? '' }}</p>
                <p class="text-xl font-extrabold text-danger mt-1 font-mono">{{ number_format($stock->quantity, 1) }}</p>
                <p class="text-[11px] text-gray-400">{{ $stock->material->unit ?? 'units' }}</p>
            </div>
            @endforeach
        </div>
        @else
        <div class="flex items-center gap-2 rounded-lg bg-success/10 px-4 py-3 text-xs text-success font-semibold">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            All warehouse &amp; site stock levels are healthy.
        </div>
        @endif
    </div>

    {{-- Quick Module Statistics --}}
    <div class="panel">
        <h5 class="mb-4 text-sm font-bold uppercase tracking-wider dark:text-white">ERP Operations Summary</h5>
        <div class="grid grid-cols-2 gap-3">
            <div class="rounded-lg bg-primary/5 p-3 text-center border border-primary/10">
                <p class="text-xl font-extrabold text-primary font-mono">{{ $totalTasks }}</p>
                <p class="text-xs text-white-dark font-medium">Scheduled Tasks</p>
            </div>
            <div class="rounded-lg bg-success/5 p-3 text-center border border-success/10">
                <p class="text-xl font-extrabold text-success font-mono">৳{{ number_format($totalInvoiceAmount/1000000, 1) }}M</p>
                <p class="text-xs text-white-dark font-medium">Total Billed Revenue</p>
            </div>
            <div class="rounded-lg bg-warning/5 p-3 text-center border border-warning/10">
                <p class="text-xl font-extrabold text-warning font-mono">{{ $todayAttendance }}</p>
                <p class="text-xs text-white-dark font-medium">Staff Present Today</p>
            </div>
            <div class="rounded-lg bg-info/5 p-3 text-center border border-info/10">
                <p class="text-xl font-extrabold text-info font-mono">{{ $totalEquipment }}</p>
                <p class="text-xs text-white-dark font-medium">Active Equipment Fleet</p>
            </div>
        </div>
    </div>

</div>

{{-- ===== ROW 5: VISUAL CHARTS GRID (3 CHARTS) ===== --}}
<div class="mt-6 grid gap-6 lg:grid-cols-3">

    {{-- Chart 1: Revenue vs Expense Monthly Trend --}}
    <div class="panel lg:col-span-2">
        <div class="mb-4 flex items-center justify-between">
            <h5 class="text-sm font-bold uppercase tracking-wider text-primary">6-Month Financial Trend (Invoices vs Bills)</h5>
            <span class="badge badge-outline-primary text-xs">Revenue &amp; Expense Flow</span>
        </div>
        <div id="financialTrendChart" class="w-full h-64"></div>
    </div>

    {{-- Chart 2: Project Status Donut --}}
    <div class="panel">
        <div class="mb-4 flex items-center justify-between">
            <h5 class="text-sm font-bold uppercase tracking-wider text-primary">Project Status Breakdown</h5>
            <span class="badge-status bg-primary/10 text-primary">{{ $totalProjects }} Total</span>
        </div>
        <div id="projectStatusChart" class="w-full h-64 flex items-center justify-center"></div>
    </div>

</div>

@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.documentElement.classList.contains('dark') || localStorage.getItem('theme') === 'dark';
    const textColor = isDark ? '#888ea8' : '#515365';
    const gridColor = isDark ? '#1b2e4b' : '#e0e6ed';

    // 1. Revenue vs Expense Monthly Trend Chart
    const months = [ @foreach($monthlyRevenue as $m => $v) "{{ $m }}", @endforeach ];
    const revData = [ @foreach($monthlyRevenue as $v) {{ $v }}, @endforeach ];
    const expData = [ @foreach($monthlyExpenses as $v) {{ $v }}, @endforeach ];

    if (document.querySelector("#financialTrendChart")) {
        new ApexCharts(document.querySelector("#financialTrendChart"), {
            series: [{
                name: 'Invoices Billed (৳)',
                data: revData
            }, {
                name: 'Vendor Bills (৳)',
                data: expData
            }],
            chart: { type: 'area', height: 260, toolbar: { show: false }, background: 'transparent' },
            colors: ['#00ab55', '#e7515a'],
            stroke: { curve: 'smooth', width: 2 },
            fill: { type: 'gradient', gradient: { opacityFrom: 0.3, opacityTo: 0.05 } },
            xaxis: { categories: months, labels: { style: { colors: textColor, fontSize: '11px' } } },
            yaxis: { labels: { style: { colors: textColor, fontSize: '11px' }, formatter: v => "৳" + (v/1000).toFixed(0) + "K" } },
            grid: { borderColor: gridColor },
            legend: { position: 'top', horizontalAlign: 'right', labels: { colors: textColor } },
            tooltip: { y: { formatter: v => "৳" + v.toLocaleString('en-US', {minimumFractionDigits: 2}) } },
            theme: { mode: isDark ? 'dark' : 'light' }
        }).render();
    }

    // 2. Project Status Donut
    if (document.querySelector("#projectStatusChart")) {
        new ApexCharts(document.querySelector("#projectStatusChart"), {
            series: [{{ $projectsByStatus['active'] ?? 0 }}, {{ $projectsByStatus['planning'] ?? 0 }}, {{ $projectsByStatus['completed'] ?? 0 }}, {{ $projectsByStatus['on_hold'] ?? 0 }}],
            labels: ['Active', 'Planning', 'Completed', 'On Hold'],
            chart: { type: 'donut', height: 250, toolbar: { show: false }, background: 'transparent' },
            colors: ['#4361ee', '#e2a03f', '#00ab55', '#e7515a'],
            legend: { position: 'bottom', fontSize: '11px', labels: { colors: textColor } },
            dataLabels: { enabled: true, formatter: (val) => Math.round(val) + '%', style: { fontSize: '10px' } },
            stroke: { width: 2 },
            plotOptions: { pie: { donut: { size: '60%', labels: { show: true, total: { show: true, label: 'Projects', color: textColor, fontSize: '11px', formatter: () => {{ $totalProjects }} } } } } },
            theme: { mode: isDark ? 'dark' : 'light' }
        }).render();
    }
});
</script>
@endpush
