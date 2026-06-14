@extends('admin.layouts.master')

@section('title', 'Project Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Project Details</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.core.projects.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to List
            </a>
            <a href="{{ route('admin.core.projects.gantt', $project) }}" class="btn btn-info gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><rect x="3" y="3" width="18" height="18" rx="2"></rect><line x1="9" y1="3" x2="9" y2="21"></line><line x1="7" y1="8" x2="13" y2="8"></line><line x1="7" y1="12" x2="17" y2="12"></line><line x1="7" y1="16" x2="11" y2="16"></line></svg>
                Gantt Chart
            </a>
            <a href="{{ route('admin.core.projects.edit', $project->id) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Edit
            </a>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="panel lg:col-span-2">
            <h5 class="mb-4 text-base font-semibold">General Information</h5>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="text-xs text-white-dark">Project Name</label>
                    <p class="font-semibold">{{ $project->name }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Status</label>
                    <div>
                        @php $colors = ['planning' => 'badge-outline-warning', 'active' => 'badge-outline-success', 'on_hold' => 'badge-outline-danger', 'completed' => 'badge-outline-primary']; @endphp
                        <span class="badge {{ $colors[$project->status] ?? 'badge-outline-secondary' }} capitalize">{{ str_replace('_', ' ', $project->status) }}</span>
                    </div>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Budget</label>
                    <p class="font-semibold">৳{{ number_format($project->budget) }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Created By</label>
                    <p class="font-semibold">{{ $project->creator->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Start Date</label>
                    <p class="font-semibold">{{ $project->start_date->format('d M Y') }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">End Date</label>
                    <p class="font-semibold">{{ $project->end_date->format('d M Y') }}</p>
                </div>
            </div>
            <div class="mt-4">
                <label class="text-xs text-white-dark">Description</label>
                <p class="font-semibold">{{ $project->description ?: '—' }}</p>
            </div>
        </div>

        <div class="panel">
            <h5 class="mb-4 text-base font-semibold">Project Summary</h5>
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Total Sites</span>
                    <span class="text-sm font-bold dark:text-white">{{ $project->sites->count() }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Total Tasks</span>
                    <span class="text-sm font-bold dark:text-white">{{ $project->tasks->count() }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Resource Budget</span>
                    <span class="text-sm font-bold dark:text-white">৳{{ number_format($project->resources->sum('total_cost'), 0) }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Duration</span>
                    <span class="text-sm font-bold dark:text-white">{{ $project->start_date->diffInDays($project->end_date) }} days</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Created</span>
                    <span class="text-xs font-semibold dark:text-white">{{ $project->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <div class="panel">
            <div class="mb-4 flex items-center justify-between">
                <h5 class="text-base font-semibold">Phases &amp; Milestones</h5>
                <div class="flex gap-2">
                    <a href="{{ route('admin.core.projects.phases.index', $project) }}" class="btn btn-sm btn-outline-primary">Phases</a>
                    <a href="{{ route('admin.core.projects.milestones.index', $project) }}" class="btn btn-sm btn-outline-primary">Milestones</a>
                    <a href="{{ route('admin.core.projects.resources.index', $project) }}" class="btn btn-sm btn-outline-primary">Resources</a>
                </div>
            </div>

            @if($project->phases->isNotEmpty())
                <div class="space-y-4">
                    @foreach($project->phases as $phase)
                        <div class="rounded-lg border p-4 dark:border-gray-700">
                            <div class="mb-2 flex items-center justify-between">
                                <div>
                                    <span class="font-semibold">{{ $phase->name }}</span>
                                    @php $pColors = ['planned' => 'badge-outline-secondary', 'active' => 'badge-outline-success', 'completed' => 'badge-outline-primary', 'delayed' => 'badge-outline-danger']; @endphp
                                    <span class="badge {{ $pColors[$phase->status] ?? 'badge-outline-secondary' }} ml-2 text-xs">{{ $phase->status }}</span>
                                </div>
                                <span class="text-xs text-white-dark">{{ $phase->start_date?->format('d M') }} — {{ $phase->end_date?->format('d M Y') ?: '—' }}</span>
                            </div>
                            @if($phase->description)
                                <p class="mb-2 text-xs text-white-dark">{{ $phase->description }}</p>
                            @endif
                            @if($phase->milestones->isNotEmpty())
                                <div class="ml-4 space-y-1 border-l-2 border-primary pl-3">
                                    @foreach($phase->milestones as $ms)
                                        <div class="flex items-center gap-2 text-xs">
                                            @php $msColors = ['pending' => 'text-warning', 'achieved' => 'text-success', 'missed' => 'text-danger']; @endphp
                                            <span class="h-2 w-2 rounded-full {{ $msColors[$ms->status] ?? 'bg-gray-400' }}"></span>
                                            <span>{{ $ms->name }}</span>
                                            <span class="text-white-dark">— {{ $ms->target_date?->format('d M Y') ?: '—' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-white-dark">No phases defined yet.</p>
            @endif

            @if($project->milestones->whereNull('phase_id')->isNotEmpty())
                <div class="mt-4">
                    <h6 class="mb-2 text-sm font-semibold">Project-Level Milestones</h6>
                    <div class="space-y-1">
                        @foreach($project->milestones->whereNull('phase_id') as $ms)
                            <div class="flex items-center gap-2 text-sm">
                                @php $msColors = ['pending' => 'text-warning', 'achieved' => 'text-success', 'missed' => 'text-danger']; @endphp
                                <span class="h-2 w-2 rounded-full {{ $msColors[$ms->status] ?? 'bg-gray-400' }}"></span>
                                <span>{{ $ms->name }}</span>
                                <span class="text-xs text-white-dark">— {{ $ms->target_date?->format('d M Y') ?: '—' }}</span>
                                @php $msBadge = ['pending' => 'badge-outline-warning', 'achieved' => 'badge-outline-success', 'missed' => 'badge-outline-danger']; @endphp
                                <span class="badge {{ $msBadge[$ms->status] ?? 'badge-outline-secondary' }} text-xs">{{ $ms->status }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
