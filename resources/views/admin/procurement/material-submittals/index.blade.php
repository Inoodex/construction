@extends('admin.layouts.master')

@section('title', 'Material Submittals')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Material Submittals</h2>
        <div class="flex w-full flex-wrap items-center justify-end gap-4 sm:w-auto">
            <a href="{{ route('admin.procurement.material-submittals.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                New Submittal
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.procurement.material-submittals.index') }}" method="GET" class="flex items-center gap-3 w-full flex-wrap">
                <input type="text" name="search" class="form-input flex-1" placeholder="Search title, number, material..." value="{{ request('search') }}" />
                <select name="project_id" class="form-select flex-1">
                    <option value="">Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select flex-1">
                    <option value="">Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="approved_with_conditions" {{ request('status') == 'approved_with_conditions' ? 'selected' : '' }}>Approved w/ Conditions</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="resubmitted" {{ request('status') == 'resubmitted' ? 'selected' : '' }}>Resubmitted</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['search', 'project_id', 'status']))
                    <a href="{{ route('admin.procurement.material-submittals.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Material</th>
                        <th>Project</th>
                        <th>Submitted By</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submittals as $s)
                        <tr>
                            <td class="font-semibold">{{ $s->submittal_number }}</td>
                            <td>{{ $s->title }}</td>
                            <td>{{ $s->material_name }}</td>
                            <td>{{ $s->project?->name ?? '-' }}</td>
                            <td>{{ $s->submitter?->name ?? '-' }}</td>
                            <td>
                                @php
                                    $sc = match($s->status) {
                                        'draft' => 'badge-outline-secondary',
                                        'submitted' => 'badge-outline-info',
                                        'under_review' => 'badge-outline-warning',
                                        'approved' => 'badge-outline-success',
                                        'approved_with_conditions' => 'badge-outline-primary',
                                        'rejected' => 'badge-outline-danger',
                                        'resubmitted' => 'badge-outline-dark',
                                        default => 'badge-outline-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $sc }} capitalize">{{ str_replace('_', ' ', $s->status) }}</span>
                            </td>
                            <td class="text-center">
                                <x-action-dropdown>
                                    <x-slot name="items">
                                        <li><a href="{{ route('admin.procurement.material-submittals.show', $s) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                        <li><a href="{{ route('admin.procurement.material-submittals.pdf', $s->id) }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> PDF</a></li>
                                        @if(in_array($s->status, ['draft', 'resubmitted']))
                                        <li><a href="{{ route('admin.procurement.material-submittals.edit', $s) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/></svg> Edit</a></li>
                                        @endif
                                    </x-slot>
                                </x-action-dropdown>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-white-dark">No submittals found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $submittals->links() }}
        </div>
    </div>
@endsection
