@extends('admin.layouts.master')

@section('title', 'NCR - ' . $ncr->ncr_number)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">NCR: {{ $ncr->ncr_number }}</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.quality.ncrs.edit', $ncr) }}" class="btn btn-primary gap-2">Edit</a>
            <a href="{{ route('admin.quality.ncrs.index') }}" class="btn btn-secondary gap-2">&larr; Back to List</a>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-3 gap-4">
        <div class="panel">
            <h4 class="font-semibold mb-3">Details</h4>
            <table class="w-full text-sm">
                <tr><td class="py-1 text-gray-500 w-32">NCR Number</td><td class="font-mono font-semibold text-primary">{{ $ncr->ncr_number }}</td></tr>
                <tr><td class="py-1 text-gray-500">Title</td><td>{{ $ncr->title }}</td></tr>
                <tr><td class="py-1 text-gray-500">Project</td><td>{{ $ncr->project->name ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Category</td><td><span class="badge badge-outline-secondary">{{ ucfirst($ncr->category) }}</span></td></tr>
                <tr><td class="py-1 text-gray-500">Severity</td><td>
                    @php $sevCls = match($ncr->severity) { 'critical' => 'badge-outline-danger', 'major' => 'badge-outline-warning', default => 'badge-outline-success' }; @endphp
                    <span class="badge {{ $sevCls }}">{{ ucfirst($ncr->severity) }}</span>
                </td></tr>
                <tr><td class="py-1 text-gray-500">Status</td><td>
                    @php $stCls = match($ncr->status) { 'closed' => 'badge-outline-success', 'corrective_action' => 'badge-outline-warning', 'under_investigation' => 'badge-outline-info', default => 'badge-outline-secondary' }; @endphp
                    <span class="badge {{ $stCls }}">{{ str_replace('_', ' ', ucfirst($ncr->status)) }}</span>
                </td></tr>
            </table>
        </div>

        <div class="panel">
            <h4 class="font-semibold mb-3">People & Dates</h4>
            <table class="w-full text-sm">
                <tr><td class="py-1 text-gray-500 w-32">Identified By</td><td>{{ $ncr->identifier?->name ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Identified Date</td><td>{{ $ncr->identified_date->format('d M Y') }}</td></tr>
                <tr><td class="py-1 text-gray-500">Due Date</td><td>{{ $ncr->due_date?->format('d M Y') ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Closed Date</td><td>{{ $ncr->closed_date?->format('d M Y') ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Location</td><td>{{ $ncr->location ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Created By</td><td>{{ $ncr->creator?->name ?? '—' }}</td></tr>
            </table>
        </div>

        <div class="panel">
            <h4 class="font-semibold mb-3">Corrective Actions</h4>
            @if($ncr->correctiveActions->count() > 0)
                <div class="space-y-2">
                    @foreach($ncr->correctiveActions as $car)
                        <div class="rounded border p-2 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="font-mono text-xs font-semibold text-primary">{{ $car->car_number }}</span>
                                <span class="badge badge-outline-{{ $car->status === 'closed' ? 'success' : 'warning' }} text-xs">{{ str_replace('_', ' ', ucfirst($car->status)) }}</span>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">{{ Str::limit($car->title, 50) }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-400">No corrective actions linked.</p>
            @endif
        </div>
    </div>

    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Description</h4>
        <p class="text-sm whitespace-pre-wrap">{{ $ncr->description }}</p>
    </div>

    <div class="mt-4 grid grid-cols-3 gap-4">
        @if($ncr->root_cause)
        <div class="panel">
            <h4 class="font-semibold mb-3">Root Cause</h4>
            <p class="text-sm whitespace-pre-wrap">{{ $ncr->root_cause }}</p>
        </div>
        @endif
        @if($ncr->corrective_action)
        <div class="panel">
            <h4 class="font-semibold mb-3">Corrective Action</h4>
            <p class="text-sm whitespace-pre-wrap">{{ $ncr->corrective_action }}</p>
        </div>
        @endif
        @if($ncr->preventive_action)
        <div class="panel">
            <h4 class="font-semibold mb-3">Preventive Action</h4>
            <p class="text-sm whitespace-pre-wrap">{{ $ncr->preventive_action }}</p>
        </div>
        @endif
    </div>

    @if($ncr->notes)
    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Notes</h4>
        <p class="text-sm whitespace-pre-wrap">{{ $ncr->notes }}</p>
    </div>
    @endif
@endsection
