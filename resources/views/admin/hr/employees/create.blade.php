@extends('admin.layouts.master')

@section('title', 'Add Employee')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Add Employee</h2>
        <a href="{{ route('admin.hr.employees.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.hr.employees.store') }}" method="POST">
            @csrf
            <div class="mb-5 border-b border-gray-200 pb-3">
                <h4 class="text-base font-semibold">Personal Information</h4>
            </div>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div class="form-group">
                    <label for="employee_code">Employee Code <span class="text-danger">*</span></label>
                    <input type="text" name="employee_code" id="employee_code" class="form-input" required value="{{ old('employee_code') }}" placeholder="EMP-001" />
                    @error('employee_code') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="full_name">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="full_name" id="full_name" class="form-input" required value="{{ old('full_name') }}" />
                    @error('full_name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="date_of_birth">Date of Birth</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" class="form-input" value="{{ old('date_of_birth') }}" />
                </div>
                <div class="form-group">
                    <label for="father_name">Father's Name</label>
                    <input type="text" name="father_name" id="father_name" class="form-input" value="{{ old('father_name') }}" />
                </div>
                <div class="form-group">
                    <label for="mother_name">Mother's Name</label>
                    <input type="text" name="mother_name" id="mother_name" class="form-input" value="{{ old('mother_name') }}" />
                </div>
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select name="gender" id="gender" class="form-select">
                        <option value="">Select</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="blood_group">Blood Group</label>
                    <select name="blood_group" id="blood_group" class="form-select">
                        <option value="">Select</option>
                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                            <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="nid_number">NID Number</label>
                    <input type="text" name="nid_number" id="nid_number" class="form-input" value="{{ old('nid_number') }}" />
                </div>
            </div>

            <div class="mb-5 mt-8 border-b border-gray-200 pb-3">
                <h4 class="text-base font-semibold">Contact Information</h4>
            </div>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-input" value="{{ old('phone') }}" />
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}" />
                </div>
                <div class="form-group">
                    <label for="present_address">Present Address</label>
                    <textarea name="present_address" id="present_address" class="form-textarea" rows="2">{{ old('present_address') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="permanent_address">Permanent Address</label>
                    <textarea name="permanent_address" id="permanent_address" class="form-textarea" rows="2">{{ old('permanent_address') }}</textarea>
                </div>
            </div>

            <div class="mb-5 mt-8 border-b border-gray-200 pb-3">
                <h4 class="text-base font-semibold">Employment Details</h4>
            </div>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div class="form-group">
                    <label for="designation">Designation</label>
                    <input type="text" name="designation" id="designation" class="form-input" value="{{ old('designation') }}" placeholder="e.g. Site Engineer" />
                </div>
                <div class="form-group">
                    <label for="department">Department</label>
                    <input type="text" name="department" id="department" class="form-input" value="{{ old('department') }}" placeholder="e.g. Construction" />
                </div>
                <div class="form-group">
                    <label for="employment_type">Employment Type</label>
                    <select name="employment_type" id="employment_type" class="form-select">
                        <option value="">Select</option>
                        <option value="permanent" {{ old('employment_type') == 'permanent' ? 'selected' : '' }}>Permanent</option>
                        <option value="contractual" {{ old('employment_type') == 'contractual' ? 'selected' : '' }}>Contractual</option>
                        <option value="daily_wage" {{ old('employment_type') == 'daily_wage' ? 'selected' : '' }}>Daily Wage</option>
                        <option value="probation" {{ old('employment_type') == 'probation' ? 'selected' : '' }}>Probation</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="joining_date">Joining Date</label>
                    <input type="date" name="joining_date" id="joining_date" class="form-input" value="{{ old('joining_date') }}" />
                </div>
                <div class="form-group">
                    <label for="basic_salary">Basic Salary (৳)</label>
                    <input type="number" step="0.01" min="0" name="basic_salary" id="basic_salary" class="form-input" value="{{ old('basic_salary') }}" />
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="terminated" {{ old('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                        <option value="resigned" {{ old('status') == 'resigned' ? 'selected' : '' }}>Resigned</option>
                    </select>
                </div>
            </div>

            <div class="mb-5 mt-8 border-b border-gray-200 pb-3">
                <h4 class="text-base font-semibold">Emergency Contact</h4>
            </div>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="emergency_contact_name">Contact Name</label>
                    <input type="text" name="emergency_contact_name" id="emergency_contact_name" class="form-input" value="{{ old('emergency_contact_name') }}" />
                </div>
                <div class="form-group">
                    <label for="emergency_contact_phone">Contact Phone</label>
                    <input type="text" name="emergency_contact_phone" id="emergency_contact_phone" class="form-input" value="{{ old('emergency_contact_phone') }}" />
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Save Employee</button>
                <button type="reset" class="btn btn-outline-danger">Reset Form</button>
            </div>
        </form>
    </div>
@endsection
