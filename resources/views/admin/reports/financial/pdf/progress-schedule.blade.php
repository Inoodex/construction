@extends('admin.reports.financial.pdf._master')
@section('title', 'Progress Schedule Report')
@section('content')
<table class="stats">
    <tr>
        <td><div class="value">{{ $totalProjects }}</div><div class="label">Projects Tracked</div></td>
        <td><div class="value">{{ $totalTasks }}</div><div class="label">Total Tasks</div></td>
        <td><div class="value">{{ round($avgProgress, 1) }}%</div><div class="label">Avg Progress</div></td>
    </tr>
</table>

@forelse($allSeries as $series)
<div style="page-break-inside: avoid; margin-bottom: 12px;">
    <h5>{{ $series['project']->name }}</h5>
    <table class="data">
        <thead>
            <tr>
                <th>Date</th>
                <th class="text-right">Planned %</th>
                <th class="text-right">Actual %</th>
                <th class="text-right">Variance</th>
            </tr>
        </thead>
        <tbody>
            @php $interval = max(1, floor(count($series['labels']) / 30)); @endphp
            @foreach($series['labels'] as $idx => $label)
                @if($idx % $interval == 0 || $idx == count($series['labels']) - 1)
                <tr>
                    <td class="font-mono">{{ \Carbon\Carbon::parse($label)->format('d M y') }}</td>
                    <td class="text-right">{{ $series['planned'][$idx] }}%</td>
                    <td class="text-right">{{ $series['actual'][$idx] }}%</td>
                    <td class="text-right {{ $series['actual'][$idx] >= $series['planned'][$idx] ? 'text-success' : 'text-danger' }}">
                        {{ round($series['actual'][$idx] - $series['planned'][$idx], 2) }}%
                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    @if(count($series['milestones']) > 0)
    <table class="data" style="margin-top: 4px;">
        <thead>
            <tr><th>Milestone</th><th>Target Date</th><th class="text-right">Progress</th><th>Status</th></tr>
        </thead>
        <tbody>
            @foreach($series['milestones'] as $ms)
            <tr>
                <td class="font-semibold">{{ $ms['name'] }}</td>
                <td>{{ $ms['date'] }}</td>
                <td class="text-right">{{ $ms['y'] }}%</td>
                <td>{{ ucfirst($ms['status'] ?? '—') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@empty
<p style="color:#888ea8; text-align:center; padding:20px;">No project schedule data available</p>
@endforelse

@if($phases->isNotEmpty())
<h5>Phase Summary</h5>
<table class="data">
    <thead>
        <tr><th>Phase</th><th class="text-center">Tasks</th></tr>
    </thead>
    <tbody>
        @foreach($phases as $phase)
        <tr>
            <td>{{ $phase->name }}</td>
            <td class="text-center">{{ $phase->tasks_count }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection