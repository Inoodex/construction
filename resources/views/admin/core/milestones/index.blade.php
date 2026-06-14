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
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.core.projects.milestones.show', [$project, $milestone]) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.core.projects.milestones.edit', [$project, $milestone]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.core.projects.milestones.destroy', [$project, $milestone]) }}" method="POST" onsubmit="return confirm('Delete this milestone?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
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
