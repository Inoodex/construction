@extends('admin.layouts.master')

@section('title', 'Progress & Schedule Report (S-Curve)')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
<style>
    .milestone-dot { display: inline-block; width: 10px; height: 10px; border-radius: 50%; margin-right: 4px; }
</style>
@endpush

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold dark:text-white">Progress & Schedule Report</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">S-Curve — Planned vs Actual progress over time</p>
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

{{-- Summary Cards --}}
<div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div class="panel stat-card">
        <p class="text-2xl font-bold text-primary">{{ $totalProjects }}</p>
        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Projects Tracked</p>
    </div>
    <div class="panel stat-card">
        <p class="text-2xl font-bold text-info">{{ $totalTasks }}</p>
        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Tasks with Dates</p>
    </div>
    <div class="panel stat-card">
        <p class="text-2xl font-bold text-warning">{{ $avgProgress ? number_format($avgProgress, 1) : 0 }}%</p>
        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Avg Task Progress</p>
    </div>
    <div class="panel stat-card">
        <p class="text-2xl font-bold text-success">{{ $phases->count() }}</p>
        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Phases</p>
    </div>
</div>

{{-- Charts per Project --}}
@forelse($allSeries as $series)
<div class="mb-6 panel">
    <div class="mb-4 flex items-center justify-between">
        <h5 class="text-base font-semibold dark:text-white">
            <span class="text-primary">{{ $series['project']->name }}</span>
            <span class="ml-2 text-xs text-gray-400">({{ $series['task_count'] }} tasks &middot; {{ $series['total_weight_days'] }} total days)</span>
        </h5>
        @php
            $lastPlanned = count($series['planned']) > 0 ? end($series['planned']) : 0;
            $lastActual = count($series['actual']) > 0 ? end($series['actual']) : 0;
            $variance = $lastActual - $lastPlanned;
        @endphp
        <span class="text-xs {{ $variance >= 0 ? 'text-success' : 'text-danger' }}">
            {{ $variance >= 0 ? '+' : '' }}{{ number_format($variance, 1) }}% variance
        </span>
    </div>
    <div id="sCurveChart_{{ $series['project']->id }}" class="min-h-[320px]"></div>

    {{-- Milestones --}}
    @if(count($series['milestones']) > 0)
    <div class="mt-4 flex flex-wrap gap-3">
        @foreach($series['milestones'] as $ms)
        <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-medium
            {{ $ms['status'] === 'achieved' ? 'bg-success/10 text-success' : ($ms['status'] === 'missed' ? 'bg-danger/10 text-danger' : 'bg-warning/10 text-warning') }}">
            <span class="milestone-dot {{ $ms['status'] === 'achieved' ? 'bg-success' : ($ms['status'] === 'missed' ? 'bg-danger' : 'bg-warning') }}"></span>
            {{ $ms['name'] }} — {{ \Carbon\Carbon::parse($ms['date'])->format('d M') }}
        </span>
        @endforeach
    </div>
    @endif
</div>
@empty
<div class="panel">
    <p class="py-8 text-center text-sm text-gray-400">No task data found. Add tasks with start/end dates and progress to see the S-Curve.</p>
</div>
@endforelse

