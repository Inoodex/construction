@extends('admin.layouts.master')

@section('title', 'Edit Training Record')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Training Record</h2>
        <a href="{{ route('admin.hr.training-records.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.hr.training-records.update', $trainingRecord) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">Employee <span class="text-danger">*</span></label>
                    <select name="employee_id" class="form-select" required>
                        <option value="">Select employee</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id', $trainingRecord->employee_id) == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }}</option>
                        @endforeach
                    </select>
                    @error('employee_id') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Training Name <span class="text-danger">*</span></label>
                    <input type="text" name="training_name" class="form-input" required value="{{ old('training_name', $trainingRecord->training_name) }}" />
                    @error('training_name') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Provider</label>
                    <input type="text" name="provider" class="form-input" value="{{ old('provider', $trainingRecord->provider) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="planned" {{ old('status', $trainingRecord->status) == 'planned' ? 'selected' : '' }}>Planned</option>
                        <option value="in-progress" {{ old('status', $trainingRecord->status) == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ old('status', $trainingRecord->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="expired" {{ old('status', $trainingRecord->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Start Date <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" class="form-input" required value="{{ old('start_date', $trainingRecord->start_date?->format('Y-m-d')) }}" />
                    @error('start_date') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">End Date</label>
                    <input type="date" name="end_date" class="form-input" value="{{ old('end_date', $trainingRecord->end_date?->format('Y-m-d')) }}" />
                    @error('end_date') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Certificate No.</label>
                    <input type="text" name="certificate_no" class="form-input" value="{{ old('certificate_no', $trainingRecord->certificate_no) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Expiry Date</label>
                    <input type="date" name="expiry_date" class="form-input" value="{{ old('expiry_date', $trainingRecord->expiry_date?->format('Y-m-d')) }}" />
                    @error('expiry_date') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Cost</label>
                    <input type="number" step="0.01" name="cost" class="form-input" value="{{ old('cost', $trainingRecord->cost) }}" />
                </div>
            </div>
            <div class="mt-4">
                <label class="text-sm font-semibold">Notes</label>
                <textarea name="notes" class="form-textarea" rows="3">{{ old('notes', $trainingRecord->notes) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Update Record</button>
        </form>
    </div>
@endsection
