@extends('admin.layouts.master')

@section('title', 'Incident Report - ' . $incidentReport->incident_date->format('d M Y'))

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Incident Report</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.hr.incident-reports.edit', $incidentReport) }}" class="btn btn-outline-secondary">Edit</a>
            <a href="{{ route('admin.hr.incident-reports.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-3 gap-4">
        <div class="panel">
            <h4 class="font-semibold mb-3">Details</h4>
            <table class="w-full text-sm">
                <tr><td class="py-1 text-gray-500 w-32">Date / Time</td><td>{{ $incidentReport->incident_date->format('d M Y') }}{{ $incidentReport->incident_time ? ' at ' . $incidentReport->incident_time->format('H:i') : '' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Type</td><td><span class="badge badge-outline-{{ $incidentReport->incident_type === 'accident' ? 'danger' : ($incidentReport->incident_type === 'near-miss' ? 'warning' : ($incidentReport->incident_type === 'injury' ? 'info' : 'secondary')) }}">{{ str_replace('-', ' ', ucfirst($incidentReport->incident_type)) }}</span></td></tr>
                <tr><td class="py-1 text-gray-500">Severity</td><td>
                    @php $sevCls = match($incidentReport->severity) { 'fatal' => 'badge-danger', 'critical' => 'badge-warning', 'serious' => 'badge-secondary', 'moderate' => 'badge-info', default => 'badge-success' }; @endphp
                    <span class="badge {{ $sevCls }}">{{ ucfirst($incidentReport->severity) }}</span>
                </td></tr>
                <tr><td class="py-1 text-gray-500">Location</td><td>{{ $incidentReport->location ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Status</td><td>
                    @php $stCls = match($incidentReport->status) { 'closed' => 'badge-outline-success', 'under-investigation' => 'badge-outline-warning', default => 'badge-outline-secondary' }; @endphp
                    <span class="badge {{ $stCls }}">{{ str_replace('-', ' ', ucfirst($incidentReport->status)) }}</span>
                </td></tr>
            </table>
        </div>

        <div class="panel">
            <h4 class="font-semibold mb-3">People</h4>
            <table class="w-full text-sm">
                <tr><td class="py-1 text-gray-500 w-32">Affected Employee</td><td>{{ $incidentReport->employee?->full_name ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Reported By</td><td>{{ $incidentReport->reported_by ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Other Affected</td><td>{{ $incidentReport->affected_persons ?? '—' }}</td></tr>
            </table>
        </div>

        <div class="panel">
            <h4 class="font-semibold mb-3">Property Damage</h4>
            <p class="text-sm">{{ $incidentReport->property_damage ?? 'None reported' }}</p>
        </div>
    </div>

    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Description</h4>
        <p class="text-sm whitespace-pre-wrap">{{ $incidentReport->description }}</p>
    </div>

    @if($incidentReport->immediate_action)
    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Immediate Action Taken</h4>
        <p class="text-sm whitespace-pre-wrap">{{ $incidentReport->immediate_action }}</p>
    </div>
    @endif

    <div class="mt-4 grid grid-cols-2 gap-4">
        @if($incidentReport->root_cause)
        <div class="panel">
            <h4 class="font-semibold mb-3">Root Cause</h4>
            <p class="text-sm whitespace-pre-wrap">{{ $incidentReport->root_cause }}</p>
        </div>
        @endif
        @if($incidentReport->corrective_action)
        <div class="panel">
            <h4 class="font-semibold mb-3">Corrective Actions</h4>
            <p class="text-sm whitespace-pre-wrap">{{ $incidentReport->corrective_action }}</p>
        </div>
        @endif
    </div>

    @if($incidentReport->investigation_notes)
    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Investigation Notes</h4>
        <p class="text-sm whitespace-pre-wrap">{{ $incidentReport->investigation_notes }}</p>
    </div>
    @endif
@endsection
