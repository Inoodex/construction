@extends('admin.layouts.master')

@section('title', 'Gantt Chart')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Gantt Chart — {{ $project->name }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.core.projects.show', $project) }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to Project
            </a>
        </div>
    </div>

    @php
        $dayWidth = 24;
        $totalWidth = ($totalDays + 1) * $dayWidth;
        $rowH = 36;
        $labelW = 220;
        $chartStartTs = $chartStart->timestamp;

        function pct($date, $chartStart, $totalDays) {
            $days = $chartStart->diffInDays($date);
            return ($days / max($totalDays, 1)) * 100;
        }
    @endphp

    <div class="panel mt-6 overflow-hidden">
        <div class="mb-4 flex items-center justify-between text-xs text-white-dark">
            <span>{{ $chartStart->format('d M Y') }}</span>
            <span>{{ $chartEnd->format('d M Y') }}</span>
        </div>

        <div class="overflow-x-auto" style="max-height: 70vh;">
            <div class="relative" style="min-width: {{ $labelW + $totalWidth + 40 }}px;">

                {{-- Week header --}}
                <div class="sticky top-0 z-10 flex bg-white dark:bg-[#0e1726]" style="margin-left: {{ $labelW }}px;">
                    @foreach($weeks as $week)
                        <div class="border-l text-center text-[10px] text-white-dark" style="width: {{ $dayWidth * 7 }}px; min-width: {{ $dayWidth * 7 }}px;{{ $loop->last ? 'border-right:0' : '' }}">
                            {{ $week->format('d M') }}
                        </div>
                    @endforeach
                </div>

                {{-- Day grid lines --}}
                <div class="absolute inset-0 pointer-events-none" style="left: {{ $labelW }}px; top: 20px;">
                    @for($d = 0; $d <= $totalDays; $d++)
                        <div class="absolute top-0 h-full border-l border-gray-100 dark:border-gray-800" style="left: {{ $d * $dayWidth }}px;"></div>
                    @endfor
                </div>

                {{-- Today line --}}
                @php
                    $todayDays = $chartStart->diffInDays(now()->startOfDay());
                @endphp
                @if($todayDays >= 0 && $todayDays <= $totalDays)
                    <div class="absolute top-0 h-full border-l-2 border-danger/60 z-20" style="left: {{ $labelW + $todayDays * $dayWidth }}px;" title="Today"></div>
                @endif

                {{-- Bars --}}
                <div class="space-y-0.5 mt-1">
                    {{-- Phases --}}
                    @foreach($phases as $phase)
                        @php
                            $leftPct = pct($phase->start_date, $chartStart, $totalDays);
                            $wPct = pct($phase->end_date, $chartStart, $totalDays) - $leftPct;
                            $phaseColors = ['planned' => 'bg-primary/30 border-primary', 'active' => 'bg-primary/60 border-primary', 'completed' => 'bg-success/60 border-success', 'delayed' => 'bg-danger/60 border-danger'];
                        @endphp
                        <div class="flex items-center" style="height: {{ $rowH }}px;">
                            <div class="shrink-0 truncate pr-2 text-right text-xs font-semibold" style="width: {{ $labelW }}px;">{{ $phase->name }}</div>
                            <div class="relative flex-1" style="height: {{ $rowH }}px;">
                                <div class="absolute top-1/2 -translate-y-1/2 rounded border {{ $phaseColors[$phase->status] ?? 'bg-secondary/30 border-secondary' }} text-[10px] leading-tight text-black dark:text-white flex items-center px-1 overflow-hidden whitespace-nowrap" style="left: {{ max($leftPct, 0) }}%; width: {{ max($wPct, 2) }}%; height: 22px;">
                                    {{ $phase->name }}
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- Tasks grouped by phase --}}
                    @php $grouped = $tasks->groupBy('phase_id'); @endphp
                    @foreach($phases as $phase)
                        @foreach($grouped->get($phase->id, collect()) as $task)
                            @php
                                $leftPct = pct($task->start_date, $chartStart, $totalDays);
                                $wPct = pct($task->end_date, $chartStart, $totalDays) - $leftPct;
                                $taskColors = ['open' => 'bg-info/30 border-info', 'in_progress' => 'bg-warning/40 border-warning', 'review' => 'bg-info/50 border-info', 'closed' => 'bg-success/40 border-success'];
                            @endphp
                            <div class="flex items-center" style="height: {{ $rowH }}px;">
                                <div class="shrink-0 truncate pr-2 text-right text-xs text-white-dark" style="width: {{ $labelW }}px;">{{ $task->name }}</div>
                                <div class="relative flex-1" style="height: {{ $rowH }}px;">
                                    <div class="absolute top-1/2 -translate-y-1/2 rounded border {{ $taskColors[$task->status] ?? 'bg-info/30 border-info' }} text-[10px] leading-tight text-black dark:text-white flex items-center px-1 overflow-hidden whitespace-nowrap" style="left: {{ max($leftPct, 0) }}%; width: {{ max($wPct, 2) }}%; height: 20px;">
                                        {{ $task->name }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach

                    {{-- Tasks without a phase --}}
                    @foreach($grouped->get(null, collect()) as $task)
                        @php
                            $leftPct = pct($task->start_date, $chartStart, $totalDays);
                            $wPct = pct($task->end_date, $chartStart, $totalDays) - $leftPct;
                            $taskColors = ['open' => 'bg-info/30 border-info', 'in_progress' => 'bg-warning/40 border-warning', 'review' => 'bg-info/50 border-info', 'closed' => 'bg-success/40 border-success'];
                        @endphp
                        <div class="flex items-center" style="height: {{ $rowH }}px;">
                            <div class="shrink-0 truncate pr-2 text-right text-xs text-white-dark" style="width: {{ $labelW }}px;">{{ $task->name }}</div>
                            <div class="relative flex-1" style="height: {{ $rowH }}px;">
                                <div class="absolute top-1/2 -translate-y-1/2 rounded border {{ $taskColors[$task->status] ?? 'bg-info/30 border-info' }} text-[10px] leading-tight text-black dark:text-white flex items-center px-1 overflow-hidden whitespace-nowrap" style="left: {{ max($leftPct, 0) }}%; width: {{ max($wPct, 2) }}%; height: 20px;">
                                    {{ $task->name }}
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- Milestones --}}
                    @foreach($milestones as $ms)
                        @php
                            $leftPct = pct($ms->target_date, $chartStart, $totalDays);
                            $msColors = ['pending' => 'border-warning', 'achieved' => 'border-success', 'missed' => 'border-danger'];
                        @endphp
                        <div class="flex items-center" style="height: {{ $rowH }}px;">
                            <div class="shrink-0 truncate pr-2 text-right text-xs text-white-dark" style="width: {{ $labelW }}px;">{{ $ms->name }}</div>
                            <div class="relative flex-1" style="height: {{ $rowH }}px;">
                                <div class="absolute top-1/2 -translate-y-1/2 z-10" style="left: {{ $leftPct }}%;">
                                    <div class="h-3 w-3 rotate-45 border-2 {{ $msColors[$ms->status] ?? 'border-warning' }}" title="{{ $ms->name }} — {{ $ms->target_date->format('d M Y') }}"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

        <div class="mt-4 flex items-center gap-6 text-xs text-white-dark">
            <div class="flex items-center gap-2">
                <span class="inline-block h-3 w-6 rounded bg-primary/50 border border-primary"></span> Phase
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-block h-3 w-6 rounded bg-info/50 border border-info"></span> Task
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-block h-3 w-3 rotate-45 border-2 border-warning"></span> Milestone
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-block h-3 w-0.5 bg-danger"></span> Today
            </div>
        </div>
    </div>
@endsection
