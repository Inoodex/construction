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
                                    <x-action-dropdown>
                                        <x-slot name="items">
                                            <li><a href="{{ route('admin.core.projects.phases.show', [$phase->project, $phase]) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                            <li><a href="{{ route('admin.core.projects.phases.edit', [$phase->project, $phase]) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/></svg> Edit</a></li>
                                        </x-slot>
                                    </x-action-dropdown>
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
