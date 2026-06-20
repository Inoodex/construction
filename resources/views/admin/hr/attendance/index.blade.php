@extends('admin.layouts.master')

@section('title', 'Attendance')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Attendance</h5>
        <a href="{{ route('admin.hr.attendance.create') }}" class="btn btn-primary">+ Mark Attendance</a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-100 p-3 text-green-700">{{ session('success') }}</div>
    @endif

    <form method="GET" class="mb-4 flex flex-nowrap items-center gap-2 overflow-x-auto">
        <select name="employee_id" class="form-select">
            <option value="">All Employees</option>
            @foreach($employees as $id => $name)
                <option value="{{ $id }}" {{ request('employee_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
        <input type="date" name="date_from" class="form-input" value="{{ request('date_from') }}" placeholder="From" />
        <input type="date" name="date_to" class="form-input" value="{{ request('date_to') }}" placeholder="To" />
        <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
            <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
            <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
            <option value="half-day" {{ request('status') == 'half-day' ? 'selected' : '' }}>Half Day</option>
            <option value="holiday" {{ request('status') == 'holiday' ? 'selected' : '' }}>Holiday</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->anyFilled(['employee_id', 'date_from', 'date_to', 'status']))
            <a href="{{ route('admin.hr.attendance.index') }}" class="btn btn-outline-danger">Reset</a>
        @endif
    </form>

    <div class="overflow-x-auto">
        <table class="table-hover w-full table-auto">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Date</th>
                    <th>Clock In</th>
                    <th>Clock Out</th>
                    <th>Status</th>
                    <th>Note</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $r)
                    <tr>
                        <td class="font-semibold">{{ $r->employee->full_name }}</td>
                        <td>{{ $r->date->format('d M Y') }}</td>
                        <td class="text-xs">{{ $r->clock_in?->format('h:i A') ?? '—' }}</td>
                        <td class="text-xs">{{ $r->clock_out?->format('h:i A') ?? '—' }}</td>
                        <td>
                            @php
                                $cls = match($r->status) {
                                    'present' => 'badge-outline-success',
                                    'absent' => 'badge-outline-danger',
                                    'late' => 'badge-outline-warning',
                                    'half-day' => 'badge-outline-info',
                                    'holiday' => 'badge-outline-primary',
                                    default => 'badge-outline-secondary'
                                };
                            @endphp
                            <span class="badge {{ $cls }} capitalize">{{ $r->status }}</span>
                        </td>
                        <td class="text-xs">{{ $r->note ?? '—' }}</td>
                        <td>
                            <form action="{{ route('admin.hr.attendance.destroy', $r) }}" method="POST" onsubmit="return confirm('Delete?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">×</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-gray-500">No attendance records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $records->links() }}</div>
</div>
@endsection
