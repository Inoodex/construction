@extends('admin.layouts.master')

@section('title', 'Safety Audits')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Safety Audits</h2>
        <a href="{{ route('admin.hr.safety-audits.create') }}" class="btn btn-primary gap-2">+ New Audit</a>
    </div>

    <div class="panel mt-6">
        <form method="GET" class="mb-4 flex flex-nowrap items-end gap-2 overflow-x-auto">
            <div>
                <label class="text-xs font-semibold">Audit Type</label>
                <select name="audit_type" class="form-select" style="min-width: 150px">
                    <option value="">All Types</option>
                    <option value="internal" {{ request('audit_type') == 'internal' ? 'selected' : '' }}>Internal</option>
                    <option value="external" {{ request('audit_type') == 'external' ? 'selected' : '' }}>External</option>
                    <option value="regulatory" {{ request('audit_type') == 'regulatory' ? 'selected' : '' }}>Regulatory</option>
                    <option value="client" {{ request('audit_type') == 'client' ? 'selected' : '' }}>Client</option>
                    <option value="other" {{ request('audit_type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold">Status</label>
                <select name="status" class="form-select" style="min-width: 150px">
                    <option value="">All Status</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="follow_up" {{ request('status') == 'follow_up' ? 'selected' : '' }}>Follow Up</option>
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
                @if(request()->anyFilled(['audit_type', 'status', 'project_id']))
                    <a href="{{ route('admin.hr.safety-audits.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Audit #</th>
                        <th>Type</th>
                        <th>Project</th>
                        <th>Auditor</th>
                        <th>Date</th>
                        <th>Score</th>
                        <th>Status</th>
                        <th style="text-align: center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($audits as $a)
                        <tr>
                            <td class="font-semibold">{{ $a->audit_number }}</td>
                            <td>
                                @php
                                    $typeBadge = match($a->audit_type) {
                                        'internal' => 'badge-outline-info',
                                        'external' => 'badge-outline-success',
                                        'regulatory' => 'badge-outline-warning',
                                        'client' => 'badge-outline-secondary',
                                        default => 'badge-outline-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $typeBadge }}">{{ ucfirst($a->audit_type) }}</span>
                            </td>
                            <td>{{ $a->project?->name ?? '—' }}</td>
                            <td>{{ $a->auditor?->name ?? '—' }}</td>
                            <td>{{ $a->audit_date->format('d M Y') }}</td>
                            <td>
                                @if($a->score !== null)
                                    @php
                                        $scoreColor = $a->score >= 80 ? 'text-success' : ($a->score >= 60 ? 'text-warning' : 'text-danger');
                                    @endphp
                                    <span class="font-bold {{ $scoreColor }}">{{ $a->score }}%</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $stCls = match($a->status) {
                                        'scheduled' => 'badge-outline-secondary',
                                        'in_progress' => 'badge-outline-warning',
                                        'completed' => 'badge-outline-success',
                                        'follow_up' => 'badge-outline-info',
                                        default => 'badge-outline-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $stCls }}">{{ str_replace('_', ' ', ucfirst($a->status)) }}</span>
                            </td>
                            <td class="text-center">
                                <x-action-dropdown>
                                    <x-slot name="items">
                                        <li><a href="{{ route('admin.hr.safety-audits.show', $a) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                        <li><a href="{{ route('admin.hr.safety-audits.edit', $a) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/></svg> Edit</a></li>
                                        <li class="divider"></li>
                                        <li class="danger">
                                            <form action="{{ route('admin.hr.safety-audits.destroy', $a) }}" method="POST" onsubmit="return confirm('Delete this audit?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 001-1h4a1 1 0 001 1m-6 0h6"/></svg> Delete</button>
                                            </form>
                                        </li>
                                    </x-slot>
                                </x-action-dropdown>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-gray-400 py-4">No safety audits found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $audits->links() }}</div>
    </div>
@endsection
