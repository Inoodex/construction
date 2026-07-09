@extends('admin.layouts.master')

@section('title', 'CAR - ' . $correctiveAction->car_number)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">CAR: {{ $correctiveAction->car_number }}</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.quality.corrective-actions.edit', $correctiveAction) }}" class="btn btn-primary gap-2">Edit</a>
            <a href="{{ route('admin.quality.corrective-actions.index') }}" class="btn btn-secondary gap-2">&larr; Back to List</a>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-3 gap-4">
        <div class="panel">
            <h4 class="font-semibold mb-3">Details</h4>
            <table class="w-full text-sm">
                <tr><td class="py-1 text-gray-500 w-32">CAR Number</td><td class="font-mono font-semibold text-primary">{{ $correctiveAction->car_number }}</td></tr>
                <tr><td class="py-1 text-gray-500">Title</td><td>{{ $correctiveAction->title }}</td></tr>
                <tr><td class="py-1 text-gray-500">Project</td><td>{{ $correctiveAction->project->name ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Status</td><td>
                    @php $stCls = match($correctiveAction->status) { 'closed' => 'badge-outline-success', 'verified' => 'badge-outline-primary', 'completed' => 'badge-outline-info', 'in_progress' => 'badge-outline-warning', default => 'badge-outline-secondary' }; @endphp
                    <span class="badge {{ $stCls }}">{{ str_replace('_', ' ', ucfirst($correctiveAction->status)) }}</span>
                </td></tr>
            </table>
        </div>

        <div class="panel">
            <h4 class="font-semibold mb-3">People & Dates</h4>
            <table class="w-full text-sm">
                <tr><td class="py-1 text-gray-500 w-32">Responsible</td><td>{{ $correctiveAction->responsible_person ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Target Date</td><td>{{ $correctiveAction->target_date?->format('d M Y') ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Completed</td><td>{{ $correctiveAction->completed_date?->format('d M Y') ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Verified By</td><td>{{ $correctiveAction->verifier?->name ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Verified Date</td><td>{{ $correctiveAction->verified_date?->format('d M Y') ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Created By</td><td>{{ $correctiveAction->creator?->name ?? '—' }}</td></tr>
            </table>
        </div>

        <div class="panel">
            <h4 class="font-semibold mb-3">Source</h4>
            @if($correctiveAction->ncr)
                <div class="mb-2 rounded border p-2">
                    <span class="badge badge-outline-warning text-xs">NCR</span>
                    <a href="{{ route('admin.quality.ncrs.show', $correctiveAction->ncr) }}" class="ml-2 text-xs text-primary hover:underline">{{ $correctiveAction->ncr->ncr_number }}</a>
                    <p class="text-xs text-gray-500 mt-1">{{ Str::limit($correctiveAction->ncr->title, 50) }}</p>
                </div>
            @endif
            @if($correctiveAction->punchListItem)
                <div class="mb-2 rounded border p-2">
                    <span class="badge badge-outline-info text-xs">Punch List Item</span>
                    <p class="text-xs text-gray-500 mt-1">{{ Str::limit($correctiveAction->punchListItem->description, 50) }}</p>
                </div>
            @endif
            @if(!$correctiveAction->ncr && !$correctiveAction->punchListItem)
                <p class="text-sm text-gray-400">Standalone CAR (no linked source)</p>
            @endif
        </div>
    </div>

    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Description</h4>
        <p class="text-sm whitespace-pre-wrap">{{ $correctiveAction->description }}</p>
    </div>

    <div class="mt-4 grid grid-cols-3 gap-4">
        @if($correctiveAction->root_cause)
        <div class="panel">
            <h4 class="font-semibold mb-3">Root Cause</h4>
            <p class="text-sm whitespace-pre-wrap">{{ $correctiveAction->root_cause }}</p>
        </div>
        @endif
        @if($correctiveAction->corrective_action)
        <div class="panel">
            <h4 class="font-semibold mb-3">Corrective Action</h4>
            <p class="text-sm whitespace-pre-wrap">{{ $correctiveAction->corrective_action }}</p>
        </div>
        @endif
        @if($correctiveAction->preventive_action)
        <div class="panel">
            <h4 class="font-semibold mb-3">Preventive Action</h4>
            <p class="text-sm whitespace-pre-wrap">{{ $correctiveAction->preventive_action }}</p>
        </div>
        @endif
    </div>

    @if($correctiveAction->effectiveness_check)
    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Effectiveness Check</h4>
        <p class="text-sm whitespace-pre-wrap">{{ $correctiveAction->effectiveness_check }}</p>
    </div>
    @endif

    @if($correctiveAction->notes)
    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Notes</h4>
        <p class="text-sm whitespace-pre-wrap">{{ $correctiveAction->notes }}</p>
    </div>
    @endif
@endsection
