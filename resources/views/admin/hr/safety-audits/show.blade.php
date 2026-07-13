@extends('admin.layouts.master')

@section('title', 'Safety Audit - ' . $safetyAudit->audit_number)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Safety Audit</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.hr.safety-audits.edit', $safetyAudit) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit
            </a>
            <a href="{{ route('admin.hr.safety-audits.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to List
            </a>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-3 gap-4">
        <div class="panel">
            <h4 class="font-semibold mb-3">Details</h4>
            <table class="w-full text-sm">
                <tr><td class="py-1 text-gray-500 w-36">Audit Number</td><td class="font-bold">{{ $safetyAudit->audit_number }}</td></tr>
                <tr><td class="py-1 text-gray-500">Type</td><td>
                    @php
                        $typeBadge = match($safetyAudit->audit_type) {
                            'internal' => 'badge-outline-info',
                            'external' => 'badge-outline-success',
                            'regulatory' => 'badge-outline-warning',
                            'client' => 'badge-outline-secondary',
                            default => 'badge-outline-secondary'
                        };
                    @endphp
                    <span class="badge {{ $typeBadge }}">{{ ucfirst($safetyAudit->audit_type) }}</span>
                </td></tr>
                <tr><td class="py-1 text-gray-500">Project</td><td>{{ $safetyAudit->project?->name ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Site</td><td>{{ $safetyAudit->site?->name ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Scope</td><td>{{ $safetyAudit->scope }}</td></tr>
                <tr><td class="py-1 text-gray-500">Audit Date</td><td>{{ $safetyAudit->audit_date->format('d M Y') }}</td></tr>
                <tr><td class="py-1 text-gray-500">Status</td><td>
                    @php
                        $stCls = match($safetyAudit->status) {
                            'scheduled' => 'badge-outline-secondary',
                            'in_progress' => 'badge-outline-warning',
                            'completed' => 'badge-outline-success',
                            'follow_up' => 'badge-outline-info',
                            default => 'badge-outline-secondary'
                        };
                    @endphp
                    <span class="badge {{ $stCls }}">{{ str_replace('_', ' ', ucfirst($safetyAudit->status)) }}</span>
                </td></tr>
            </table>
        </div>

        <div class="panel">
            <h4 class="font-semibold mb-3">Auditor</h4>
            <table class="w-full text-sm">
                <tr><td class="py-1 text-gray-500 w-36">Name</td><td>{{ $safetyAudit->auditor?->name ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Created</td><td>{{ $safetyAudit->created_at->format('d M Y H:i') }}</td></tr>
            </table>
        </div>

        <div class="panel">
            <h4 class="font-semibold mb-3">Score</h4>
            @if($safetyAudit->score !== null)
                @php
                    $scoreColor = $safetyAudit->score >= 80 ? 'success' : ($safetyAudit->score >= 60 ? 'warning' : 'danger');
                @endphp
                <div class="flex items-center gap-4">
                    <div class="relative h-24 w-24">
                        <svg class="h-24 w-24 -rotate-90" viewBox="0 0 36 36">
                            <path class="text-gray-200 dark:text-white/10" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-{{ $scoreColor }}" stroke-width="3" fill="none" stroke-dasharray="{{ $safetyAudit->score }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-lg font-bold text-{{ $scoreColor }}">{{ $safetyAudit->score }}%</span>
                        </div>
                    </div>
                    <div>
                        @if($safetyAudit->score >= 80)
                            <p class="text-success font-semibold">Good Standing</p>
                        @elseif($safetyAudit->score >= 60)
                            <p class="text-warning font-semibold">Needs Improvement</p>
                        @else
                            <p class="text-danger font-semibold">Requires Action</p>
                        @endif
                    </div>
                </div>
            @else
                <p class="text-sm text-gray-400">No score recorded</p>
            @endif
        </div>
    </div>

    @if($safetyAudit->findings)
    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Findings</h4>
        <p class="text-sm whitespace-pre-wrap">{{ $safetyAudit->findings }}</p>
    </div>
    @endif

    @if($safetyAudit->non_conformances)
    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Non-Conformances</h4>
        <p class="text-sm whitespace-pre-wrap text-danger">{{ $safetyAudit->non_conformances }}</p>
    </div>
    @endif

    @if($safetyAudit->recommendations)
    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Recommendations</h4>
        <p class="text-sm whitespace-pre-wrap">{{ $safetyAudit->recommendations }}</p>
    </div>
    @endif

    @if($safetyAudit->notes)
    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Notes</h4>
        <p class="text-sm whitespace-pre-wrap">{{ $safetyAudit->notes }}</p>
    </div>
    @endif
@endsection
