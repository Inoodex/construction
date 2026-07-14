@extends('admin.layouts.master')

@section('title', 'Material Issue Slips')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Material Issue Slips</h2>
        <div class="flex w-full flex-wrap items-center justify-end gap-4 sm:w-auto">
            <a href="{{ route('admin.procurement.material-issue-slips.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                New Issue
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.procurement.material-issue-slips.index') }}" method="GET" class="flex items-center gap-3 w-full">
                <select name="project_id" class="form-select flex-1">
                    <option value="">Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['project_id']))
                    <a href="{{ route('admin.procurement.material-issue-slips.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Issue #</th>
                            <th>Project</th>
                            <th>Site</th>
                            <th>Issued To</th>
                            <th>Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($slips as $slip)
                            <tr>
                                <td><span class="font-mono text-xs font-semibold text-primary">{{ $slip->issue_number }}</span></td>
                                <td class="text-xs">{{ $slip->project->name ?? 'N/A' }}</td>
                                <td class="text-xs">{{ $slip->site->name ?? 'N/A' }}</td>
                                <td class="text-xs">{{ $slip->recipient->name ?? 'N/A' }}</td>
                                <td class="text-xs">{{ $slip->issue_date->format('d M Y') }}</td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.procurement.material-issue-slips.show', $slip->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.procurement.material-issue-slips.pdf', $slip->id) }}" target="_blank" class="btn btn-sm btn-outline-success">PDF</a>
                                        <form action="{{ route('admin.procurement.material-issue-slips.destroy', $slip->id) }}" method="POST" onsubmit="return confirm('Delete this issue slip?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No issue slips found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $slips->links() }}
            </div>
        </div>
    </div>
@endsection
