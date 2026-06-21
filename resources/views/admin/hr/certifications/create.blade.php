@extends('admin.layouts.master')

@section('title', 'Add Certification')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Add Certification</h2>
        <a href="{{ route('admin.hr.certifications.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
    </div>

    <div class="panel mt-6 max-w-2xl">
        <form action="{{ route('admin.hr.certifications.store') }}" method="POST">
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
                    <label class="text-sm font-semibold">Certification Name <span class="text-danger">*</span></label>
                    <input type="text" name="certification_name" class="form-input" required value="{{ old('certification_name') }}" />
                    @error('certification_name') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Category <span class="text-danger">*</span></label>
                    <select name="category" class="form-select" required>
                        <option value="certification" {{ old('category', 'certification') == 'certification' ? 'selected' : '' }}>Certification</option>
                        <option value="license" {{ old('category') == 'license' ? 'selected' : '' }}>License</option>
                        <option value="permit" {{ old('category') == 'permit' ? 'selected' : '' }}>Permit</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Issuing Authority</label>
                    <input type="text" name="issuing_authority" class="form-input" value="{{ old('issuing_authority') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Certificate No.</label>
                    <input type="text" name="certificate_no" class="form-input" value="{{ old('certificate_no') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="revoked" {{ old('status') == 'revoked' ? 'selected' : '' }}>Revoked</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Issue Date <span class="text-danger">*</span></label>
                    <input type="date" name="issue_date" class="form-input" required value="{{ old('issue_date', date('Y-m-d')) }}" />
                    @error('issue_date') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Expiry Date</label>
                    <input type="date" name="expiry_date" class="form-input" value="{{ old('expiry_date') }}" />
                    @error('expiry_date') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Renewal Reminder Date</label>
                    <input type="date" name="renewal_reminder_date" class="form-input" value="{{ old('renewal_reminder_date') }}" />
                </div>
            </div>
            <div class="mt-4">
                <label class="text-sm font-semibold">Notes</label>
                <textarea name="notes" class="form-textarea" rows="3">{{ old('notes') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Save Certification</button>
        </form>
    </div>
@endsection
