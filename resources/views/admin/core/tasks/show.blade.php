@extends('admin.layouts.master')

@section('title', 'Task Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Task Details</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.core.tasks.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to List
            </a>
            <a href="{{ route('admin.core.tasks.edit', $task->id) }}" class="btn btn-primary gap-2">
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
                    <label class="text-xs text-white-dark">Task Name</label>
                    <p class="font-semibold">{{ $task->name }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Status</label>
                    <div>
                        @php $sc = ['open' => 'badge-outline-secondary', 'in_progress' => 'badge-outline-warning', 'review' => 'badge-outline-info', 'closed' => 'badge-outline-success']; @endphp
                        <span class="badge {{ $sc[$task->status] ?? 'badge-outline-secondary' }} capitalize">{{ str_replace('_', ' ', $task->status) }}</span>
                    </div>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Priority</label>
                    <div>
                        @php $pc = ['critical' => 'badge-outline-danger', 'high' => 'badge-outline-warning', 'medium' => 'badge-outline-info', 'low' => 'badge-outline-success']; @endphp
                        <span class="badge {{ $pc[$task->priority] ?? 'badge-outline-secondary' }} capitalize">{{ $task->priority }}</span>
                    </div>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Progress</label>
                    <div class="flex items-center gap-2">
                        <div class="progress-bar-outer flex-1" style="height:6px;background:#e9ecef;border-radius:4px;overflow:hidden">
                            <div class="progress-bar-inner" style="width:{{ $task->progress_percent ?? 0 }}%;height:100%;border-radius:4px;background:#4361ee"></div>
                        </div>
                        <span class="text-sm font-bold">{{ $task->progress_percent ?? 0 }}%</span>
                    </div>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Project</label>
                    <p class="font-semibold">{{ $task->project->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Site</label>
                    <p class="font-semibold">{{ $task->site->name ?? '—' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Assignee</label>
                    <p class="font-semibold">{{ $task->assignee->name ?? 'Unassigned' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Start Date</label>
                    <p class="font-semibold">{{ $task->start_date?->format('d M Y') ?: '—' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">End Date</label>
                    <p class="font-semibold">{{ $task->end_date?->format('d M Y') ?: '—' }}</p>
                </div>
            </div>
            <div class="mt-4">
                <label class="text-xs text-white-dark">Description</label>
                <p class="font-semibold">{{ $task->description ?: '—' }}</p>
            </div>
        </div>

        <div class="panel">
            <h5 class="mb-4 text-base font-semibold">Task Relationships</h5>
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Depends On</span>
                    <span class="text-sm font-bold dark:text-white">{{ $task->dependencies->count() }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Blocks</span>
                    <span class="text-sm font-bold dark:text-white">{{ $task->dependentTasks->count() }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Created</span>
                    <span class="text-xs font-semibold dark:text-white">{{ $task->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
