@extends('admin.layouts.master')

@section('title', 'Create NCR')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Create Non-Conformance Report</h2>
        <a href="{{ route('admin.quality.ncrs.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.quality.ncrs.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">Project <span class="text-danger">*</span></label>
                    <select name="project_id" class="form-select" required>
                        <option value="">Select project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                    @error('project_id') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-input" required value="{{ old('title') }}" placeholder="Brief description of non-conformance" />
                    @error('title') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Category <span class="text-danger">*</span></label>
                    <select name="category" class="form-select" required>
                        <option value="">Select category</option>
                        <option value="structural" {{ old('category') == 'structural' ? 'selected' : '' }}>Structural</option>
                        <option value="material" {{ old('category') == 'material' ? 'selected' : '' }}>Material</option>
                        <option value="workmanship" {{ old('category') == 'workmanship' ? 'selected' : '' }}>Workmanship</option>
                        <option value="safety" {{ old('category') == 'safety' ? 'selected' : '' }}>Safety</option>
                        <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('category') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Severity <span class="text-danger">*</span></label>
                    <select name="severity" class="form-select" required>
                        <option value="">Select severity</option>
                        <option value="minor" {{ old('severity') == 'minor' ? 'selected' : '' }}>Minor</option>
                        <option value="major" {{ old('severity') == 'major' ? 'selected' : '' }}>Major</option>
                        <option value="critical" {{ old('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>
                    @error('severity') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Identified Date <span class="text-danger">*</span></label>
                    <input type="date" name="identified_date" class="form-input" required value="{{ old('identified_date', date('Y-m-d')) }}" />
                    @error('identified_date') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Due Date</label>
                    <input type="date" name="due_date" class="form-input" value="{{ old('due_date') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Location</label>
                    <input type="text" name="location" class="form-input" value="{{ old('location') }}" placeholder="Where the NCR was identified" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Identified By</label>
                    <select name="identified_by" class="form-select">
                        <option value="">Select person</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('identified_by') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Description <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-textarea" rows="4" required>{{ old('description') }}</textarea>
                    @error('description') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Root Cause</label>
                    <textarea name="root_cause" class="form-textarea" rows="3">{{ old('root_cause') }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-semibold">Corrective Action</label>
                    <textarea name="corrective_action" class="form-textarea" rows="3">{{ old('corrective_action') }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-semibold">Preventive Action</label>
                    <textarea name="preventive_action" class="form-textarea" rows="3">{{ old('preventive_action') }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-semibold">Notes</label>
                    <textarea name="notes" class="form-textarea" rows="3">{{ old('notes') }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Create NCR</button>
        </form>
    </div>
@endsection
