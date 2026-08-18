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
                            <td class="text-center">
                                <x-action-dropdown>
                                    <x-slot name="items">
                                        <li><a href="{{ route('admin.hr.permits-to-work.show', $p) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                        <li><a href="{{ route('admin.hr.permits-to-work.edit', $p) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/></svg> Edit</a></li>
                                        <li class="divider"></li>
                                        <li class="danger">
                                            <form action="{{ route('admin.hr.permits-to-work.destroy', $p) }}" method="POST" onsubmit="return confirm('Delete this permit?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 001-1h4a1 1 0 001 1m-6 0h6"/></svg> Delete</button>
                                            </form>
                                        </li>
                                    </x-slot>
                                </x-action-dropdown>
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
