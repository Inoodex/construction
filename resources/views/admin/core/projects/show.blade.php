@extends('admin.layouts.master')

@section('title', 'Project Dashboard - ' . $project->name)

@section('content')
    {{-- Header Bar --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase flex items-center gap-2">
                <span>{{ $project->name }}</span>
                @php $colors = ['planning' => 'badge-outline-warning', 'active' => 'badge-outline-success', 'on_hold' => 'badge-outline-danger', 'completed' => 'badge-outline-primary']; @endphp
                <span class="badge {{ $colors[$project->status] ?? 'badge-outline-secondary' }} capitalize text-xs px-2.5 py-1">{{ str_replace('_', ' ', $project->status) }}</span>
            </h2>
            <p class="text-xs text-white-dark mt-1">Client: <span class="font-semibold text-primary">{{ $project->client->company_name ?? $project->client->name ?? 'Internal Project' }}</span> | Duration: {{ $project->start_date?->format('d M Y') ?? 'N/A' }} — {{ $project->end_date?->format('d M Y') ?? 'N/A' }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.core.projects.gantt', ['project' => $project->id]) }}" class="btn btn-info gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><rect x="3" y="3" width="18" height="18" rx="2"></rect><line x1="9" y1="3" x2="9" y2="21"></line><line x1="7" y1="8" x2="13" y2="8"></line><line x1="7" y1="12" x2="17" y2="12"></line><line x1="7" y1="16" x2="11" y2="16"></line></svg>
                Gantt Chart
            </a>
            <a href="{{ route('admin.core.projects.edit', ['project' => $project->id]) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit Project
            </a>
            <a href="{{ route('admin.core.projects.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to List
            </a>
        </div>
    </div>

    {{-- Top 4 Financial KPI Summary Cards --}}
    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Card 1: Allocated Budget --}}
        <div class="panel border-l-4 border-primary bg-gray-50/50 dark:bg-gray-800/40">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-bold text-white-dark">Allocated Budget</span>
                <span class="rounded-full bg-primary/10 p-2 text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"></path></svg>
                </span>
            </div>
            <p class="mt-2 text-2xl font-bold font-mono text-primary">৳{{ number_format($project->budget, 2) }}</p>
            <div class="mt-2 flex items-center justify-between text-xs text-white-dark">
                <span>Progress: {{ $project->progress }}%</span>
                <span>Duration: {{ ($project->start_date && $project->end_date) ? $project->start_date->diffInDays($project->end_date) : 0 }}d</span>
            </div>
        </div>

        {{-- Card 2: Total Revenue / Invoiced --}}
        <div class="panel border-l-4 border-success bg-gray-50/50 dark:bg-gray-800/40">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-bold text-white-dark">Total Revenue (Invoiced)</span>
                <span class="rounded-full bg-success/10 p-2 text-success">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                </span>
            </div>
            <p class="mt-2 text-2xl font-bold font-mono text-success">৳{{ number_format($totalInvoiced, 2) }}</p>
            <div class="mt-2 flex items-center justify-between text-xs">
                <span class="text-success font-semibold">Collected: ৳{{ number_format($totalIncomeCollected, 2) }}</span>
                <span class="text-danger">Due: ৳{{ number_format($pendingReceivables, 2) }}</span>
            </div>
        </div>

        {{-- Card 3: Total Expenses & Costs --}}
        <div class="panel border-l-4 border-danger bg-gray-50/50 dark:bg-gray-800/40">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-bold text-white-dark">Total Expenses & Costs</span>
                <span class="rounded-full bg-danger/10 p-2 text-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg>
                </span>
            </div>
            <p class="mt-2 text-2xl font-bold font-mono text-danger">৳{{ number_format($totalExpenses, 2) }}</p>
            <div class="mt-2 flex items-center justify-between text-xs">
                <span class="text-white-dark">Paid Out: ৳{{ number_format($actualCashOut, 2) }}</span>
                <span class="text-warning">Unpaid Bills: ৳{{ number_format($dueBills, 2) }}</span>
            </div>
        </div>

        {{-- Card 4: Net Surplus / Margin --}}
        <div class="panel border-l-4 {{ $netProfit >= 0 ? 'border-info' : 'border-danger' }} bg-gray-50/50 dark:bg-gray-800/40">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-bold text-white-dark">Net Surplus / Margin</span>
                <span class="rounded-full {{ $netProfit >= 0 ? 'bg-info/10 text-info' : 'bg-danger/10 text-danger' }} p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 8v8M8 12h8"></path></svg>
                </span>
            </div>
            <p class="mt-2 text-2xl font-bold font-mono {{ $netProfit >= 0 ? 'text-info' : 'text-danger' }}">৳{{ number_format($netProfit, 2) }}</p>
            <div class="mt-2 flex items-center justify-between text-xs">
                <span class="font-bold {{ $netProfit >= 0 ? 'text-info' : 'text-danger' }}">{{ $profitMargin }}% Financial Margin</span>
                <span class="text-white-dark">PO Commit: ৳{{ number_format($poTotal, 0) }}</span>
            </div>
        </div>
    </div>

    {{-- Interactive Single-Page Tabs Component --}}
    <div x-data="{ activeTab: 'overview' }" class="mt-6">
        {{-- Navigation Bar --}}
        <div class="flex overflow-x-auto border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-t-lg p-1 gap-1">
            <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'bg-primary text-white font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'" class="flex items-center gap-2 rounded-md px-4 py-2.5 text-xs transition-all whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /></svg>
                Overview & Progress
            </button>
            <button @click="activeTab = 'invoices'" :class="activeTab === 'invoices' ? 'bg-primary text-white font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'" class="flex items-center gap-2 rounded-md px-4 py-2.5 text-xs transition-all whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Invoices & Revenue ({{ $invoices->count() }})
            </button>
            <button @click="activeTab = 'bills'" :class="activeTab === 'bills' ? 'bg-primary text-white font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'" class="flex items-center gap-2 rounded-md px-4 py-2.5 text-xs transition-all whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" /></svg>
                Vendor Bills & Expenses ({{ $bills->count() + $expenses->count() }})
            </button>
            <button @click="activeTab = 'procurement'" :class="activeTab === 'procurement' ? 'bg-primary text-white font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'" class="flex items-center gap-2 rounded-md px-4 py-2.5 text-xs transition-all whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                Procurement & BBS Sheets ({{ $purchaseOrders->count() + $rodCalculations->count() }})
            </button>
            <button @click="activeTab = 'resources'" :class="activeTab === 'resources' ? 'bg-primary text-white font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'" class="flex items-center gap-2 rounded-md px-4 py-2.5 text-xs transition-all whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                Resources & Equipment ({{ $project->resources->count() }})
            </button>
        </div>

        {{-- Tab 1: Overview & Progress --}}
        <div x-show="activeTab === 'overview'" class="panel rounded-t-none border-t-0">
            <div class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-6">
                    <div>
                        <h5 class="mb-3 text-base font-semibold">General Information & Description</h5>
                        <p class="text-xs text-white-dark leading-relaxed">{{ $project->description ?: 'No additional description provided for this project.' }}</p>
                    </div>

                    {{-- Progress Bar Card --}}
                    <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800/40 border dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase text-white-dark">Overall Project Completion</span>
                            <span class="text-sm font-bold text-primary">{{ $project->progress }}% Completed</span>
                        </div>
                        <div class="mt-2.5 h-3 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                            @php $progColor = $project->progress >= 100 ? 'bg-success' : ($project->progress >= 50 ? 'bg-primary' : ($project->progress >= 25 ? 'bg-warning' : 'bg-danger')); @endphp
                            <div class="h-full rounded-full {{ $progColor }} transition-all duration-500" style="width: {{ $project->progress }}%"></div>
                        </div>
                    </div>

                    {{-- Phases & Milestones --}}
                    <div>
                        <div class="mb-3 flex items-center justify-between">
                            <h5 class="text-base font-semibold">Phases & Milestones</h5>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.core.projects.phases.index', ['project' => $project->id]) }}" class="btn btn-xs btn-outline-primary">Manage Phases</a>
                                <a href="{{ route('admin.core.projects.milestones.index', ['project' => $project->id]) }}" class="btn btn-xs btn-outline-primary">Manage Milestones</a>
                            </div>
                        </div>

                        @if($project->phases->isNotEmpty())
                            <div class="space-y-3">
                                @foreach($project->phases as $phase)
                                    <div class="rounded-lg border p-3 dark:border-gray-700">
                                        <div class="flex items-center justify-between">
                                            <span class="font-semibold text-xs">{{ $phase->name }}</span>
                                            @php $pColors = ['planned' => 'badge-outline-secondary', 'active' => 'badge-outline-success', 'completed' => 'badge-outline-primary', 'delayed' => 'badge-outline-danger']; @endphp
                                            <span class="badge {{ $pColors[$phase->status] ?? 'badge-outline-secondary' }} text-[10px]">{{ $phase->status }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-400">No phases configured yet.</p>
                        @endif
                    </div>
                </div>

                {{-- Operational Summary Panel --}}
                <div class="space-y-4">
                    <h5 class="text-base font-semibold">Operational Summary</h5>
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800/40 border dark:border-gray-700">
                        <span class="text-xs text-white-dark">Total Construction Sites</span>
                        <span class="text-sm font-bold">{{ $project->sites->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800/40 border dark:border-gray-700">
                        <span class="text-xs text-white-dark">Active Work Tasks</span>
                        <span class="text-sm font-bold">{{ $project->tasks->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800/40 border dark:border-gray-700">
                        <span class="text-xs text-white-dark">Allocated Resources</span>
                        <span class="text-sm font-bold">{{ $project->resources->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800/40 border dark:border-gray-700">
                        <span class="text-xs text-white-dark">Created By</span>
                        <span class="text-xs font-semibold">{{ $project->creator->name ?? 'System Admin' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab 2: Invoices & Income --}}
        <div x-show="activeTab === 'invoices'" class="panel rounded-t-none border-t-0">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h5 class="text-base font-semibold">Customer Invoices & Receivables</h5>
                    <p class="text-xs text-white-dark">Total Revenue Invoiced: <span class="font-bold text-success">৳{{ number_format($totalInvoiced, 2) }}</span></p>
                </div>
                <a href="{{ route('admin.finance.invoices.create') }}?project_id={{ $project->id }}" class="btn btn-sm btn-primary">+ Create Invoice</a>
            </div>

            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto text-xs">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800">
                            <th>Invoice #</th>
                            <th>Invoice Date</th>
                            <th>Total Amount</th>
                            <th>Paid Amount</th>
                            <th>Due Amount</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $inv)
                            <tr class="border-t dark:border-gray-800">
                                <td class="font-bold text-primary font-mono">{{ $inv->invoice_number }}</td>
                                <td>{{ $inv->invoice_date ? $inv->invoice_date->format('d M Y') : '—' }}</td>
                                <td class="font-mono font-semibold">৳{{ number_format($inv->total_amount, 2) }}</td>
                                <td class="font-mono text-success">৳{{ number_format($inv->paid_amount, 2) }}</td>
                                <td class="font-mono font-bold {{ $inv->due_amount > 0 ? 'text-danger' : 'text-success' }}">৳{{ number_format($inv->due_amount, 2) }}</td>
                                <td>
                                    @php $iCls = ['paid' => 'badge-outline-success', 'partial' => 'badge-outline-warning', 'overdue' => 'badge-outline-danger', 'sent' => 'badge-outline-info', 'draft' => 'badge-outline-secondary']; @endphp
                                    <span class="badge {{ $iCls[$inv->status] ?? 'badge-outline-secondary' }} capitalize">{{ $inv->status }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.finance.invoices.show', ['invoice' => $inv->id]) }}" class="btn btn-xs btn-outline-info">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-4 text-gray-400">No invoices generated for this project yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tab 3: Bills & Expenses --}}
        <div x-show="activeTab === 'bills'" class="panel rounded-t-none border-t-0 space-y-6">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h5 class="text-base font-semibold">Vendor Bills Payable</h5>
                        <p class="text-xs text-white-dark">Total Bills: <span class="font-bold text-danger">৳{{ number_format($totalBills, 2) }}</span></p>
                    </div>
                    <a href="{{ route('admin.finance.bills.create') }}" class="btn btn-sm btn-primary">+ Create Bill</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="table-hover w-full table-auto text-xs">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-800">
                                <th>Bill #</th>
                                <th>Vendor</th>
                                <th>Bill Date</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bills as $bill)
                                <tr class="border-t dark:border-gray-800">
                                    <td class="font-bold text-primary font-mono">{{ $bill->bill_number }}</td>
                                    <td>{{ $bill->vendor?->name ?? 'N/A' }}</td>
                                    <td>{{ $bill->bill_date ? $bill->bill_date->format('d M Y') : '—' }}</td>
                                    <td class="font-mono font-semibold">৳{{ number_format($bill->total_amount, 2) }}</td>
                                    <td class="font-mono text-success">৳{{ number_format($bill->paid_amount, 2) }}</td>
                                    <td class="font-mono font-bold {{ $bill->due_amount > 0 ? 'text-danger' : 'text-success' }}">৳{{ number_format($bill->due_amount, 2) }}</td>
                                    <td>
                                        @php $bCls = ['paid' => 'badge-outline-success', 'approved' => 'badge-outline-primary', 'draft' => 'badge-outline-secondary', 'cancelled' => 'badge-outline-dark']; @endphp
                                        <span class="badge {{ $bCls[$bill->status] ?? 'badge-outline-secondary' }} capitalize">{{ $bill->status }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.finance.bills.show', ['bill' => $bill->id]) }}" class="btn btn-xs btn-outline-info">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center py-4 text-gray-400">No vendor bills recorded for this project yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Direct Expenses Section --}}
            <div class="border-t pt-5 dark:border-gray-800">
                <h5 class="text-base font-semibold mb-3">Direct Overhead Expenses</h5>
                <div class="overflow-x-auto">
                    <table class="table-hover w-full table-auto text-xs">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-800">
                                <th>Category</th>
                                <th>Title / Description</th>
                                <th>Expense Date</th>
                                <th>Amount</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $exp)
                                <tr class="border-t dark:border-gray-800">
                                    <td class="font-semibold capitalize">{{ $exp->category ?? 'General' }}</td>
                                    <td>{{ $exp->title ?? $exp->description }}</td>
                                    <td>{{ $exp->expense_date ? \Carbon\Carbon::parse($exp->expense_date)->format('d M Y') : '—' }}</td>
                                    <td class="font-mono font-bold text-danger">৳{{ number_format($exp->amount, 2) }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.finance.expenses.show', ['expense' => $exp->id]) }}" class="btn btn-xs btn-outline-info">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-4 text-gray-400">No direct expenses recorded yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Tab 4: Procurement & BBS Sheets --}}
        <div x-show="activeTab === 'procurement'" class="panel rounded-t-none border-t-0 space-y-6">
            {{-- Purchase Orders --}}
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h5 class="text-base font-semibold">Purchase Orders (POs)</h5>
                    <a href="{{ route('admin.procurement.purchase-orders.create') }}" class="btn btn-sm btn-primary">+ Create PO</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="table-hover w-full table-auto text-xs">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-800">
                                <th>PO Number</th>
                                <th>Vendor</th>
                                <th>Order Date</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchaseOrders as $po)
                                <tr class="border-t dark:border-gray-800">
                                    <td class="font-bold text-primary font-mono">{{ $po->po_number }}</td>
                                    <td>{{ $po->vendor?->name ?? 'N/A' }}</td>
                                    <td>{{ $po->order_date ? $po->order_date->format('d M Y') : '—' }}</td>
                                    <td class="font-mono font-semibold">৳{{ number_format($po->total_amount, 2) }}</td>
                                    <td>
                                        @php $poCls = ['ordered' => 'badge-outline-primary', 'received' => 'badge-outline-success', 'partially_received' => 'badge-outline-warning', 'draft' => 'badge-outline-secondary']; @endphp
                                        <span class="badge {{ $poCls[$po->status] ?? 'badge-outline-secondary' }} capitalize">{{ str_replace('_', ' ', $po->status) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.procurement.purchase-orders.show', ['purchase_order' => $po->id]) }}" class="btn btn-xs btn-outline-info">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center py-4 text-gray-400">No purchase orders created yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Rod & Concrete Calculation Sheets (BBS) --}}
            <div class="border-t pt-5 dark:border-gray-800">
                <div class="flex items-center justify-between mb-4">
                    <h5 class="text-base font-semibold">Rod & Concrete Calculation Sheets (BBS)</h5>
                    <a href="{{ route('admin.finance.rod-calculations.create') }}" class="btn btn-sm btn-primary">+ New BBS Sheet</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="table-hover w-full table-auto text-xs">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-800">
                                <th>Sheet Code</th>
                                <th>Structure / Element</th>
                                <th>Total Rebar (Kg)</th>
                                <th>Rebar Cost</th>
                                <th>Concrete Vol (m³)</th>
                                <th>Concrete Cost</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rodCalculations as $sheet)
                                <tr class="border-t dark:border-gray-800">
                                    <td class="font-bold text-primary font-mono">{{ $sheet->code }}</td>
                                    <td>{{ $sheet->structure_name }}</td>
                                    <td class="font-mono font-semibold">{{ number_format($sheet->total_weight_kg, 2) }} kg</td>
                                    <td class="font-mono text-primary">৳{{ number_format($sheet->total_rebar_cost, 2) }}</td>
                                    <td class="font-mono">{{ number_format($sheet->total_concrete_volume_m3, 2) }} m³</td>
                                    <td class="font-mono text-primary">৳{{ number_format($sheet->total_concrete_cost, 2) }}</td>
                                    <td>
                                        @php $sCls = ['approved' => 'badge-outline-success', 'draft' => 'badge-outline-warning', 'completed' => 'badge-outline-primary']; @endphp
                                        <span class="badge {{ $sCls[$sheet->status] ?? 'badge-outline-secondary' }} capitalize">{{ $sheet->status }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.finance.rod-calculations.show', ['rodCalculation' => $sheet->id]) }}" class="btn btn-xs btn-outline-info">View Sheet</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center py-4 text-gray-400">No Rod & Concrete BBS sheets created for this project yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Tab 5: Resources & Equipment --}}
        <div x-show="activeTab === 'resources'" class="panel rounded-t-none border-t-0">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h5 class="text-base font-semibold">Project Resources & Budget Allocation</h5>
                    <p class="text-xs text-white-dark">Total Resource Budget: <span class="font-bold text-primary">৳{{ number_format($resourceCosts, 2) }}</span></p>
                </div>
                <a href="{{ route('admin.core.projects.resources.index', ['project' => $project->id]) }}" class="btn btn-sm btn-primary">Manage Resources</a>
            </div>

            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto text-xs">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800">
                            <th>Resource Name</th>
                            <th>Resource Type</th>
                            <th>Allocated Quantity</th>
                            <th>Unit Rate (৳)</th>
                            <th>Total Cost (৳)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($project->resources as $res)
                            <tr class="border-t dark:border-gray-800">
                                <td class="font-semibold">{{ $res->name }}</td>
                                <td class="capitalize">{{ $res->resource_type ?? 'General' }}</td>
                                <td class="font-mono">{{ number_format($res->quantity, 2) }}</td>
                                <td class="font-mono">৳{{ number_format($res->cost_per_unit, 2) }}</td>
                                <td class="font-mono font-bold text-primary">৳{{ number_format($res->total_cost, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4 text-gray-400">No resource allocations logged for this project yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
