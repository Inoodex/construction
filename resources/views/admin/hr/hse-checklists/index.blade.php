@extends('admin.layouts.master')

@section('title', 'HSE Checklists')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">HSE Checklists</h2>
        <a href="{{ route('admin.hr.hse-checklists.create') }}" class="btn btn-primary gap-2">+ New HSE Checklist</a>
    </div>

    <div class="panel mt-6">
        <form method="GET" class="mb-4 flex flex-nowrap items-end gap-2 overflow-x-auto">
            <div>
                <label class="text-xs font-semibold">Type</label>
                <select name="checklist_type" class="form-select" style="min-width: 150px">
                    <option value="">All Types</option>
                    <option value="general" {{ request('checklist_type') == 'general' ? 'selected' : '' }}>General</option>
                    <option value="fire" {{ request('checklist_type') == 'fire' ? 'selected' : '' }}>Fire Safety</option>
                    <option value="electrical" {{ request('checklist_type') == 'electrical' ? 'selected' : '' }}>Electrical</option>
                    <option value="scaffolding" {{ request('checklist_type') == 'scaffolding' ? 'selected' : '' }}>Scaffolding</option>
                    <option value="ppe" {{ request('checklist_type') == 'ppe' ? 'selected' : '' }}>PPE Compliance</option>
                    <option value="excavation" {{ request('checklist_type') == 'excavation' ? 'selected' : '' }}>Excavation</option>
                    <option value="other" {{ request('checklist_type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold">Status</label>
                <select name="status" class="form-select" style="min-width: 150px">
                    <option value="">All Status</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['checklist_type', 'status']))
                    <a href="{{ route('admin.hr.hse-checklists.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Inspector</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $r)
                        <tr>
                            <td>{{ $r->title }}</td>
                            <td><span class="badge badge-outline-{{ $r->checklist_type === 'fire' ? 'danger' : ($r->checklist_type === 'ppe' ? 'success' : 'info') }}">{{ str_replace('-', ' ', ucfirst($r->checklist_type)) }}</span></td>
                            <td>{{ $r->location ?? '—' }}</td>
                            <td>{{ $r->employee?->full_name ?? '—' }}</td>
                            <td>{{ $r->inspection_date->format('d M Y') }}</td>
                            <td>
                                <span class="badge {{ $r->status === 'closed' ? 'badge-outline-success' : 'badge-outline-warning' }}">{{ ucfirst($r->status) }}</span>
                            </td>
                            <td class="flex items-center gap-1">
                                <a href="{{ route('admin.hr.hse-checklists.show', $r) }}" class="btn btn-sm btn-outline-info">View</a>
                                <a href="{{ route('admin.hr.hse-checklists.edit', $r) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form action="{{ route('admin.hr.hse-checklists.destroy', $r) }}" method="POST" class="inline-flex" onsubmit="return confirm('Delete this checklist?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-gray-400 py-4">No HSE checklists found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $records->links() }}</div>
    </div>
@endsection
