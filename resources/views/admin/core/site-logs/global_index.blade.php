@extends('admin.layouts.master')

@section('title', 'All Site Logs')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">All Site Logs &amp; Field Reports</h2>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.core.site-logs.index') }}" method="GET" class="flex items-center gap-3 w-full">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search logs..." class="form-input ltr:pr-11 rtl:pl-11 w-full" />
                    <button type="submit" class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5" /><path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" /></svg>
                    </button>
                </div>
                <select name="site_id" class="form-select flex-1">
                    <option value="">All Sites</option>
                    @foreach($sites as $site)
                        <option value="{{ $site->id }}" {{ request('site_id') == $site->id ? 'selected' : '' }}>{{ $site->name }} ({{ $site->project->name ?? '—' }})</option>
                    @endforeach
                </select>
                <select name="report_type" class="form-select flex-1">
                    <option value="">All Types</option>
                    <option value="daily_log" {{ request('report_type') == 'daily_log' ? 'selected' : '' }}>Daily Log</option>
                    <option value="field_report" {{ request('report_type') == 'field_report' ? 'selected' : '' }}>Field Report</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['search', 'site_id', 'report_type']))
                    <a href="{{ route('admin.core.site-logs.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Title</th>
                            <th>Site</th>
                            <th>Type</th>
                            <th>Workers</th>
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
                                <td class="text-xs">{{ $log->site->name ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ $log->report_type == 'field_report' ? 'badge-outline-info' : 'badge-outline-secondary' }}">{{ str_replace('_', ' ', $log->report_type) }}</span>
                                </td>
                                <td class="text-xs">{{ $log->worker_count ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ $log->status == 'submitted' ? 'badge-outline-success' : 'badge-outline-warning' }} capitalize">{{ $log->status }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.core.sites.logs.show', [$log->site, $log]) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.core.sites.logs.edit', [$log->site, $log]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">No logs found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $logs->links() }}</div>
        </div>
    </div>
@endsection
