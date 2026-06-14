@extends('admin.layouts.master')

@section('title', 'All Milestones')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">All Milestones</h2>
    </div>

    <div class="panel mt-6">
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Project</th>
                            <th>Phase</th>
                            <th>Target Date</th>
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
                                <td class="text-xs">{{ $milestone->project->name ?? '—' }}</td>
                                <td class="text-xs">{{ $milestone->phase?->name ?: '—' }}</td>
                                <td class="text-xs">{{ $milestone->target_date?->format('d M Y') ?: '—' }}</td>
                                <td>
                                    @php $colors = ['pending' => 'badge-outline-warning', 'achieved' => 'badge-outline-success', 'missed' => 'badge-outline-danger']; @endphp
                                    <span class="badge {{ $colors[$milestone->status] ?? 'badge-outline-secondary' }} capitalize">{{ $milestone->status }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.core.projects.milestones.show', [$milestone->project, $milestone]) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.core.projects.milestones.edit', [$milestone->project, $milestone]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
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