{{-- Phase Summary --}}
@if($phases->count() > 0)
<div class="panel">
    <h5 class="mb-4 text-base font-semibold dark:text-white">Phase Summary</h5>
    <div class="table-responsive">
        <table class="table-striped table">
            <thead>
                <tr>
                    <th class="text-xs">Phase</th>
                    <th class="text-xs">Project</th>
                    <th class="text-xs">Start</th>
                    <th class="text-xs">End</th>
                    <th class="text-xs">Tasks</th>
                    <th class="text-xs">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($phases as $phase)
                <tr>
                    <td class="text-xs font-semibold">{{ $phase->name }}</td>
                    <td class="text-xs">{{ $phase->project->name ?? 'N/A' }}</td>
                    <td class="text-xs">{{ $phase->start_date ? $phase->start_date->format('d M Y') : '-' }}</td>
                    <td class="text-xs">{{ $phase->end_date ? $phase->end_date->format('d M Y') : '-' }}</td>
                    <td class="text-xs">{{ $phase->tasks_count ?? 0 }}</td>
                    <td>
                        <span class="badge badge-outline-{{ $phase->status === 'completed' ? 'success' : ($phase->status === 'active' ? 'info' : ($phase->status === 'delayed' ? 'danger' : 'secondary')) }} text-xs">
                            {{ ucfirst($phase->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.documentElement.classList.contains('dark');
    const textColor = isDark ? '#888ea8' : '#515365';
    const gridColor = isDark ? '#1b2e4b' : '#e0e6ed';

    @forelse($allSeries as $series)
    (function() {
        const plannedData = [{{ implode(',', $series['planned']) }}];
        const actualData = [{{ implode(',', $series['actual']) }}];
        const labels = [{{ collect($series['labels'])->map(fn($d) => "'".\Carbon\Carbon::parse($d)->format('d M')."'")->implode(',') }}];

        // Sample labels to avoid overcrowding (show every Nth label)
        const step = Math.max(1, Math.floor(labels.length / 20));
        const sampledLabels = labels.map((l, i) => i % step === 0 ? l : '');

        new ApexCharts(document.querySelector("#sCurveChart_{{ $series['project']->id }}"), {
            series: [{
                name: 'Planned',
                type: 'line',
                data: plannedData
            }, {
                name: 'Actual',
                type: 'line',
                data: actualData
            }],
            chart: {
                height: 320,
                toolbar: { show: true },
                background: 'transparent',
                animations: { enabled: false }
            },
            colors: ['#4361ee', '#00ab55'],
            stroke: {
                curve: 'smooth',
                width: [3, 3],
                dashArray: [5, 0]
            },
            markers: {
                size: [0, 0],
                hover: { size: 5 }
            },
            xaxis: {
                categories: sampledLabels,
                labels: { style: { colors: textColor, fontSize: '9px' }, rotate: -45 },
                tickAmount: 20,
                title: { text: 'Date', style: { color: textColor, fontSize: '11px' } }
            },
            yaxis: {
                min: 0,
                max: 100,
                labels: {
                    style: { colors: textColor, fontSize: '11px' },
                    formatter: v => v.toFixed(0) + '%'
                },
                title: { text: 'Cumulative Progress %', style: { color: textColor, fontSize: '11px' } }
            },
            grid: { borderColor: gridColor },
            legend: {
                position: 'top',
                labels: { colors: textColor },
                fontSize: '12px'
            },
            annotations: {
                points: [
                    @foreach($series['milestones'] as $ms)
                    {
                        x: '{{ \Carbon\Carbon::parse($ms['date'])->format('d M') }}',
                        y: {{ $ms['y'] }},
                        marker: {
                            size: 6,
                            fillColor: '{{ $ms['status'] === 'achieved' ? '#00ab55' : ($ms['status'] === 'missed' ? '#e7515a' : '#e2a03f') }}',
                            strokeColor: '#fff',
                            radius: 2
                        },
                        label: {
                            text: '{{ $ms['name'] }}',
                            borderColor: '{{ $ms['status'] === 'achieved' ? '#00ab55' : ($ms['status'] === 'missed' ? '#e7515a' : '#e2a03f') }}',
                            style: {
                                color: '#fff',
                                background: '{{ $ms['status'] === 'achieved' ? '#00ab55' : ($ms['status'] === 'missed' ? '#e7515a' : '#e2a03f') }}',
                                fontSize: '9px'
                            }
                        }
                    },
                    @endforeach
                ]
            },
            tooltip: {
                y: {
                    formatter: v => v.toFixed(1) + '%'
                }
            },
            theme: { mode: isDark ? 'dark' : 'light' }
        }).render();
    })();
    @empty
    @endforelse
});
</script>
@endpush
