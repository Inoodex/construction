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
                                        'draft' => 'bg-gray-500',
                                        'submitted' => 'bg-blue-500',
                                        'under_review' => 'bg-cyan-500',
                                        'approved' => 'bg-green-600',
                                        'approved_with_conditions' => 'bg-lime-600',
                                        'rejected' => 'bg-red-600',
                                        'resubmitted' => 'bg-purple-500',
                                        default => 'bg-gray-500',
                                    };
                                @endphp
                                <span class="badge {{ $sc }} text-white px-2 py-0.5 rounded text-xs">{{ str_replace('_', ' ', ucfirst($s->status)) }}</span>
                            </td>
                            <td>
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.procurement.material-submittals.show', $s) }}" class="btn btn-sm btn-outline-info">View</a>
                                    @if(in_array($s->status, ['draft', 'resubmitted']))
                                        <a href="{{ route('admin.procurement.material-submittals.edit', $s) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    @endif
                                </div>
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
