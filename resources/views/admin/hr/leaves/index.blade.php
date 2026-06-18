@extends('admin.layouts.master')

@section('title', 'Leave Requests')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Leave Requests</h5>
        <a href="{{ route('admin.hr.leaves.create') }}" class="btn btn-primary">+ New Leave Request</a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-100 p-3 text-green-700">{{ session('success') }}</div>
    @endif

    <form method="GET" class="mb-4 flex flex-wrap items-center gap-3">
        <select name="employee_id" class="form-select">
            <option value="">All Employees</option>
            @foreach($employees as $id => $name)
                <option value="{{ $id }}" {{ request('employee_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
        <select name="leave_type" class="form-select">
            <option value="">All Types</option>
            <option value="sick" {{ request('leave_type') == 'sick' ? 'selected' : '' }}>Sick</option>
            <option value="casual" {{ request('leave_type') == 'casual' ? 'selected' : '' }}>Casual</option>
            <option value="annual" {{ request('leave_type') == 'annual' ? 'selected' : '' }}>Annual</option>
            <option value="unpaid" {{ request('leave_type') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
            <option value="other" {{ request('leave_type') == 'other' ? 'selected' : '' }}>Other</option>
        </select>
        <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->anyFilled(['employee_id', 'leave_type', 'status']))
            <a href="{{ route('admin.hr.leaves.index') }}" class="btn btn-outline-danger">Reset</a>
        @endif
    </form>

    <div class="overflow-x-auto">
        <table class="table-hover w-full table-auto">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Type</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Days</th>
                    <th>Status</th>
                    <th>Approved By</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaves as $l)
                    <tr>
                        <td class="font-semibold">{{ $l->employee->full_name }}</td>
                        <td><span class="badge badge-outline-info capitalize">{{ $l->leave_type }}</span></td>
                        <td>{{ $l->start_date->format('d M Y') }}</td>
                        <td>{{ $l->end_date->format('d M Y') }}</td>
                        <td class="text-center">{{ $l->start_date->diffInDays($l->end_date) + 1 }}</td>
                        <td>
                            @php
                                $cls = match($l->status) {
                                    'approved' => 'badge-outline-success',
                                    'pending' => 'badge-outline-warning',
                                    'rejected' => 'badge-outline-danger',
                                    default => 'badge-outline-secondary'
                                };
                            @endphp
                            <span class="badge {{ $cls }} capitalize">{{ $l->status }}</span>
                        </td>
                        <td class="text-xs">{{ $l->approver?->name ?? '—' }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.hr.leaves.show', $l) }}" class="btn btn-sm btn-outline-info">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-gray-500">No leave requests found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $leaves->links() }}</div>
</div>
@endsection
