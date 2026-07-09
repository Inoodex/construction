@extends('admin.layouts.master')

@section('title', 'Edit NCR - ' . $ncr->ncr_number)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit NCR: {{ $ncr->ncr_number }}</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.quality.ncrs.show', $ncr) }}" class="btn btn-secondary gap-2">&larr; Back</a>
        </div>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.quality.ncrs.update', $ncr) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">Project <span class="text-danger">*</span></label>
                    <select name="project_id" class="form-select" required>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $ncr->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                    @error('project_id') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-input" required value="{{ old('title', $ncr->title) }}" />
                    @error('title') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Category <span class="text-danger">*</span></label>
                    <select name="category" class="form-select" required>
                        @foreach(['structural','material','workmanship','safety','other'] as $cat)
                            <option value="{{ $cat }}" {{ old('category', $ncr->category) == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                        @endforeach
                    </select>
                    @error('category') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Severity <span class="text-danger">*</span></label>
                    <select name="severity" class="form-select" required>
                        @foreach(['minor','major','critical'] as $sev)
                            <option value="{{ $sev }}" {{ old('severity', $ncr->severity) == $sev ? 'selected' : '' }}>{{ ucfirst($sev) }}</option>
                        @endforeach
                    </select>
                    @error('severity') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        @foreach(['open','under_investigation','corrective_action','closed'] as $st)
                            <option value="{{ $st }}" {{ old('status', $ncr->status) == $st ? 'selected' : '' }}>{{ str_replace('_', ' ', ucfirst($st)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Identified Date <span class="text-danger">*</span></label>
                    <input type="date" name="identified_date" class="form-input" required value="{{ old('identified_date', $ncr->identified_date->format('Y-m-d')) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Due Date</label>
                    <input type="date" name="due_date" class="form-input" value="{{ old('due_date', $ncr->due_date?->format('Y-m-d')) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Location</label>
                    <input type="text" name="location" class="form-input" value="{{ old('location', $ncr->location) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Identified By</label>
                    <select name="identified_by" class="form-select">
                        <option value="">Select person</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('identified_by', $ncr->identified_by) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Closed Date</label>
                    <input type="date" name="closed_date" class="form-input" value="{{ old('closed_date', $ncr->closed_date?->format('Y-m-d')) }}" />
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Description <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-textarea" rows="4" required>{{ old('description', $ncr->description) }}</textarea>
                    @error('description') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Root Cause</label>
                    <textarea name="root_cause" class="form-textarea" rows="3">{{ old('root_cause', $ncr->root_cause) }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-semibold">Corrective Action</label>
                    <textarea name="corrective_action" class="form-textarea" rows="3">{{ old('corrective_action', $ncr->corrective_action) }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-semibold">Preventive Action</label>
                    <textarea name="preventive_action" class="form-textarea" rows="3">{{ old('preventive_action', $ncr->preventive_action) }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-semibold">Notes</label>
                    <textarea name="notes" class="form-textarea" rows="3">{{ old('notes', $ncr->notes) }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Update NCR</button>
        </form>
    </div>
@endsection
