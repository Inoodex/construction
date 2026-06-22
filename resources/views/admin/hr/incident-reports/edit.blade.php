@extends('admin.layouts.master')

@section('title', 'Edit Incident Report')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Incident Report</h2>
        <a href="{{ route('admin.hr.incident-reports.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.hr.incident-reports.update', $incidentReport) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">Incident Date <span class="text-danger">*</span></label>
                    <input type="date" name="incident_date" class="form-input" required value="{{ old('incident_date', $incidentReport->incident_date?->format('Y-m-d')) }}" />
                    @error('incident_date') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Incident Time</label>
                    <input type="time" name="incident_time" class="form-input" value="{{ old('incident_time', $incidentReport->incident_time?->format('H:i')) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Incident Type <span class="text-danger">*</span></label>
                    <select name="incident_type" class="form-select" required>
                        <option value="">Select type</option>
                        <option value="accident" {{ old('incident_type', $incidentReport->incident_type) == 'accident' ? 'selected' : '' }}>Accident</option>
                        <option value="near-miss" {{ old('incident_type', $incidentReport->incident_type) == 'near-miss' ? 'selected' : '' }}>Near Miss</option>
                        <option value="injury" {{ old('incident_type', $incidentReport->incident_type) == 'injury' ? 'selected' : '' }}>Injury</option>
                        <option value="property-damage" {{ old('incident_type', $incidentReport->incident_type) == 'property-damage' ? 'selected' : '' }}>Property Damage</option>
                        <option value="fire" {{ old('incident_type', $incidentReport->incident_type) == 'fire' ? 'selected' : '' }}>Fire</option>
                        <option value="other" {{ old('incident_type', $incidentReport->incident_type) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Severity <span class="text-danger">*</span></label>
                    <select name="severity" class="form-select" required>
                        <option value="">Select severity</option>
                        <option value="minor" {{ old('severity', $incidentReport->severity) == 'minor' ? 'selected' : '' }}>Minor</option>
                        <option value="moderate" {{ old('severity', $incidentReport->severity) == 'moderate' ? 'selected' : '' }}>Moderate</option>
                        <option value="serious" {{ old('severity', $incidentReport->severity) == 'serious' ? 'selected' : '' }}>Serious</option>
                        <option value="critical" {{ old('severity', $incidentReport->severity) == 'critical' ? 'selected' : '' }}>Critical</option>
                        <option value="fatal" {{ old('severity', $incidentReport->severity) == 'fatal' ? 'selected' : '' }}>Fatal</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Location</label>
                    <input type="text" name="location" class="form-input" value="{{ old('location', $incidentReport->location) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Affected Employee</label>
                    <select name="employee_id" class="form-select">
                        <option value="">None</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id', $incidentReport->employee_id) == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Reported By</label>
                    <input type="text" name="reported_by" class="form-input" value="{{ old('reported_by', $incidentReport->reported_by) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Affected Persons</label>
                    <input type="text" name="affected_persons" class="form-input" value="{{ old('affected_persons', $incidentReport->affected_persons) }}" />
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Description <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-textarea" rows="4" required>{{ old('description', $incidentReport->description) }}</textarea>
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Immediate Action Taken</label>
                    <textarea name="immediate_action" class="form-textarea" rows="3">{{ old('immediate_action', $incidentReport->immediate_action) }}</textarea>
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Property Damage</label>
                    <textarea name="property_damage" class="form-textarea" rows="2">{{ old('property_damage', $incidentReport->property_damage) }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-semibold">Root Cause</label>
                    <textarea name="root_cause" class="form-textarea" rows="2">{{ old('root_cause', $incidentReport->root_cause) }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-semibold">Corrective Actions</label>
                    <textarea name="corrective_action" class="form-textarea" rows="2">{{ old('corrective_action', $incidentReport->corrective_action) }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-semibold">Investigation Notes</label>
                    <textarea name="investigation_notes" class="form-textarea" rows="2">{{ old('investigation_notes', $incidentReport->investigation_notes) }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="open" {{ old('status', $incidentReport->status) == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="under-investigation" {{ old('status', $incidentReport->status) == 'under-investigation' ? 'selected' : '' }}>Under Investigation</option>
                        <option value="closed" {{ old('status', $incidentReport->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Update Report</button>
        </form>
    </div>
@endsection
