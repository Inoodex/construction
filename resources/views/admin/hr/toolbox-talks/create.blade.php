@extends('admin.layouts.master')

@section('title', 'New Toolbox Talk')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">New Toolbox Talk</h2>
        <a href="{{ route('admin.hr.toolbox-talks.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.hr.toolbox-talks.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">Date <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-input" required value="{{ old('date', date('Y-m-d')) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Topic <span class="text-danger">*</span></label>
                    <input type="text" name="topic" class="form-input" required value="{{ old('topic') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Conducted By</label>
                    <select name="employee_id" class="form-select">
                        <option value="">Select employee</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Duration (minutes)</label>
                    <input type="number" name="duration_minutes" class="form-input" value="{{ old('duration_minutes') }}" min="1" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Location</label>
                    <input type="text" name="location" class="form-input" value="{{ old('location') }}" />
                </div>
            </div>
            <div class="mt-4">
                <label class="text-sm font-semibold">Attendees</label>
                <textarea name="attendees" class="form-textarea" rows="2" placeholder="Names of attendees">{{ old('attendees') }}</textarea>
            </div>
            <div class="mt-4">
                <label class="text-sm font-semibold">Discussion Points</label>
                <textarea name="discussion_points" class="form-textarea" rows="3" placeholder="Key points discussed">{{ old('discussion_points') }}</textarea>
            </div>
            <div class="mt-4">
                <label class="text-sm font-semibold">Action Items</label>
                <textarea name="action_items" class="form-textarea" rows="3" placeholder="Follow-up actions">{{ old('action_items') }}</textarea>
            </div>
            <div class="mt-4">
                <label class="text-sm font-semibold">Notes</label>
                <textarea name="notes" class="form-textarea" rows="2">{{ old('notes') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Save Toolbox Talk</button>
        </form>
    </div>
@endsection
