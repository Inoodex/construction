@extends('admin.layouts.master')

@section('title', 'Milestone Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">{{ $milestone->name }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.core.projects.milestones.index', $project) }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to Milestones
            </a>
            <a href="{{ route('admin.core.projects.milestones.edit', [$project, $milestone]) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit
            </a>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="panel lg:col-span-2">
            <h5 class="mb-4 text-base font-semibold">Milestone Information</h5>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="text-xs text-white-dark">Project</label>
                    <p class="font-semibold">{{ $project->name }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Phase</label>
                    <p class="font-semibold">{{ $milestone->phase?->name ?: '—' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Status</label>
                    @php $colors = ['pending' => 'badge-outline-warning', 'achieved' => 'badge-outline-success', 'missed' => 'badge-outline-danger']; @endphp
                    <span class="badge {{ $colors[$milestone->status] ?? 'badge-outline-secondary' }} capitalize">{{ $milestone->status }}</span>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Target Date</label>
                    <p class="font-semibold">{{ $milestone->target_date?->format('d M Y') ?: '—' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Achieved Date</label>
                    <p class="font-semibold">{{ $milestone->achieved_date?->format('d M Y') ?: '—' }}</p>
                </div>
            </div>
            <div class="mt-4">
                <label class="text-xs text-white-dark">Description</label>
                <p class="font-semibold">{{ $milestone->description ?: '—' }}</p>
            </div>
        </div>

        <div class="panel">
            <h5 class="mb-4 text-base font-semibold">Timeline</h5>
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-white-dark">Created</span>
                    <span class="text-xs font-semibold">{{ $milestone->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-white-dark">Days to Target</span>
                    <span class="text-xs font-semibold">
                        @if($milestone->target_date)
                            {{ now()->startOfDay()->diffInDays($milestone->target_date, false) }} days
                        @else
                            —
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
@endsection
