@extends('admin.layouts.master')

@section('title', 'Projects')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Projects</h2>
        @unless(auth()->user()?->hasRole('client'))
            <div class="flex w-full flex-wrap items-center justify-end gap-4 sm:w-auto">
                <a href="{{ route('admin.core.projects.create') }}" class="btn btn-primary gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Add Project
                </a>
            </div>
        @endunless
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.core.projects.index') }}" method="GET" class="flex items-center gap-3 w-full">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search projects..." class="form-input ltr:pr-11 rtl:pl-11 w-full" />
                    <button type="submit" class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5" />
                            <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <select name="status" class="form-select flex-1">
                    <option value="">Status</option>
                    <option value="planning" {{ request('status') == 'planning' ? 'selected' : '' }}>Planning</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="on_hold" {{ request('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('admin.core.projects.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Budget</th>
                            <th>Timeline</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th>Client</th>
                            <th>Created By</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr>
                                <td>
                                    <div class="font-semibold">{{ $project->name }}</div>
                                    <div class="text-xs text-white-dark">{{ Str::limit($project->description, 50) }}</div>
                                </td>
                                <td class="font-semibold">৳{{ number_format($project->budget / 1000000, 1) }}M</td>
                                <td class="text-xs">
                                    <div>{{ $project->start_date->format('d M Y') }}</div>
                                    <div class="text-white-dark">{{ $project->end_date->format('d M Y') }}</div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="h-1.5 w-16 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                            @php $progColor = $project->progress >= 100 ? 'bg-success' : ($project->progress >= 50 ? 'bg-primary' : ($project->progress >= 25 ? 'bg-warning' : 'bg-danger')); @endphp
                                            <div class="h-full rounded-full {{ $progColor }}" style="width: {{ $project->progress }}%"></div>
                                        </div>
                                        <span class="text-xs font-semibold">{{ $project->progress }}%</span>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $colors = ['planning' => 'badge-outline-warning', 'active' => 'badge-outline-success', 'on_hold' => 'badge-outline-danger', 'completed' => 'badge-outline-primary'];
                                    @endphp
                                    <span class="badge {{ $colors[$project->status] ?? 'badge-outline-secondary' }} capitalize">{{ str_replace('_', ' ', $project->status) }}</span>
                                </td>
                                <td class="text-xs">{{ $project->client->company_name ?? '—' }}</td>
                                <td class="text-xs">{{ $project->creator->name ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <x-action-dropdown>
                                        <x-slot name="items">
                                            <li><a href="{{ route('admin.core.projects.show', $project->id) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                            @unless(filled(auth()->user()?->client_id))
                                            <li><a href="{{ route('admin.core.projects.edit', $project->id) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/></svg> Edit</a></li>
                                            <li class="divider"></li>
                                            <li class="danger">
                                                <form action="{{ route('admin.core.projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Delete this project?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 001-1h4a1 1 0 001 1m-6 0h6"/></svg> Delete</button>
                                                </form>
                                            </li>
                                            @endunless
                                        </x-slot>
                                    </x-action-dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No projects found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $projects->links() }}
            </div>
        </div>
    </div>
@endsection
