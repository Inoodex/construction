@extends('admin.layouts.master')

@section('title', 'Risk Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">{{ $risk->risk_number }} — {{ $risk->title }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.quality.risks.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back
            </a>
            <a href="{{ route('admin.quality.risks.edit', $risk) }}" class="btn btn-primary gap-2">Edit</a>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="panel lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h5 class="text-base font-semibold">Risk Details</h5>
                <div class="flex gap-2">
                    <span class="badge badge-outline-secondary capitalize">{{ str_replace('_', ' ', $risk->category) }}</span>
                    @php
                        $stCls = match($risk->status) {
                            'closed' => 'badge-outline-success',
                            'mitigated' => 'badge-outline-warning',
                            'in_progress' => 'badge-outline-info',
                            default => 'badge-outline-secondary',
                        };
                    @endphp
                    <span class="badge {{ $stCls }} capitalize">{{ str_replace('_', ' ', $risk->status) }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div><label class="text-xs text-white-dark">Risk #</label><p class="font-mono font-semibold">{{ $risk->risk_number }}</p></div>
                <div><label class="text-xs text-white-dark">Project</label><p class="font-semibold">{{ $risk->project->name ?? '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Risk Owner</label><p class="font-semibold">{{ $risk->owner->name ?? 'Unassigned' }}</p></div>
                <div><label class="text-xs text-white-dark">Identified By</label><p class="font-semibold">{{ $risk->creator->name ?? '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Identified Date</label><p class="font-semibold">{{ $risk->identified_date->format('d M Y') }}</p></div>
                <div><label class="text-xs text-white-dark">Mitigation Due</label><p class="font-semibold">{{ $risk->due_date?->format('d M Y') ?? '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Next Review</label><p class="font-semibold">{{ $risk->review_date?->format('d M Y') ?? '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Closed Date</label><p class="font-semibold">{{ $risk->closed_date?->format('d M Y') ?? '—' }}</p></div>
            </div>

            <hr class="my-4 border-white-light dark:border-gray-700">
            <div><label class="text-xs text-white-dark">Description</label><p class="mt-1 whitespace-pre-wrap">{{ $risk->description }}</p></div>

            @if($risk->mitigation_plan)
                <hr class="my-4 border-white-light dark:border-gray-700">
                <div><label class="text-xs text-white-dark">Mitigation Plan</label><p class="mt-1 whitespace-pre-wrap">{{ $risk->mitigation_plan }}</p></div>
            @endif

            @if($risk->contingency_plan)
                <hr class="my-4 border-white-light dark:border-gray-700">
                <div><label class="text-xs text-white-dark">Contingency Plan</label><p class="mt-1 whitespace-pre-wrap">{{ $risk->contingency_plan }}</p></div>
            @endif

            @if($risk->notes)
                <hr class="my-4 border-white-light dark:border-gray-700">
                <div><label class="text-xs text-white-dark">Notes</label><p class="mt-1 whitespace-pre-wrap">{{ $risk->notes }}</p></div>
            @endif
        </div>

        <div class="space-y-6">
            <!-- Risk Score Card -->
            <div class="panel">
                <h5 class="mb-4 text-base font-semibold">Risk Assessment</h5>
                <div class="text-center">
                    <div class="mx-auto mb-3 flex h-24 w-24 items-center justify-center rounded-full border-4 {{ \App\Models\Risk::scoreColor($risk->risk_score) }}">
                        <span class="text-3xl font-bold">{{ $risk->risk_score }}</span>
                    </div>
                    <p class="text-sm font-semibold">{{ \App\Models\Risk::scoreLabel($risk->risk_score) }} Risk</p>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-3">
                    <div class="rounded-lg bg-gray-50 p-3 text-center dark:bg-gray-800">
                        <label class="text-xs text-white-dark">Probability</label>
                        <p class="font-semibold capitalize">{{ str_replace('_', ' ', $risk->probability) }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3 text-center dark:bg-gray-800">
                        <label class="text-xs text-white-dark">Impact</label>
                        <p class="font-semibold capitalize">{{ str_replace('_', ' ', $risk->impact) }}</p>
                    </div>
                </div>

                <!-- Mini Risk Matrix -->
                <div class="mt-4">
                    <label class="text-xs text-white-dark mb-2 block">Risk Matrix</label>
                    <table class="w-full text-center text-xs">
                        <thead>
                            <tr>
                                <th class="p-1"></th>
                                <th class="p-1 text-white-dark">VL</th>
                                <th class="p-1 text-white-dark">L</th>
                                <th class="p-1 text-white-dark">M</th>
                                <th class="p-1 text-white-dark">H</th>
                                <th class="p-1 text-white-dark">VH</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $probMap = ['very_low' => 1, 'low' => 2, 'medium' => 3, 'high' => 4, 'very_high' => 5];
                                $impMap = ['very_low' => 1, 'low' => 2, 'medium' => 3, 'high' => 4, 'very_high' => 5];
                                $currentProb = $probMap[$risk->probability] ?? 3;
                                $currentImp = $impMap[$risk->impact] ?? 3;
                                $labels = [1 => 'VL', 2 => 'L', 3 => 'M', 4 => 'H', 5 => 'VH'];
                            @endphp
                            @for($p = 5; $p >= 1; $p--)
                                <tr>
                                    <td class="p-1 font-semibold text-white-dark">{{ $labels[$p] }}</td>
                                    @for($i = 1; $i <= 5; $i++)
                                        @php
                                            $score = $p * $i;
                                            $bg = $score <= 5 ? 'bg-green-100 dark:bg-green-900' : ($score <= 12 ? 'bg-yellow-100 dark:bg-yellow-900' : 'bg-red-100 dark:bg-red-900');
                                            $isCurrent = ($p === $currentProb && $i === $currentImp);
                                        @endphp
                                        <td class="p-1 {{ $bg }} {{ $isCurrent ? 'ring-2 ring-primary font-bold' : '' }}">{{ $score }}</td>
                                    @endfor
                                </tr>
                            @endfor
                            <tr>
                                <td class="p-1"></td>
                                @for($i = 1; $i <= 5; $i++)
                                    <td class="p-1 text-white-dark">{{ $labels[$i] }}</td>
                                @endfor
                            </tr>
                        </tbody>
                    </table>
                    <p class="mt-1 text-center text-xs text-white-dark">Probability →</p>
                </div>
            </div>

            <!-- Info Card -->
            <div class="panel">
                <h5 class="mb-4 text-base font-semibold">Info</h5>
                <div class="space-y-3">
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                        <span class="text-xs">Created</span>
                        <span class="text-xs font-semibold">{{ $risk->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                        <span class="text-xs">Last Updated</span>
                        <span class="text-xs font-semibold">{{ $risk->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
