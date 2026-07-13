@extends('admin.layouts.master')

@section('title', 'Permits to Work')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Permits to Work</h2>
        <a href="{{ route('admin.hr.permits-to-work.create') }}" class="btn btn-primary gap-2">+ New Permit</a>
    </div>

    <div class="panel mt-6">
        <form method="GET" class="mb-4 flex flex-nowrap items-end gap-2 overflow-x-auto">
            <div>
                <label class="text-xs font-semibold">Permit Type</label>
                <select name="permit_type" class="form-select" style="min-width: 150px">
                    <option value="">All Types</option>
                    <option value="hot_work" {{ request('permit_type') == 'hot_work' ? 'selected' : '' }}>Hot Work</option>
                    <option value="confined_space" {{ request('permit_type') == 'confined_space' ? 'selected' : '' }}>Confined Space</option>
                    <option value="working_at_height" {{ request('permit_type') == 'working_at_height' ? 'selected' : '' }}>Working at Height</option>
                    <option value="electrical" {{ request('permit_type') == 'electrical' ? 'selected' : '' }}>Electrical</option>
                    <option value="excavation" {{ request('permit_type') == 'excavation' ? 'selected' : '' }}>Excavation</option>
                    <option value="lifting" {{ request('permit_type') == 'lifting' ? 'selected' : '' }}>Lifting</option>
                    <option value="radiography" {{ request('permit_type') == 'radiography' ? 'selected' : '' }}>Radiography</option>
                    <option value="other" {{ request('permit_type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold">Status</label>
                <select name="status" class="form-select" style="min-width: 150px">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="pending_approval" {{ request('status') == 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold">Project</label>
                <select name="project_id" class="form-select" style="min-width: 150px">
                    <option value="">All Projects</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['permit_type', 'status', 'project_id']))
                    <a href="{{ route('admin.hr.permits-to-work.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Permit #</th>
                        <th>Type</th>
                        <th>Project</th>
                        <th>Location</th>
                        <th>Requester</th>
                        <th>Valid From</th>
                        <th>Valid Until</th>
                        <th>Status</th>
                        <th style="text-align: center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permits as $p)
                        <tr>
                            <td class="font-semibold">{{ $p->permit_number }}</td>
                            <td>
                                @php
                                    $typeBadge = match($p->permit_type) {
                                        'hot_work' => 'badge-outline-danger',
                                        'confined_space' => 'badge-outline-warning',
                                        'working_at_height' => 'badge-outline-info',
                                        'electrical' => 'badge-outline-secondary',
                                        'excavation' => 'badge-outline-success',
                                        'lifting' => 'badge-outline-info',
                                        'radiography' => 'badge-outline-warning',
                                        default => 'badge-outline-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $typeBadge }}">{{ str_replace('_', ' ', ucfirst($p->permit_type)) }}</span>
                            </td>
                            <td>{{ $p->project?->name ?? '—' }}</td>
                            <td>{{ $p->work_location }}</td>
                            <td>{{ $p->requester?->name ?? '—' }}</td>
                            <td>{{ $p->valid_from->format('d M Y') }}</td>
                            <td>{{ $p->valid_until->format('d M Y') }}</td>
                            <td>
                                @php
                                    $stCls = match($p->status) {
                                        'draft' => 'badge-outline-secondary',
                                        'pending_approval' => 'badge-outline-warning',
                                        'approved' => 'badge-outline-success',
                                        'active' => 'badge-outline-success',
                                        'completed' => 'badge-outline-info',
                                        'cancelled' => 'badge-outline-danger',
                                        default => 'badge-outline-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $stCls }}">{{ str_replace('_', ' ', ucfirst($p->status)) }}</span>
                            </td>
                            <td class="flex gap-1" style="justify-content: center">
                                <a href="{{ route('admin.hr.permits-to-work.show', $p) }}" class="btn btn-sm btn-outline-info">View</a>
                                <a href="{{ route('admin.hr.permits-to-work.edit', $p) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form action="{{ route('admin.hr.permits-to-work.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Delete this permit?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-gray-400 py-4">No permits to work found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $permits->links() }}</div>
    </div>
@endsection
