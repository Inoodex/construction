@extends('admin.layouts.master')

@section('title', 'Incident Reports')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Incident Reports</h5>
        <a href="{{ route('admin.hr.incident-reports.create') }}" class="btn btn-primary">+ Report Incident</a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-100 p-3 text-green-700">{{ session('success') }}</div>
    @endif

    <form method="GET" class="mb-4 flex flex-nowrap items-center gap-2 overflow-x-auto">
        <select name="incident_type" class="form-select" onchange="this.form.submit()">
            <option value="">All Types</option>
            <option value="accident" {{ request('incident_type') == 'accident' ? 'selected' : '' }}>Accident</option>
            <option value="near-miss" {{ request('incident_type') == 'near-miss' ? 'selected' : '' }}>Near Miss</option>
            <option value="injury" {{ request('incident_type') == 'injury' ? 'selected' : '' }}>Injury</option>
            <option value="property-damage" {{ request('incident_type') == 'property-damage' ? 'selected' : '' }}>Property Damage</option>
            <option value="fire" {{ request('incident_type') == 'fire' ? 'selected' : '' }}>Fire</option>
            <option value="other" {{ request('incident_type') == 'other' ? 'selected' : '' }}>Other</option>
        </select>
        <select name="severity" class="form-select" onchange="this.form.submit()">
            <option value="">All Severity</option>
            <option value="minor" {{ request('severity') == 'minor' ? 'selected' : '' }}>Minor</option>
            <option value="moderate" {{ request('severity') == 'moderate' ? 'selected' : '' }}>Moderate</option>
            <option value="serious" {{ request('severity') == 'serious' ? 'selected' : '' }}>Serious</option>
            <option value="critical" {{ request('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
            <option value="fatal" {{ request('severity') == 'fatal' ? 'selected' : '' }}>Fatal</option>
        </select>
        <select name="status" class="form-select" onchange="this.form.submit()">
            <option value="">All Status</option>
            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
            <option value="under-investigation" {{ request('status') == 'under-investigation' ? 'selected' : '' }}>Under Investigation</option>
            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
        </select>
        @if(request()->anyFilled(['incident_type', 'severity', 'status']))
            <a href="{{ route('admin.hr.incident-reports.index') }}" class="btn btn-outline-danger btn-sm">Reset</a>
        @endif
    </form>

    <div class="overflow-x-auto">
        <table class="table-hover w-full table-auto">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Severity</th>
                    <th>Location</th>
                    <th>Employee</th>
                    <th>Reported By</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $r)
                    <tr>
                        <td class="text-xs whitespace-nowrap">{{ $r->incident_date->format('d M Y') }}{{ $r->incident_time ? ' ' . $r->incident_time->format('H:i') : '' }}</td>
                        <td><span class="badge badge-outline-{{ $r->incident_type === 'accident' ? 'danger' : ($r->incident_type === 'near-miss' ? 'warning' : ($r->incident_type === 'injury' ? 'info' : 'secondary')) }}">{{ str_replace('-', ' ', ucfirst($r->incident_type)) }}</span></td>
                        <td>
                            @php
                                $sevCls = match($r->severity) {
                                    'fatal' => 'badge-danger',
                                    'critical' => 'badge-warning',
                                    'serious' => 'badge-secondary',
                                    'moderate' => 'badge-info',
                                    default => 'badge-success'
                                };
                            @endphp
                            <span class="badge {{ $sevCls }}">{{ ucfirst($r->severity) }}</span>
                        </td>
                        <td class="text-xs">{{ $r->location ?? '—' }}</td>
                        <td>{{ $r->employee?->full_name ?? '—' }}</td>
                        <td class="text-xs">{{ $r->reported_by ?? '—' }}</td>
                        <td>
                            @php
                                $stCls = match($r->status) {
                                    'closed' => 'badge-outline-success',
                                    'under-investigation' => 'badge-outline-warning',
                                    default => 'badge-outline-secondary'
                                };
                            @endphp
                            <span class="badge {{ $stCls }}">{{ str_replace('-', ' ', ucfirst($r->status)) }}</span>
                        </td>
                        <td class="flex gap-1">
                            <a href="{{ route('admin.hr.incident-reports.show', $r) }}" class="btn btn-xs btn-outline-info">View</a>
                            <a href="{{ route('admin.hr.incident-reports.edit', $r) }}" class="btn btn-xs btn-outline-secondary">Edit</a>
                            <form action="{{ route('admin.hr.incident-reports.destroy', $r) }}" method="POST" class="inline" onsubmit="return confirm('Delete this report?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-outline-danger">×</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-gray-400 py-4">No incident reports found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $records->links() }}</div>
</div>
@endsection
