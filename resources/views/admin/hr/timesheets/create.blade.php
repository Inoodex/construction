@extends('admin.layouts.master')

@section('title', 'New Timesheet Entry')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">New Timesheet Entry</h2>
        <a href="{{ route('admin.hr.timesheets.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
    </div>

    <div class="panel mt-6 max-w-2xl">
        <form action="{{ route('admin.hr.timesheets.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">Employee <span class="text-danger">*</span></label>
                    <select name="employee_id" class="form-select" required>
                        <option value="">Select employee</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }}</option>
                        @endforeach
                    </select>
                    @error('employee_id') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Project</label>
                    <select name="project_id" class="form-select">
                        <option value="">None</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}" {{ old('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Date <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-input" required value="{{ old('date', date('Y-m-d')) }}" />
                    @error('date') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Hours Worked <span class="text-danger">*</span></label>
                    <input type="number" step="0.5" name="hours_worked" class="form-input" required value="{{ old('hours_worked') }}" />
                    @error('hours_worked') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Start Time</label>
                    <input type="time" name="start_time" class="form-input" value="{{ old('start_time') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">End Time</label>
                    <input type="time" name="end_time" class="form-input" value="{{ old('end_time') }}" />
                </div>
            </div>
            <div class="mt-4">
                <label class="text-sm font-semibold">Description</label>
                <textarea name="description" class="form-textarea" rows="3">{{ old('description') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Save Entry</button>
        </form>
    </div>
@endsection
