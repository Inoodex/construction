@extends('admin.layouts.master')

@section('title', 'Resource Allocation Chart')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Resource Allocation — {{ $project->name }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.core.projects.resources.index', $project) }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to Resources
            </a>
        </div>
    </div>

    @php
        $dayWidth = 20;
        $labelW = 250;
        $rowH = 36;
        $resColors = ['labor' => '#4361ee', 'equipment' => '#e2a03f', 'material' => '#00ab55'];

        function rpct($date, $chartStart, $totalDays) {
            $days = $chartStart->diffInDays($date);
            return ($days / max($totalDays, 1)) * 100;
        }
    @endphp

    <div class="panel mt-6 overflow-hidden">
        <div class="mb-4 flex items-center justify-between text-xs text-white-dark">
            <span>{{ $chartStart->format('d M Y') }}</span>
            <span>{{ $chartEnd->format('d M Y') }}</span>
        </div>

        <div class="overflow-x-auto" style="max-height: 75vh;">
            <div class="relative" style="min-width: {{ $labelW + ($totalDays + 1) * $dayWidth + 40 }}px;">

                {{-- Week header --}}
                <div class="sticky top-0 z-10 flex bg-white dark:bg-[#0e1726]" style="margin-left: {{ $labelW }}px;">
                    @foreach($weeks as $week)
                        <div class="border-l text-center text-[10px] text-white-dark" style="width: {{ $dayWidth * 7 }}px; min-width: {{ $dayWidth * 7 }}px;">
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
                @php $todayDays = $chartStart->diffInDays(now()->startOfDay()); @endphp
                @if($todayDays >= 0 && $todayDays <= $totalDays)
                    <div class="absolute top-0 h-full border-l-2 border-danger/60 z-20" style="left: {{ $labelW + $todayDays * $dayWidth }}px;" title="Today"></div>
                @endif

                {{-- Resource rows --}}
                <div class="space-y-0.5 mt-1">
                    @forelse($resources as $resource)
                        @php $color = $resColors[$resource->resource_type] ?? '#888'; @endphp
                        <div style="height: {{ $rowH * max(1, $resource->taskAllocations->count() + 1) }}px;">
                            {{-- Resource header row --}}
                            <div class="flex items-center" style="height: {{ $rowH }}px;">
                                <div class="shrink-0 truncate pr-2 text-right text-xs font-semibold" style="width: {{ $labelW }}px;">
                                    {{ $resource->name }}
                                    <span class="text-white-dark font-normal">({{ $resource->resource_type }})</span>
                                </div>
                                <div class="relative flex-1" style="height: {{ $rowH }}px;"></div>
                            </div>

                            {{-- Task allocation bars --}}
                            @foreach($resource->taskAllocations as $ta)
                                @php
                                    $start = $ta->start_date ?? $ta->task->start_date ?? $chartStart;
                                    $end = $ta->end_date ?? $ta->task->end_date ?? $chartEnd;
                                    $leftPct = rpct($start, $chartStart, $totalDays);
                                    $wPct = rpct($end, $chartStart, $totalDays) - $leftPct;
                                @endphp
                                <div class="flex items-center" style="height: {{ $rowH }}px;">
                                    <div class="shrink-0 truncate pr-2 text-right text-[10px] text-white-dark" style="width: {{ $labelW }}px;">
                                        ↳ {{ $ta->task->name }} ({{ number_format($ta->allocated_quantity, 1) }})
                                    </div>
                                    <div class="relative flex-1" style="height: {{ $rowH }}px;">
                                        <div class="absolute top-1/2 -translate-y-1/2 rounded text-[10px] leading-tight text-white flex items-center px-1 overflow-hidden whitespace-nowrap cursor-default" title="{{ $resource->name }} — {{ $ta->task->name }} ({{ number_format($ta->allocated_quantity, 1) }} {{ $resource->unit }})" style="left: {{ max($leftPct, 0) }}%; width: {{ max($wPct, 2) }}%; height: 20px; background: {{ $color }};">
                                            {{ $ta->task->name }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <div class="flex items-center justify-center py-10 text-sm text-white-dark">
                            No resource allocations found for this project.
                        </div>
                    @endforelse
                </div>

            </div>
        </div>

        <div class="mt-4 flex items-center gap-6 text-xs text-white-dark">
            <div class="flex items-center gap-2">
                <span class="inline-block h-3 w-3 rounded" style="background: #4361ee;"></span> Labor
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-block h-3 w-3 rounded" style="background: #e2a03f;"></span> Equipment
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-block h-3 w-3 rounded" style="background: #00ab55;"></span> Material
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-block h-3 w-0.5 bg-danger"></span> Today
            </div>
        </div>
    </div>
@endsection
