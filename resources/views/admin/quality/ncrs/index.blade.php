@extends('admin.layouts.master')

@section('title', 'Non-Conformance Reports')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Non-Conformance Reports (NCR)</h2>
        <a href="{{ route('admin.quality.ncrs.create') }}" class="btn btn-primary gap-2">+ New NCR</a>
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
                <label class="text-xs font-semibold">Severity</label>
                <select name="severity" class="form-select" style="min-width: 130px">
                    <option value="">All</option>
                    <option value="minor" {{ request('severity') == 'minor' ? 'selected' : '' }}>Minor</option>
                    <option value="major" {{ request('severity') == 'major' ? 'selected' : '' }}>Major</option>
                    <option value="critical" {{ request('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold">Status</label>
                <select name="status" class="form-select" style="min-width: 150px">
                    <option value="">All</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="under_investigation" {{ request('status') == 'under_investigation' ? 'selected' : '' }}>Under Investigation</option>
                    <option value="corrective_action" {{ request('status') == 'corrective_action' ? 'selected' : '' }}>Corrective Action</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold">Category</label>
                <select name="category" class="form-select" style="min-width: 140px">
                    <option value="">All</option>
                    <option value="structural" {{ request('category') == 'structural' ? 'selected' : '' }}>Structural</option>
                    <option value="material" {{ request('category') == 'material' ? 'selected' : '' }}>Material</option>
                    <option value="workmanship" {{ request('category') == 'workmanship' ? 'selected' : '' }}>Workmanship</option>
                    <option value="safety" {{ request('category') == 'safety' ? 'selected' : '' }}>Safety</option>
                    <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['project_id', 'severity', 'status', 'category']))
                    <a href="{{ route('admin.quality.ncrs.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>NCR#</th>
                        <th>Title</th>
                        <th>Project</th>
                        <th>Category</th>
                        <th>Severity</th>
                        <th>Identified</th>
                        <th>Due</th>
                        <th>Status</th>
                        <th style="text-align: center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $r)
                        <tr>
                            <td class="font-mono text-xs font-semibold text-primary">{{ $r->ncr_number }}</td>
                            <td class="text-sm">{{ Str::limit($r->title, 30) }}</td>
                            <td class="text-sm">{{ $r->project->name ?? '—' }}</td>
                            <td><span class="badge badge-outline-secondary text-xs">{{ ucfirst($r->category) }}</span></td>
                            <td>
                                @php
                                    $sevCls = match($r->severity) {
                                        'critical' => 'badge-outline-danger',
                                        'major' => 'badge-outline-warning',
                                        default => 'badge-outline-success',
                                    };
                                @endphp
                                <span class="badge {{ $sevCls }}">{{ ucfirst($r->severity) }}</span>
                            </td>
                            <td class="text-sm">{{ $r->identified_date->format('d M Y') }}</td>
                            <td class="text-sm">{{ $r->due_date?->format('d M Y') ?? '—' }}</td>
                            <td>
                                @php
                                    $stCls = match($r->status) {
                                        'closed' => 'badge-outline-success',
                                        'corrective_action' => 'badge-outline-warning',
                                        'under_investigation' => 'badge-outline-info',
                                        default => 'badge-outline-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $stCls }}">{{ str_replace('_', ' ', ucfirst($r->status)) }}</span>
                            </td>
                            <td class="flex gap-1" style="justify-content: center">
                                <a href="{{ route('admin.quality.ncrs.show', $r) }}" class="btn btn-sm btn-outline-info">View</a>
                                <a href="{{ route('admin.quality.ncrs.edit', $r) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form action="{{ route('admin.quality.ncrs.destroy', $r) }}" method="POST" class="inline" onsubmit="return confirm('Delete this NCR?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-gray-400 py-4">No NCRs found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $records->links() }}</div>
    </div>
@endsection
