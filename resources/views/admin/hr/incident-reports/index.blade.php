@extends('admin.layouts.master')

@section('title', 'Incident Reports')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Incident Reports</h2>
        <a href="{{ route('admin.hr.incident-reports.create') }}" class="btn btn-primary gap-2">+ Report Incident</a>
    </div>

    <div class="panel mt-6">
        <form method="GET" class="mb-4 flex flex-nowrap items-end gap-2 overflow-x-auto">
            <div>
                <label class="text-xs font-semibold">Type</label>
                <select name="incident_type" class="form-select" style="min-width: 150px">
                    <option value="">All Types</option>
                    <option value="accident" {{ request('incident_type') == 'accident' ? 'selected' : '' }}>Accident</option>
                    <option value="near-miss" {{ request('incident_type') == 'near-miss' ? 'selected' : '' }}>Near Miss</option>
                    <option value="injury" {{ request('incident_type') == 'injury' ? 'selected' : '' }}>Injury</option>
                    <option value="property-damage" {{ request('incident_type') == 'property-damage' ? 'selected' : '' }}>Property Damage</option>
                    <option value="fire" {{ request('incident_type') == 'fire' ? 'selected' : '' }}>Fire</option>
                    <option value="other" {{ request('incident_type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold">Severity</label>
                <select name="severity" class="form-select" style="min-width: 150px">
                    <option value="">All Severity</option>
                    <option value="minor" {{ request('severity') == 'minor' ? 'selected' : '' }}>Minor</option>
                    <option value="moderate" {{ request('severity') == 'moderate' ? 'selected' : '' }}>Moderate</option>
                    <option value="serious" {{ request('severity') == 'serious' ? 'selected' : '' }}>Serious</option>
                    <option value="critical" {{ request('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
                    <option value="fatal" {{ request('severity') == 'fatal' ? 'selected' : '' }}>Fatal</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="under-investigation" {{ request('status') == 'under-investigation' ? 'selected' : '' }}>Under Investigation</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['incident_type', 'severity', 'status']))
                    <a href="{{ route('admin.hr.incident-reports.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </div>
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
                    <th style="text-align: center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $r)
                    <tr>
                        <td>{{ $r->incident_date->format('d M Y') }}{{ $r->incident_time ? ' ' . $r->incident_time->format('H:i') : '' }}</td>
                        <td><span class="badge badge-outline-{{ $r->incident_type === 'accident' ? 'danger' : ($r->incident_type === 'near-miss' ? 'warning' : ($r->incident_type === 'injury' ? 'info' : 'secondary')) }}">{{ str_replace('-', ' ', ucfirst($r->incident_type)) }}</span></td>
                        <td>
                            @php
                                $sevCls = match($r->severity) {
                                    'fatal' => 'badge-outline-danger',
                                    'critical' => 'badge-outline-warning',
                                    'serious' => 'badge-outline-secondary',
                                    'moderate' => 'badge-outline-info',
                                    default => 'badge-outline-success'
                                };
                            @endphp
                            <span class="badge {{ $sevCls }}">{{ ucfirst($r->severity) }}</span>
                        </td>
                        <td>{{ $r->location ?? '—' }}</td>
                        <td>{{ $r->employee?->full_name ?? '—' }}</td>
                        <td>{{ $r->reported_by ?? '—' }}</td>
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
                        <td class="flex gap-1" style="justify-content: center">
                            <a href="{{ route('admin.hr.incident-reports.show', $r) }}" class="btn btn-sm btn-outline-info">View</a>
                            <a href="{{ route('admin.hr.incident-reports.edit', $r) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="{{ route('admin.hr.incident-reports.destroy', $r) }}" method="POST" class="inline" onsubmit="return confirm('Delete this report?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
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
