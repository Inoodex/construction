@extends('admin.layouts.master')

@section('title', 'Report Templates')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Report Templates</h2>
        <a href="{{ route('admin.reports.report-templates.create') }}" class="btn btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            New Template
        </a>
    </div>

    <div class="panel mt-6">
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Created By</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $t)
                            <tr>
                                <td class="font-semibold">{{ $t->name }}</td>
                                <td><span class="badge bg-primary/10 text-primary capitalize">{{ $t->report_type }}</span></td>
                                <td class="text-xs max-w-[250px] truncate">{{ $t->description ?? '-' }}</td>
                                <td class="text-xs">{{ $t->creator->name ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.reports.report-templates.show', $t->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.reports.report-templates.edit', $t->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                        <form action="{{ route('admin.reports.report-templates.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Delete this template?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">No report templates found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $templates->links() }}</div>
        </div>
    </div>
@endsection
