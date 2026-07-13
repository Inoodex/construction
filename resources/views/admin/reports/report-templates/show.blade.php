@extends('admin.layouts.master')

@section('title', 'Template Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Report Template Details</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.reports.report-templates.preview', $reportTemplate) }}" class="btn btn-outline-success">Preview Report</a>
            <a href="{{ route('admin.reports.report-templates.edit', $reportTemplate->id) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit
            </a>
            <a href="{{ route('admin.reports.report-templates.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to List
            </a>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="panel lg:col-span-2">
            <h5 class="mb-4 text-base font-semibold">Template Information</h5>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="text-xs text-white-dark">Name</label>
                    <p class="font-semibold">{{ $reportTemplate->name }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Type</label>
                    <p><span class="badge bg-primary/10 text-primary capitalize">{{ $reportTemplate->report_type }}</span></p>
                </div>
                <div class="md:col-span-2">
                    <label class="text-xs text-white-dark">Description</label>
                    <p class="font-semibold">{{ $reportTemplate->description ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Created By</label>
                    <p class="font-semibold">{{ $reportTemplate->creator->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Created</label>
                    <p class="font-semibold">{{ $reportTemplate->created_at->format('d M Y h:i A') }}</p>
                </div>
            </div>
        </div>

        <div class="panel">
            <h5 class="mb-4 text-base font-semibold">Schedules</h5>
            <p class="text-xs text-white-dark">{{ $reportTemplate->scheduledReports->count() }} schedule(s)</p>
            @if($reportTemplate->scheduledReports->count())
                <ul class="mt-2 space-y-1">
                    @foreach($reportTemplate->scheduledReports as $s)
                        <li class="text-xs">{{ ucfirst($s->frequency) }} - {{ $s->status }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <div class="panel mt-6">
        <h5 class="mb-4 text-base font-semibold">Configuration</h5>
        <pre class="rounded-lg bg-gray-50 p-4 text-xs dark:bg-gray-800">{{ json_encode($reportTemplate->configuration, JSON_PRETTY_PRINT) }}</pre>
    </div>
@endsection
