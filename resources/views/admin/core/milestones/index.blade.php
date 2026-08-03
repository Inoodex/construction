@extends('admin.layouts.master')

@section('title', 'Milestones')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Milestones — {{ $project->name }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.core.projects.show', $project) }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to Project
            </a>
            <a href="{{ route('admin.core.projects.milestones.create', $project) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Add Milestone
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phase</th>
                            <th>Target Date</th>
                            <th>Achieved Date</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($milestones as $milestone)
                            <tr>
                                <td>
                                    <div class="font-semibold">{{ $milestone->name }}</div>
                                    <div class="text-xs text-white-dark">{{ Str::limit($milestone->description, 40) }}</div>
                                </td>
                                <td class="text-xs">{{ $milestone->phase?->name ?: '—' }}</td>
                                <td class="text-xs">{{ $milestone->target_date?->format('d M Y') ?: '—' }}</td>
                                <td class="text-xs">{{ $milestone->achieved_date?->format('d M Y') ?: '—' }}</td>
                                <td>
                                    @php $colors = ['pending' => 'badge-outline-warning', 'achieved' => 'badge-outline-success', 'missed' => 'badge-outline-danger']; @endphp
                                    <span class="badge {{ $colors[$milestone->status] ?? 'badge-outline-secondary' }} capitalize">{{ $milestone->status }}</span>
                                </td>
                                <td class="text-center">
                                    <x-action-dropdown>
                                        <x-slot name="items">
                                            <li><a href="{{ route('admin.core.projects.milestones.show', [$project, $milestone]) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                            <li><a href="{{ route('admin.core.projects.milestones.edit', [$project, $milestone]) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/></svg> Edit</a></li>
                                            <li class="divider"></li>
                                            <li class="danger">
                                                <form action="{{ route('admin.core.projects.milestones.destroy', [$project, $milestone]) }}" method="POST" onsubmit="return confirm('Delete this milestone?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 001-1h4a1 1 0 001 1m-6 0h6"/></svg> Delete</button>
                                                </form>
                                            </li>
                                        </x-slot>
                                    </x-action-dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No milestones found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $milestones->links() }}</div>
        </div>
    </div>
@endsection
