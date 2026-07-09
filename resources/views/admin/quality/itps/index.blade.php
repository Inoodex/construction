@extends('admin.layouts.master')

@section('title', 'Inspection & Test Plans')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Inspection & Test Plans (ITP)</h2>
        <a href="{{ route('admin.quality.itps.create') }}" class="btn btn-primary gap-2">+ New ITP</a>
    </div>

    <div class="panel mt-6">
        <form method="GET" class="mb-4 flex flex-nowrap items-end gap-2 overflow-x-auto">
            <div>
                <label class="text-xs font-semibold">Project</label>
                <select name="project_id" class="form-select" style="min-width: 180px">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold">Phase</label>
                <select name="phase" class="form-select" style="min-width: 150px">
                    <option value="">All</option>
                    <option value="foundation" {{ request('phase') == 'foundation' ? 'selected' : '' }}>Foundation</option>
                    <option value="superstructure" {{ request('phase') == 'superstructure' ? 'selected' : '' }}>Superstructure</option>
                    <option value="finishing" {{ request('phase') == 'finishing' ? 'selected' : '' }}>Finishing</option>
                    <option value="mep" {{ request('phase') == 'mep' ? 'selected' : '' }}>MEP</option>
                    <option value="other" {{ request('phase') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold">Status</label>
                <select name="status" class="form-select" style="min-width: 130px">
                    <option value="">All</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['project_id', 'phase', 'status']))
                    <a href="{{ route('admin.quality.itps.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>ITP#</th>
                        <th>Title</th>
                        <th>Project</th>
                        <th>Phase</th>
                        <th>Items</th>
                        <th>Completion</th>
                        <th>Status</th>
                        <th style="text-align: center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $r)
                        <tr>
                            <td class="font-mono text-xs font-semibold text-primary">{{ $r->itp_number }}</td>
                            <td class="text-sm">{{ Str::limit($r->title, 30) }}</td>
                            <td class="text-sm">{{ $r->project->name ?? '—' }}</td>
                            <td><span class="badge badge-outline-secondary text-xs">{{ ucfirst($r->phase) }}</span></td>
                            <td class="text-sm font-semibold">{{ $r->items_count ?? $r->items()->count() }}</td>
                            <td>
                                @php $pct = $r->completion_percent; @endphp
                                <div class="flex items-center gap-2">
                                    <div class="progress-bar-outer w-20">
                                        <div class="progress-bar-inner {{ $pct >= 100 ? 'bg-success' : ($pct >= 50 ? 'bg-warning' : 'bg-primary') }}" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="text-xs font-semibold">{{ $pct }}%</span>
                                </div>
                            </td>
                            <td>
                                @php
                                    $stCls = match($r->status) {
                                        'completed' => 'badge-outline-success',
                                        'active' => 'badge-outline-primary',
                                        'archived' => 'badge-outline-secondary',
                                        default => 'badge-outline-warning',
                                    };
                                @endphp
                                <span class="badge {{ $stCls }}">{{ ucfirst($r->status) }}</span>
                            </td>
                            <td class="flex gap-1" style="justify-content: center">
                                <a href="{{ route('admin.quality.itps.show', $r) }}" class="btn btn-sm btn-outline-info">View</a>
                                <a href="{{ route('admin.quality.itps.edit', $r) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form action="{{ route('admin.quality.itps.destroy', $r) }}" method="POST" class="inline" onsubmit="return confirm('Delete this ITP?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-gray-400 py-4">No ITPs found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $records->links() }}</div>
    </div>
@endsection
