@extends('admin.layouts.master')

@section('title', 'Site Log Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">{{ $log->title }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.core.sites.logs.index', $site) }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to Logs
            </a>
            <a href="{{ route('admin.core.sites.logs.edit', [$site, $log]) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit
            </a>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="panel lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h5 class="text-base font-semibold">Log Information</h5>
                <span class="badge {{ $log->report_type == 'field_report' ? 'badge-outline-info' : 'badge-outline-secondary' }}">{{ str_replace('_', ' ', strtoupper($log->report_type)) }}</span>
            </div>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="text-xs text-white-dark">Site</label>
                    <p class="font-semibold">{{ $site->name }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Date</label>
                    <p class="font-semibold">{{ $log->log_date->format('d M Y') }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Submitted By</label>
                    <p class="font-semibold">{{ $log->submitter->name ?? '—' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Status</label>
                    <span class="badge {{ $log->status == 'submitted' ? 'badge-outline-success' : 'badge-outline-warning' }} capitalize">{{ $log->status }}</span>
                </div>
            </div>

            @if($log->weather_conditions || $log->temperature || $log->worker_count)
                <hr class="my-4 border-white-light dark:border-gray-700">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    @if($log->weather_conditions)
                        <div>
                            <label class="text-xs text-white-dark">Weather</label>
                            <p class="font-semibold">{{ $log->weather_conditions }}</p>
                        </div>
                    @endif
                    @if($log->temperature)
                        <div>
                            <label class="text-xs text-white-dark">Temperature</label>
                            <p class="font-semibold">{{ $log->temperature }} °C</p>
                        </div>
                    @endif
                    @if($log->worker_count)
                        <div>
                            <label class="text-xs text-white-dark">Workers on Site</label>
                            <p class="font-semibold">{{ $log->worker_count }}</p>
                        </div>
                    @endif
                </div>
            @endif

            @if($log->description)
                <hr class="my-4 border-white-light dark:border-gray-700">
                <div>
                    <label class="text-xs text-white-dark">Description</label>
                    <p class="mt-1">{{ $log->description }}</p>
                </div>
            @endif

            @if($log->work_completed)
                <hr class="my-4 border-white-light dark:border-gray-700">
                <div>
                    <label class="text-xs text-white-dark">Work Completed</label>
                    <p class="mt-1 whitespace-pre-wrap">{{ $log->work_completed }}</p>
                </div>
            @endif

            @if($log->equipment_used)
                <hr class="my-4 border-white-light dark:border-gray-700">
                <div>
                    <label class="text-xs text-white-dark">Equipment Used</label>
                    <p class="mt-1 whitespace-pre-wrap">{{ $log->equipment_used }}</p>
                </div>
            @endif

            @if($log->materials_received)
                <hr class="my-4 border-white-light dark:border-gray-700">
                <div>
                    <label class="text-xs text-white-dark">Materials Received</label>
                    <p class="mt-1 whitespace-pre-wrap">{{ $log->materials_received }}</p>
                </div>
            @endif

            @if($log->issues_notes)
                <hr class="my-4 border-white-light dark:border-gray-700">
                <div>
                    <label class="text-xs text-white-dark">Issues / Notes</label>
                    <p class="mt-1 whitespace-pre-wrap">{{ $log->issues_notes }}</p>
                </div>
            @endif
        </div>

        <div class="panel">
            <h5 class="mb-4 text-base font-semibold">Summary</h5>
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs">Type</span>
                    <span class="text-xs font-semibold capitalize">{{ str_replace('_', ' ', $log->report_type) }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs">Workers</span>
                    <span class="text-xs font-semibold">{{ $log->worker_count ?? '—' }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs">Weather</span>
                    <span class="text-xs font-semibold">{{ $log->weather_conditions ?: '—' }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs">Temperature</span>
                    <span class="text-xs font-semibold">{{ $log->temperature ? $log->temperature.'°C' : '—' }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs">Created</span>
                    <span class="text-xs font-semibold">{{ $log->created_at->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
