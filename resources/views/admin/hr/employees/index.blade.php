@extends('admin.layouts.master')

@section('title', 'Employees')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Employees</h5>
        <a href="{{ route('admin.hr.employees.create') }}" class="btn btn-primary">+ Add Employee</a>
    </div>
    <form method="GET" class="mb-4 flex flex-wrap items-center gap-3">
        <input type="text" name="search" class="form-input flex-1" placeholder="Search name, code, phone..." value="{{ request('search') }}" />
        <select name="department" class="form-select flex-1">
            <option value="">All Departments</option>
            @foreach($departments as $dept)
                <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
            @endforeach
        </select>
        <select name="employment_type" class="form-select flex-1">
            <option value="">All Types</option>
            @foreach($employmentTypes as $t)
                <option value="{{ $t }}" {{ request('employment_type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
            @endforeach
        </select>
        <select name="status" class="form-select flex-1">
            <option value="">All Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
            <option value="resigned" {{ request('status') == 'resigned' ? 'selected' : '' }}>Resigned</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->anyFilled(['search', 'department', 'employment_type', 'status']))
            <a href="{{ route('admin.hr.employees.index') }}" class="btn btn-outline-danger">Reset</a>
        @endif
    </form>

    <div class="datatable">
        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Department</th>
                        <th>Phone</th>
                        <th>Type</th>
                        <th>Salary</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            <td class="font-mono text-xs">{{ $employee->employee_code }}</td>
                            <td>
                                <div class="font-semibold">{{ $employee->full_name }}</div>
                                <div class="text-xs text-white-dark">{{ $employee->designation ?? '—' }}</div>
                            </td>
                            <td>{{ $employee->designation ?? '—' }}</td>
                            <td>{{ $employee->department ?? '—' }}</td>
                            <td>{{ $employee->phone ?? '—' }}</td>
                            <td><span class="badge badge-outline-info capitalize">{{ $employee->employment_type }}</span></td>
                            <td>{{ $employee->basic_salary ? number_format($employee->basic_salary, 2) : '—' }}</td>
                            <td>
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
                            </td>
                            <td class="text-center">
                                <x-action-dropdown>
                                    <x-slot name="items">
                                        <li><a href="{{ route('admin.hr.employees.show', $employee) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                        <li><a href="{{ route('admin.hr.employees.edit', $employee) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/></svg> Edit</a></li>
                                        <li class="divider"></li>
                                        <li class="danger">
                                            <form action="{{ route('admin.hr.employees.destroy', $employee) }}" method="POST" onsubmit="return confirm('Delete this employee?');">
                                                @csrf @method('DELETE')
                                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 001-1h4a1 1 0 001 1m-6 0h6"/></svg> Delete</button>
                                            </form>
                                        </li>
                                    </x-slot>
                                </x-action-dropdown>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-gray-500">No employees found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $employees->links() }}</div>
</div>
@endsection
