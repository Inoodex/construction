@extends('admin.layouts.master')

@section('title', 'Wastage Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Material Wastage Details</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.procurement.material-wastages.edit', $materialWastage->id) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('admin.procurement.material-wastages.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to List
            </a>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="panel lg:col-span-2">
            <h5 class="mb-4 text-base font-semibold">Wastage Information</h5>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="text-xs text-white-dark">Project</label>
                    <p class="font-semibold">{{ $materialWastage->project->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Site</label>
                    <p class="font-semibold">{{ $materialWastage->site->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Material</label>
                    <p class="font-semibold">{{ $materialWastage->material->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Quantity</label>
                    <p class="font-semibold">{{ number_format($materialWastage->quantity, 2) }} {{ $materialWastage->material->unit ?? '' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Reported Date</label>
                    <p class="font-semibold">{{ $materialWastage->reported_date->format('d M Y') }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Reported By</label>
                    <p class="font-semibold">{{ $materialWastage->reporter->name ?? 'N/A' }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="text-xs text-white-dark">Reason</label>
                    <p class="font-semibold">{{ $materialWastage->reason }}</p>
                </div>
            </div>
        </div>

        <div class="panel">
            <h5 class="mb-4 text-base font-semibold">Summary</h5>
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Created</span>
                    <span class="text-xs font-semibold dark:text-white">{{ $materialWastage->created_at->format('d M Y h:i A') }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Last Updated</span>
                    <span class="text-xs font-semibold dark:text-white">{{ $materialWastage->updated_at->format('d M Y h:i A') }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
