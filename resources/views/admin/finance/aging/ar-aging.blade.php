@extends('admin.layouts.master')

@section('title', 'Accounts Receivable (AR) Aging Report')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
@endpush

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold uppercase dark:text-white">Accounts Receivable (AR) Aging Report</h2>
            <p class="mt-0.5 text-xs text-white-dark">Track customer outstanding receivables across 30, 60, 90+ day aging brackets</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.finance.invoices.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                View Invoices
            </a>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="panel mt-6 mb-6">
        <form action="{{ route('admin.finance.aging.ar') }}" method="GET" class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-xs font-semibold text-white-dark whitespace-nowrap">Filter Project:</span>
                <select name="project_id" class="form-select w-64 text-xs" onchange="this.form.submit()">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            @if(request()->filled('project_id'))
                <a href="{{ route('admin.finance.aging.ar') }}" class="btn btn-sm btn-outline-danger">Reset Filter</a>
            @endif
        </form>
    </div>

    {{-- Aging Bucket Executive Metric Cards Grid --}}
    <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        @foreach($buckets as $key => $bucket)
            @php
                $color = match($key) {
                    'current' => 'text-success',
                    'days1_30' => 'text-info',
                    'days31_60' => 'text-primary',
                    'days61_90' => 'text-warning',
                    'days90_plus' => 'text-danger',
                    default => 'text-primary'
                };
            @endphp
            <div class="panel text-center flex flex-col items-center justify-center">
                <label class="text-xs text-white-dark uppercase font-semibold">{{ $bucket['label'] }}</label>
                <p class="text-xl font-extrabold {{ $color }} mt-1">৳{{ number_format($bucket['total'], 2) }}</p>
                <span class="text-[11px] text-white-dark mt-0.5">{{ $bucket['invoices']->count() }} Invoices</span>
            </div>
        @endforeach
    </div>

    {{-- Grand Total & Chart Row --}}
    <div class="mb-6 grid gap-6 lg:grid-cols-3">
        <div class="panel lg:col-span-1 text-center flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-800">
            <label class="text-xs text-white-dark uppercase font-semibold">Total Outstanding Receivables</label>
            <p class="text-3xl font-extrabold {{ $grandTotal > 0 ? 'text-danger' : 'text-success' }} mt-2">
                ৳{{ number_format($grandTotal, 2) }}
            </p>
            <span class="text-xs text-white-dark mt-1">Total Uncollected Billed Revenue</span>
        </div>
        <div class="panel lg:col-span-2 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <h5 class="text-sm font-bold uppercase text-primary">AR Aging Bracket Distribution</h5>
            </div>
            <div id="arAgingChart" class="w-full h-44"></div>
        </div>
    </div>

    {{-- Invoices by Bucket Tables --}}
    <div class="space-y-6">
        @foreach($buckets as $key => $bucket)
            @if($bucket['invoices']->count() > 0)
                @php
                    $badgeStyle = match($key) {
                        'current' => 'badge-outline-success',
                        'days1_30' => 'badge-outline-info',
                        'days31_60' => 'badge-outline-primary',
                        'days61_90' => 'badge-outline-warning',
                        'days90_plus' => 'badge-outline-danger',
                        default => 'badge-outline-primary'
                    };
                @endphp
                <div class="panel">
                    <div class="flex items-center justify-between mb-4 border-b pb-3 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <span class="badge {{ $badgeStyle }} text-xs uppercase px-2.5 py-1 font-bold">{{ $bucket['label'] }}</span>
                            <span class="text-xs text-white-dark font-medium">{{ $bucket['invoices']->count() }} Invoices</span>
                        </div>
                        <span class="font-mono font-extrabold text-sm text-primary">Subtotal: ৳{{ number_format($bucket['total'], 2) }}</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table-hover w-full table-auto text-xs">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-800 border-b dark:border-gray-700">
                                    <th class="py-2.5 px-4 text-left">Invoice #</th>
                                    <th class="py-2.5 px-4 text-left">Project</th>
                                    <th class="py-2.5 px-4 text-center">Due Date</th>
                                    <th class="py-2.5 px-4 text-right">Total (৳)</th>
                                    <th class="py-2.5 px-4 text-right">Due Amount (৳)</th>
                                    <th class="py-2.5 px-4 text-center">Overdue Days</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bucket['invoices'] as $inv)
                                    @php
                                        $daysOverdue = $inv->due_date ? max(0, (int) now()->diffInDays($inv->due_date, false) * -1) : 0;
                                    @endphp
                                    <tr class="border-t dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                        <td class="py-2.5 px-4">
                                            <a href="{{ route('admin.finance.invoices.show', $inv->id) }}" class="font-mono font-bold text-primary hover:underline">
                                                {{ $inv->invoice_number }}
                                            </a>
                                        </td>
                                        <td class="py-2.5 px-4 font-semibold text-sm">{{ $inv->project->name ?? 'N/A' }}</td>
                                        <td class="py-2.5 px-4 text-center font-medium">{{ $inv->due_date?->format('d M Y') ?? '—' }}</td>
                                        <td class="py-2.5 px-4 text-right font-mono text-white-dark">৳{{ number_format($inv->total_amount, 2) }}</td>
                                        <td class="py-2.5 px-4 text-right font-mono font-extrabold text-danger">৳{{ number_format($inv->due_amount, 2) }}</td>
                                        <td class="py-2.5 px-4 text-center">
                                            <span class="badge {{ $daysOverdue > 60 ? 'badge-outline-danger' : ($daysOverdue > 0 ? 'badge-outline-warning' : 'badge-outline-success') }} text-xs font-mono">
                                                {{ $daysOverdue > 0 ? $daysOverdue . ' days' : 'Current' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @endforeach

        @if($grandTotal <= 0)
            <div class="panel text-center py-8">
                <p class="text-base text-gray-400 font-semibold">🎉 All invoices are fully collected. No outstanding receivables found.</p>
            </div>
        @endif
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.documentElement.classList.contains('dark');
    const textColor = isDark ? '#888ea8' : '#515365';

    new ApexCharts(document.querySelector("#arAgingChart"), {
        series: [ @foreach($buckets as $b) {{ $b['total'] }}, @endforeach ],
        labels: [ @foreach($buckets as $b) "{{ addslashes($b['label']) }}", @endforeach ],
        chart: { type: 'donut', height: 180, toolbar: { show: false }, background: 'transparent' },
        colors: ['#00ab55', '#2196f3', '#4361ee', '#e2a03f', '#e7515a'],
        legend: { position: 'right', fontSize: '11px', labels: { colors: textColor } },
        dataLabels: { enabled: true, formatter: v => Math.round(v) + '%', style: { fontSize: '10px' } },
        stroke: { width: 2 },
        theme: { mode: isDark ? 'dark' : 'light' }
    }).render();
});
</script>
@endpush
@endsection
