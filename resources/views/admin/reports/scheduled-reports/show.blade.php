@extends('admin.layouts.master')

@section('title', 'Schedule Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Scheduled Report Details</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.reports.scheduled-reports.edit', $scheduledReport->id) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('admin.reports.scheduled-reports.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to List
            </a>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="panel lg:col-span-2">
            <h5 class="mb-4 text-base font-semibold">Schedule Information</h5>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="text-xs text-white-dark">Template</label>
                    <p class="font-semibold">{{ $scheduledReport->template->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Frequency</label>
                    <p class="capitalize font-semibold">{{ $scheduledReport->frequency }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Next Run</label>
                    <p class="font-semibold">{{ $scheduledReport->next_run_at->format('d M Y h:i A') }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Last Run</label>
                    <p class="font-semibold">{{ $scheduledReport->last_run_at ? $scheduledReport->last_run_at->format('d M Y h:i A') : 'Never' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Status</label>
                    <p>
                        @if($scheduledReport->status === 'active')
                            <span class="badge bg-success/10 text-success">Active</span>
                        @else
                            <span class="badge bg-danger/10 text-danger">Inactive</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="panel">
            <h5 class="mb-4 text-base font-semibold">Recipients</h5>
            <ul class="space-y-1">
                @foreach($scheduledReport->recipients as $email)
                    <li class="text-xs">{{ $email }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
