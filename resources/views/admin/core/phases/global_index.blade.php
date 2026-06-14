@extends('admin.layouts.master')

@section('title', 'All Phases')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">All Phases</h2>
    </div>

    <div class="panel mt-6">
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Project</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($phases as $phase)
                            <tr>
                                <td class="text-xs">{{ $phase->order_index }}</td>
                                <td>
                                    <div class="font-semibold">{{ $phase->name }}</div>
                                    <div class="text-xs text-white-dark">{{ Str::limit($phase->description, 40) }}</div>
                                </td>
                                <td class="text-xs">{{ $phase->project->name ?? '—' }}</td>
                                <td>
                                    @php $colors = ['planned' => 'badge-outline-secondary', 'active' => 'badge-outline-success', 'completed' => 'badge-outline-primary', 'delayed' => 'badge-outline-danger']; @endphp
                                    <span class="badge {{ $colors[$phase->status] ?? 'badge-outline-secondary' }} capitalize">{{ $phase->status }}</span>
                                </td>
                                <td class="text-xs">{{ $phase->start_date?->format('d M Y') ?: '—' }}</td>
                                <td class="text-xs">{{ $phase->end_date?->format('d M Y') ?: '—' }}</td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.core.projects.phases.show', [$phase->project, $phase]) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.core.projects.phases.edit', [$phase->project, $phase]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No phases found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $phases->links() }}</div>
        </div>
    </div>
@endsection
