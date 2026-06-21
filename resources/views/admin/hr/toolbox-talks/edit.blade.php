@extends('admin.layouts.master')

@section('title', 'Edit Toolbox Talk')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Toolbox Talk</h2>
        <a href="{{ route('admin.hr.toolbox-talks.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
    </div>

    <div class="panel mt-6 max-w-2xl">
        <form action="{{ route('admin.hr.toolbox-talks.update', $toolboxTalk) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">Date <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-input" required value="{{ old('date', $toolboxTalk->date?->format('Y-m-d')) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Topic <span class="text-danger">*</span></label>
                    <input type="text" name="topic" class="form-input" required value="{{ old('topic', $toolboxTalk->topic) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Conducted By</label>
                    <select name="employee_id" class="form-select">
                        <option value="">Select employee</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id', $toolboxTalk->employee_id) == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Duration (minutes)</label>
                    <input type="number" name="duration_minutes" class="form-input" value="{{ old('duration_minutes', $toolboxTalk->duration_minutes) }}" min="1" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Location</label>
                    <input type="text" name="location" class="form-input" value="{{ old('location', $toolboxTalk->location) }}" />
                </div>
            </div>
            <div class="mt-4">
                <label class="text-sm font-semibold">Attendees</label>
                <textarea name="attendees" class="form-textarea" rows="2">{{ old('attendees', $toolboxTalk->attendees) }}</textarea>
            </div>
            <div class="mt-4">
                <label class="text-sm font-semibold">Discussion Points</label>
                <textarea name="discussion_points" class="form-textarea" rows="3">{{ old('discussion_points', $toolboxTalk->discussion_points) }}</textarea>
            </div>
            <div class="mt-4">
                <label class="text-sm font-semibold">Action Items</label>
                <textarea name="action_items" class="form-textarea" rows="3">{{ old('action_items', $toolboxTalk->action_items) }}</textarea>
            </div>
            <div class="mt-4">
                <label class="text-sm font-semibold">Notes</label>
                <textarea name="notes" class="form-textarea" rows="2">{{ old('notes', $toolboxTalk->notes) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Update Toolbox Talk</button>
        </form>
    </div>
@endsection
