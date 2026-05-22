@extends('admin.layouts.master')

@section('title', 'Tasks')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Tasks &amp; Work Orders</h2>
        <div class="flex w-full flex-wrap items-center justify-end gap-4 sm:w-auto">
            <a href="{{ route('admin.core.tasks.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add Task
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.core.tasks.index') }}" method="GET" class="flex items-center gap-3 w-full">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tasks..." class="form-input ltr:pr-11 rtl:pl-11 w-full" />
                    <button type="submit" class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5" />
                            <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <select name="project_id" class="form-select flex-1">
                    <option value="">Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select flex-1">
                    <option value="">Status</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>Review</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                <select name="priority" class="form-select flex-1">
                    <option value="">Priority</option>
                    <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['search', 'project_id', 'status', 'priority']))
                    <a href="{{ route('admin.core.tasks.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Project</th>
                            <th>Assignee</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Progress</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            <tr>
                                <td>
                                    <div class="font-semibold">{{ $task->name }}</div>
                                    <div class="text-xs text-white-dark">{{ Str::limit($task->description, 40) }}</div>
                                </td>
                                <td class="text-xs">{{ $task->project->name ?? 'N/A' }}</td>
                                <td class="text-xs">{{ $task->assignee->name ?? '—' }}</td>
                                <td>
                                    @php
                                        $priorityColors = ['critical' => 'badge-outline-danger', 'high' => 'badge-outline-warning', 'medium' => 'badge-outline-info', 'low' => 'badge-outline-success'];
                                    @endphp
                                    <span class="badge {{ $priorityColors[$task->priority] ?? 'badge-outline-secondary' }} capitalize">{{ $task->priority }}</span>
                                </td>
                                <td>
                                    @php
                                        $statusColors = ['open' => 'badge-outline-secondary', 'in_progress' => 'badge-outline-warning', 'review' => 'badge-outline-info', 'closed' => 'badge-outline-success'];
                                    @endphp
                                    <span class="badge {{ $statusColors[$task->status] ?? 'badge-outline-secondary' }} capitalize">{{ str_replace('_', ' ', $task->status) }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="progress-bar-outer flex-1" style="height:5px;background:#e9ecef;border-radius:4px;overflow:hidden">
                                            <div class="progress-bar-inner" style="width:{{ $task->progress_percent ?? 0 }}%;height:100%;border-radius:4px;background:#4361ee;transition:width 0.6s ease"></div>
                                        </div>
                                        <span class="text-xs font-semibold">{{ $task->progress_percent ?? 0 }}%</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.core.tasks.show', $task->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.core.tasks.edit', $task->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.core.tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Delete this task?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No tasks found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $tasks->links() }}
            </div>
        </div>
    </div>
@endsection
