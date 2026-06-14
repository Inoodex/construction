@extends('admin.layouts.master')

@section('title', 'Site Logs')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Site Logs — {{ $site->name }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.core.sites.show', $site) }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to Site
            </a>
            <a href="{{ route('admin.core.sites.logs.create', $site) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Add Log
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Workers</th>
                            <th>Weather</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="text-xs">{{ $log->log_date->format('d M Y') }}</td>
                                <td>
                                    <div class="font-semibold">{{ $log->title }}</div>
                                    <div class="text-xs text-white-dark">{{ Str::limit($log->description, 40) }}</div>
                                </td>
                                <td>
                                    <span class="badge {{ $log->report_type == 'field_report' ? 'badge-outline-info' : 'badge-outline-secondary' }}">{{ str_replace('_', ' ', $log->report_type) }}</span>
                                </td>
                                <td class="text-xs">{{ $log->worker_count ?? '—' }}</td>
                                <td class="text-xs">{{ $log->weather_conditions ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ $log->status == 'submitted' ? 'badge-outline-success' : 'badge-outline-warning' }} capitalize">{{ $log->status }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.core.sites.logs.show', [$site, $log]) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.core.sites.logs.edit', [$site, $log]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.core.sites.logs.destroy', [$site, $log]) }}" method="POST" onsubmit="return confirm('Delete this log?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">No logs found for this site.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $logs->links() }}</div>
        </div>
    </div>
@endsection
