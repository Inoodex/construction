@extends('admin.layouts.master')

@section('title', 'New Leave Request')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">New Leave Request</h2>
        <a href="{{ route('admin.hr.leaves.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.hr.leaves.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="employee_id">Employee <span class="text-danger">*</span></label>
                    <select name="employee_id" id="employee_id" class="form-select" required>
                        <option value="">Select</option>
                        @foreach($employees as $id => $name)
                            <option value="{{ $id }}" {{ old('employee_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="leave_type">Leave Type <span class="text-danger">*</span></label>
                    <select name="leave_type" id="leave_type" class="form-select" required>
                        <option value="">Select</option>
                        <option value="sick" {{ old('leave_type') == 'sick' ? 'selected' : '' }}>Sick Leave</option>
                        <option value="casual" {{ old('leave_type') == 'casual' ? 'selected' : '' }}>Casual Leave</option>
                        <option value="annual" {{ old('leave_type') == 'annual' ? 'selected' : '' }}>Annual Leave</option>
                        <option value="unpaid" {{ old('leave_type') == 'unpaid' ? 'selected' : '' }}>Unpaid Leave</option>
                        <option value="other" {{ old('leave_type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" id="start_date" class="form-input" required value="{{ old('start_date') }}" />
                </div>
                <div class="form-group">
                    <label for="end_date">End Date <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" id="end_date" class="form-input" required value="{{ old('end_date') }}" />
                </div>
                <div class="form-group md:col-span-2">
                    <label for="reason">Reason</label>
                    <textarea name="reason" id="reason" class="form-textarea" rows="3">{{ old('reason') }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-6">Submit Request</button>
        </form>
    </div>
@endsection
