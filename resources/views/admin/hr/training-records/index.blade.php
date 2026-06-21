@extends('admin.layouts.master')

@section('title', 'Training Records')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Training Records</h5>
        <a href="{{ route('admin.hr.training-records.create') }}" class="btn btn-primary">+ New Training Record</a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-100 p-3 text-green-700">{{ session('success') }}</div>
    @endif

    <form method="GET" class="mb-4 flex flex-nowrap items-center gap-2 overflow-x-auto">
        <select name="employee_id" class="form-select" onchange="this.form.submit()">
            <option value="">All Employees</option>
            @foreach($employees as $id => $name)
                <option value="{{ $id }}" {{ request('employee_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
        <select name="status" class="form-select" onchange="this.form.submit()">
            <option value="">All Status</option>
            <option value="planned" {{ request('status') == 'planned' ? 'selected' : '' }}>Planned</option>
            <option value="in-progress" {{ request('status') == 'in-progress' ? 'selected' : '' }}>In Progress</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
        </select>
        @if(request()->anyFilled(['employee_id', 'status']))
            <a href="{{ route('admin.hr.training-records.index') }}" class="btn btn-outline-danger btn-sm">Reset</a>
        @endif
    </form>

    <div class="overflow-x-auto">
        <table class="table-hover w-full table-auto">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Training</th>
                    <th>Provider</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Expiry</th>
                    <th>Status</th>
                    <th class="text-right">Cost</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $r)
                    <tr>
                        <td class="font-semibold">{{ $r->employee->full_name }}</td>
                        <td>{{ $r->training_name }}</td>
                        <td class="text-xs">{{ $r->provider ?? '—' }}</td>
                        <td>{{ $r->start_date->format('d M Y') }}</td>
                        <td>{{ $r->end_date?->format('d M Y') ?? '—' }}</td>
                        <td class="text-xs">{{ $r->expiry_date?->format('d M Y') ?? '—' }}</td>
                        <td>
                            @php
                                $cls = match($r->status) {
                                    'completed' => 'badge-outline-success',
                                    'in-progress' => 'badge-outline-warning',
                                    'expired' => 'badge-outline-danger',
                                    default => 'badge-outline-secondary'
                                };
                            @endphp
                            <span class="badge {{ $cls }}">{{ ucfirst($r->status) }}</span>
                        </td>
                        <td class="text-right">{{ $r->cost ? number_format($r->cost, 2) : '—' }}</td>
                        <td class="flex gap-1">
                            <a href="{{ route('admin.hr.training-records.show', $r) }}" class="btn btn-xs btn-outline-info">View</a>
                            <a href="{{ route('admin.hr.training-records.edit', $r) }}" class="btn btn-xs btn-outline-secondary">Edit</a>
                            <form action="{{ route('admin.hr.training-records.destroy', $r) }}" method="POST" class="inline" onsubmit="return confirm('Delete this record?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-outline-danger">×</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-gray-400 py-4">No training records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $records->links() }}</div>
</div>
@endsection
