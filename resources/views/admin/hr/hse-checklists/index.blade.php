@extends('admin.layouts.master')

@section('title', 'HSE Checklists')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">HSE Checklists</h5>
        <a href="{{ route('admin.hr.hse-checklists.create') }}" class="btn btn-primary">+ New HSE Checklist</a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-100 p-3 text-green-700">{{ session('success') }}</div>
    @endif

    <form method="GET" class="mb-4 flex flex-nowrap items-center gap-2 overflow-x-auto">
        <select name="checklist_type" class="form-select" onchange="this.form.submit()">
            <option value="">All Types</option>
            <option value="general" {{ request('checklist_type') == 'general' ? 'selected' : '' }}>General</option>
            <option value="fire" {{ request('checklist_type') == 'fire' ? 'selected' : '' }}>Fire Safety</option>
            <option value="electrical" {{ request('checklist_type') == 'electrical' ? 'selected' : '' }}>Electrical</option>
            <option value="scaffolding" {{ request('checklist_type') == 'scaffolding' ? 'selected' : '' }}>Scaffolding</option>
            <option value="ppe" {{ request('checklist_type') == 'ppe' ? 'selected' : '' }}>PPE Compliance</option>
            <option value="excavation" {{ request('checklist_type') == 'excavation' ? 'selected' : '' }}>Excavation</option>
            <option value="other" {{ request('checklist_type') == 'other' ? 'selected' : '' }}>Other</option>
        </select>
        <select name="status" class="form-select" onchange="this.form.submit()">
            <option value="">All Status</option>
            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
        </select>
        @if(request()->anyFilled(['checklist_type', 'status']))
            <a href="{{ route('admin.hr.hse-checklists.index') }}" class="btn btn-outline-danger btn-sm">Reset</a>
        @endif
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
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $r)
                    <tr>
                        <td class="font-semibold">{{ $r->title }}</td>
                        <td><span class="badge badge-outline-{{ $r->checklist_type === 'fire' ? 'danger' : ($r->checklist_type === 'ppe' ? 'success' : 'info') }}">{{ str_replace('-', ' ', ucfirst($r->checklist_type)) }}</span></td>
                        <td class="text-xs">{{ $r->location ?? '—' }}</td>
                        <td class="text-xs">{{ $r->employee?->full_name ?? '—' }}</td>
                        <td class="text-xs">{{ $r->inspection_date->format('d M Y') }}</td>
                        <td>
                            <span class="badge {{ $r->status === 'closed' ? 'badge-outline-success' : 'badge-outline-warning' }}">{{ ucfirst($r->status) }}</span>
                        </td>
                        <td class="flex gap-1">
                            <a href="{{ route('admin.hr.hse-checklists.show', $r) }}" class="btn btn-xs btn-outline-info">View</a>
                            <a href="{{ route('admin.hr.hse-checklists.edit', $r) }}" class="btn btn-xs btn-outline-secondary">Edit</a>
                            <form action="{{ route('admin.hr.hse-checklists.destroy', $r) }}" method="POST" class="inline" onsubmit="return confirm('Delete this checklist?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-outline-danger">×</button>
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
