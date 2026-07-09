@extends('admin.layouts.master')

@section('title', 'Punch Lists')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Punch Lists (Snagging)</h2>
        <a href="{{ route('admin.quality.punch-lists.create') }}" class="btn btn-primary gap-2">+ New Punch List</a>
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
                <label class="text-xs font-semibold">Status</label>
                <select name="status" class="form-select" style="min-width: 140px">
                    <option value="">All</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['project_id', 'status']))
                    <a href="{{ route('admin.quality.punch-lists.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>PL#</th>
                        <th>Title</th>
                        <th>Project</th>
                        <th>Items</th>
                        <th>Completion</th>
                        <th>Inspection Date</th>
                        <th>Status</th>
                        <th style="text-align: center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $r)
                        <tr>
                            <td class="font-mono text-xs font-semibold text-primary">{{ $r->punch_list_number }}</td>
                            <td class="text-sm">{{ Str::limit($r->title, 30) }}</td>
                            <td class="text-sm">{{ $r->project->name ?? '—' }}</td>
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
                            <td class="text-sm">{{ $r->inspection_date->format('d M Y') }}</td>
                            <td>
                                @php
                                    $stCls = match($r->status) {
                                        'closed' => 'badge-outline-success',
                                        'completed' => 'badge-outline-primary',
                                        'in_progress' => 'badge-outline-warning',
                                        default => 'badge-outline-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $stCls }}">{{ str_replace('_', ' ', ucfirst($r->status)) }}</span>
                            </td>
                            <td class="flex gap-1" style="justify-content: center">
                                <a href="{{ route('admin.quality.punch-lists.show', $r) }}" class="btn btn-sm btn-outline-info">View</a>
                                <a href="{{ route('admin.quality.punch-lists.edit', $r) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form action="{{ route('admin.quality.punch-lists.destroy', $r) }}" method="POST" class="inline" onsubmit="return confirm('Delete this punch list?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-gray-400 py-4">No punch lists found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $records->links() }}</div>
    </div>
@endsection
