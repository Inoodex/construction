@extends('admin.layouts.master')

@section('title', 'Edit Certification')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Certification</h2>
        <a href="{{ route('admin.hr.certifications.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Back
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.hr.certifications.update', $certification) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">Employee <span class="text-danger">*</span></label>
                    <select name="employee_id" class="form-select" required>
                        <option value="">Select employee</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id', $certification->employee_id) == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Certification Name <span class="text-danger">*</span></label>
                    <input type="text" name="certification_name" class="form-input" required value="{{ old('certification_name', $certification->certification_name) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Category <span class="text-danger">*</span></label>
                    <select name="category" class="form-select" required>
                        <option value="certification" {{ old('category', $certification->category) == 'certification' ? 'selected' : '' }}>Certification</option>
                        <option value="license" {{ old('category', $certification->category) == 'license' ? 'selected' : '' }}>License</option>
                        <option value="permit" {{ old('category', $certification->category) == 'permit' ? 'selected' : '' }}>Permit</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Issuing Authority</label>
                    <input type="text" name="issuing_authority" class="form-input" value="{{ old('issuing_authority', $certification->issuing_authority) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Certificate No.</label>
                    <input type="text" name="certificate_no" class="form-input" value="{{ old('certificate_no', $certification->certificate_no) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="active" {{ old('status', $certification->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="expired" {{ old('status', $certification->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="suspended" {{ old('status', $certification->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="revoked" {{ old('status', $certification->status) == 'revoked' ? 'selected' : '' }}>Revoked</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Issue Date <span class="text-danger">*</span></label>
                    <input type="date" name="issue_date" class="form-input" required value="{{ old('issue_date', $certification->issue_date?->format('Y-m-d')) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Expiry Date</label>
                    <input type="date" name="expiry_date" class="form-input" value="{{ old('expiry_date', $certification->expiry_date?->format('Y-m-d')) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Renewal Reminder Date</label>
                    <input type="date" name="renewal_reminder_date" class="form-input" value="{{ old('renewal_reminder_date', $certification->renewal_reminder_date?->format('Y-m-d')) }}" />
                </div>
            </div>
            <div class="mt-4">
                <label class="text-sm font-semibold">Notes</label>
                <textarea name="notes" class="form-textarea" rows="3">{{ old('notes', $certification->notes) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Update Certification</button>
        </form>
    </div>
@endsection
