@extends('admin.layouts.master')

@section('title', 'Report Incident')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Report Incident</h2>
        <a href="{{ route('admin.hr.incident-reports.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
    </div>

    <div class="panel mt-6 max-w-3xl">
        <form action="{{ route('admin.hr.incident-reports.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">Incident Date <span class="text-danger">*</span></label>
                    <input type="date" name="incident_date" class="form-input" required value="{{ old('incident_date', date('Y-m-d')) }}" />
                    @error('incident_date') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Incident Time</label>
                    <input type="time" name="incident_time" class="form-input" value="{{ old('incident_time') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Incident Type <span class="text-danger">*</span></label>
                    <select name="incident_type" class="form-select" required>
                        <option value="">Select type</option>
                        <option value="accident" {{ old('incident_type') == 'accident' ? 'selected' : '' }}>Accident</option>
                        <option value="near-miss" {{ old('incident_type') == 'near-miss' ? 'selected' : '' }}>Near Miss</option>
                        <option value="injury" {{ old('incident_type') == 'injury' ? 'selected' : '' }}>Injury</option>
                        <option value="property-damage" {{ old('incident_type') == 'property-damage' ? 'selected' : '' }}>Property Damage</option>
                        <option value="fire" {{ old('incident_type') == 'fire' ? 'selected' : '' }}>Fire</option>
                        <option value="other" {{ old('incident_type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('incident_type') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Severity <span class="text-danger">*</span></label>
                    <select name="severity" class="form-select" required>
                        <option value="">Select severity</option>
                        <option value="minor" {{ old('severity') == 'minor' ? 'selected' : '' }}>Minor</option>
                        <option value="moderate" {{ old('severity') == 'moderate' ? 'selected' : '' }}>Moderate</option>
                        <option value="serious" {{ old('severity') == 'serious' ? 'selected' : '' }}>Serious</option>
                        <option value="critical" {{ old('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
                        <option value="fatal" {{ old('severity') == 'fatal' ? 'selected' : '' }}>Fatal</option>
                    </select>
                    @error('severity') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Location</label>
                    <input type="text" name="location" class="form-input" value="{{ old('location') }}" placeholder="Where it happened" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Affected Employee</label>
                    <select name="employee_id" class="form-select">
                        <option value="">None</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Reported By</label>
                    <input type="text" name="reported_by" class="form-input" value="{{ old('reported_by') }}" placeholder="Name of reporter" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Affected Persons</label>
                    <input type="text" name="affected_persons" class="form-input" value="{{ old('affected_persons') }}" placeholder="Names of others involved" />
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Description <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-textarea" rows="4" required>{{ old('description') }}</textarea>
                    @error('description') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Immediate Action Taken</label>
                    <textarea name="immediate_action" class="form-textarea" rows="3">{{ old('immediate_action') }}</textarea>
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Property Damage</label>
                    <textarea name="property_damage" class="form-textarea" rows="2">{{ old('property_damage') }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-semibold">Root Cause</label>
                    <textarea name="root_cause" class="form-textarea" rows="2">{{ old('root_cause') }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-semibold">Corrective Actions</label>
                    <textarea name="corrective_action" class="form-textarea" rows="2">{{ old('corrective_action') }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-semibold">Investigation Notes</label>
                    <textarea name="investigation_notes" class="form-textarea" rows="2">{{ old('investigation_notes') }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="open" {{ old('status', 'open') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="under-investigation" {{ old('status') == 'under-investigation' ? 'selected' : '' }}>Under Investigation</option>
                        <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Save Report</button>
        </form>
    </div>
@endsection
