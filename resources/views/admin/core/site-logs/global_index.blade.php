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
                                    <x-action-dropdown>
                                        <x-slot name="items">
                                            <li><a href="{{ route('admin.core.sites.logs.show', [$log->site, $log]) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                            <li><a href="{{ route('admin.core.sites.logs.edit', [$log->site, $log]) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/></svg> Edit</a></li>
                                        </x-slot>
                                    </x-action-dropdown>
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
