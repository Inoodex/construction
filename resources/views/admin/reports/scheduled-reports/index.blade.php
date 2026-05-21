@extends('admin.layouts.master')

@section('title', 'Scheduled Reports')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Scheduled Reports</h2>
        <a href="{{ route('admin.reports.scheduled-reports.create') }}" class="btn btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            New Schedule
        </a>
    </div>

    <div class="panel mt-6">
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Template</th>
                            <th>Frequency</th>
                            <th>Next Run</th>
                            <th>Last Run</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $s)
                            <tr>
                                <td class="font-semibold">{{ $s->template->name ?? 'N/A' }}</td>
                                <td class="capitalize text-xs">{{ $s->frequency }}</td>
                                <td class="text-xs">{{ $s->next_run_at->format('d M Y h:i A') }}</td>
                                <td class="text-xs">{{ $s->last_run_at ? $s->last_run_at->format('d M Y h:i A') : '-' }}</td>
                                <td>
                                    @if($s->status === 'active')
                                        <span class="badge bg-success/10 text-success">Active</span>
                                    @else
                                        <span class="badge bg-danger/10 text-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.reports.scheduled-reports.show', $s->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.reports.scheduled-reports.edit', $s->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                        <form action="{{ route('admin.reports.scheduled-reports.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Delete this schedule?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">No scheduled reports found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $schedules->links() }}</div>
        </div>
    </div>
@endsection
