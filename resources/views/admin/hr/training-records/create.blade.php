@extends('admin.layouts.master')

@section('title', 'New Training Record')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">New Training Record</h2>
        <a href="{{ route('admin.hr.training-records.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
    </div>

    <div class="panel mt-6 max-w-2xl">
        <form action="{{ route('admin.hr.training-records.store') }}" method="POST">
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
                    <label class="text-sm font-semibold">Training Name <span class="text-danger">*</span></label>
                    <input type="text" name="training_name" class="form-input" required value="{{ old('training_name') }}" />
                    @error('training_name') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Provider</label>
                    <input type="text" name="provider" class="form-input" value="{{ old('provider') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="planned" {{ old('status', 'planned') == 'planned' ? 'selected' : '' }}>Planned</option>
                        <option value="in-progress" {{ old('status') == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Start Date <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" class="form-input" required value="{{ old('start_date') }}" />
                    @error('start_date') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">End Date</label>
                    <input type="date" name="end_date" class="form-input" value="{{ old('end_date') }}" />
                    @error('end_date') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Certificate No.</label>
                    <input type="text" name="certificate_no" class="form-input" value="{{ old('certificate_no') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Expiry Date</label>
                    <input type="date" name="expiry_date" class="form-input" value="{{ old('expiry_date') }}" />
                    @error('expiry_date') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Cost</label>
                    <input type="number" step="0.01" name="cost" class="form-input" value="{{ old('cost') }}" />
                </div>
            </div>
            <div class="mt-4">
                <label class="text-sm font-semibold">Notes</label>
                <textarea name="notes" class="form-textarea" rows="3">{{ old('notes') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Save Record</button>
        </form>
    </div>
@endsection
