@extends('admin.layouts.master')

@section('title', 'Edit Site Log')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Log — {{ $site->name }}</h2>
        <a href="{{ route('admin.core.sites.logs.index', $site) }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to Logs
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.core.sites.logs.update', [$site, $log]) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group md:col-span-2">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-input" required value="{{ old('title', $log->title) }}" />
                    @error('title') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="report_type">Report Type <span class="text-danger">*</span></label>
                    <select name="report_type" id="report_type" class="form-select" required>
                        <option value="daily_log" {{ old('report_type', $log->report_type) == 'daily_log' ? 'selected' : '' }}>Daily Log</option>
                        <option value="field_report" {{ old('report_type', $log->report_type) == 'field_report' ? 'selected' : '' }}>Field Report</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="log_date">Log Date <span class="text-danger">*</span></label>
                    <input type="date" name="log_date" id="log_date" class="form-input" required value="{{ old('log_date', $log->log_date?->format('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="weather_conditions">Weather Conditions</label>
                    <input type="text" name="weather_conditions" id="weather_conditions" class="form-input" value="{{ old('weather_conditions', $log->weather_conditions) }}" />
                </div>
                <div class="form-group">
                    <label for="temperature">Temperature (°C)</label>
                    <input type="number" name="temperature" id="temperature" class="form-input" step="0.1" min="-50" max="60" value="{{ old('temperature', $log->temperature) }}" />
                </div>
                <div class="form-group">
                    <label for="worker_count">Worker Count</label>
                    <input type="number" name="worker_count" id="worker_count" class="form-input" min="0" value="{{ old('worker_count', $log->worker_count) }}" />
                </div>
                <div class="form-group md:col-span-2">
                    <label for="work_completed">Work Completed</label>
                    <textarea name="work_completed" id="work_completed" class="form-textarea" rows="3">{{ old('work_completed', $log->work_completed) }}</textarea>
                </div>
                <div class="form-group md:col-span-2">
                    <label for="equipment_used">Equipment Used</label>
                    <textarea name="equipment_used" id="equipment_used" class="form-textarea" rows="3">{{ old('equipment_used', $log->equipment_used) }}</textarea>
                </div>
                <div class="form-group md:col-span-2">
                    <label for="materials_received">Materials Received</label>
                    <textarea name="materials_received" id="materials_received" class="form-textarea" rows="3">{{ old('materials_received', $log->materials_received) }}</textarea>
                </div>
                <div class="form-group md:col-span-2">
                    <label for="issues_notes">Issues / Notes</label>
                    <textarea name="issues_notes" id="issues_notes" class="form-textarea" rows="3">{{ old('issues_notes', $log->issues_notes) }}</textarea>
                </div>
                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="draft" {{ old('status', $log->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="submitted" {{ old('status', $log->status) == 'submitted' ? 'selected' : '' }}>Submitted</option>
                    </select>
                </div>
            </div>

            <div class="form-group mt-5">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-textarea" rows="4">{{ old('description', $log->description) }}</textarea>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update Log</button>
                <button type="reset" class="btn btn-outline-danger">Reset</button>
            </div>
        </form>
    </div>
@endsection
