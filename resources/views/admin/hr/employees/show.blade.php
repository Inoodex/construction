@extends('admin.layouts.master')

@section('title', $employee->full_name)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">{{ $employee->full_name }}</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.hr.employees.edit', $employee) }}" class="btn btn-primary gap-2">Edit</a>
            <a href="{{ route('admin.hr.employees.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to List
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-5 border-b border-gray-200 pb-3">
            <h4 class="text-base font-semibold">Personal Information</h4>
        </div>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div><span class="text-xs text-white-dark">Employee Code</span><p class="font-semibold font-mono">{{ $employee->employee_code }}</p></div>
            <div><span class="text-xs text-white-dark">Full Name</span><p class="font-semibold">{{ $employee->full_name }}</p></div>
            <div><span class="text-xs text-white-dark">Date of Birth</span><p>{{ $employee->date_of_birth?->format('d M Y') ?? '—' }}</p></div>
            <div><span class="text-xs text-white-dark">Father's Name</span><p>{{ $employee->father_name ?? '—' }}</p></div>
            <div><span class="text-xs text-white-dark">Mother's Name</span><p>{{ $employee->mother_name ?? '—' }}</p></div>
            <div><span class="text-xs text-white-dark">Gender</span><p class="capitalize">{{ $employee->gender ?? '—' }}</p></div>
            <div><span class="text-xs text-white-dark">Blood Group</span><p>{{ $employee->blood_group ?? '—' }}</p></div>
            <div><span class="text-xs text-white-dark">NID Number</span><p>{{ $employee->nid_number ?? '—' }}</p></div>
        </div>

        <div class="mb-5 mt-8 border-b border-gray-200 pb-3">
            <h4 class="text-base font-semibold">Contact Information</h4>
        </div>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div><span class="text-xs text-white-dark">Phone</span><p>{{ $employee->phone ?? '—' }}</p></div>
            <div><span class="text-xs text-white-dark">Email</span><p>{{ $employee->email ?? '—' }}</p></div>
            <div><span class="text-xs text-white-dark">Present Address</span><p>{{ $employee->present_address ?? '—' }}</p></div>
            <div><span class="text-xs text-white-dark">Permanent Address</span><p>{{ $employee->permanent_address ?? '—' }}</p></div>
        </div>

        <div class="mb-5 mt-8 border-b border-gray-200 pb-3">
            <h4 class="text-base font-semibold">Employment Details</h4>
        </div>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div><span class="text-xs text-white-dark">Designation</span><p>{{ $employee->designation ?? '—' }}</p></div>
            <div><span class="text-xs text-white-dark">Department</span><p>{{ $employee->department ?? '—' }}</p></div>
            <div><span class="text-xs text-white-dark">Employment Type</span><p class="capitalize">{{ $employee->employment_type }}</p></div>
            <div><span class="text-xs text-white-dark">Joining Date</span><p>{{ $employee->joining_date?->format('d M Y') ?? '—' }}</p></div>
            <div><span class="text-xs text-white-dark">Basic Salary</span><p>{{ $employee->basic_salary ? '৳ ' . number_format($employee->basic_salary, 2) : '—' }}</p></div>
            <div>
                <span class="text-xs text-white-dark">Status</span>
                <p>
                    @php
                        $statusClass = match($employee->status) {
                            'active' => 'badge-outline-success',
                            'inactive' => 'badge-outline-secondary',
                            'terminated' => 'badge-outline-danger',
                            'resigned' => 'badge-outline-warning',
                            default => 'badge-outline-secondary'
                        };
                    @endphp
                    <span class="badge {{ $statusClass }} capitalize">{{ $employee->status }}</span>
                </p>
            </div>
        </div>

        @if($employee->emergency_contact_name || $employee->emergency_contact_phone)
            <div class="mb-5 mt-8 border-b border-gray-200 pb-3">
                <h4 class="text-base font-semibold">Emergency Contact</h4>
            </div>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div><span class="text-xs text-white-dark">Name</span><p>{{ $employee->emergency_contact_name }}</p></div>
                <div><span class="text-xs text-white-dark">Phone</span><p>{{ $employee->emergency_contact_phone }}</p></div>
            </div>
        @endif
    </div>
@endsection
