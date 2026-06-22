@extends('admin.layouts.master')

@section('title', 'Training Records')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Training Records</h2>
        <a href="{{ route('admin.hr.training-records.create') }}" class="btn btn-primary gap-2">+ New Training Record</a>
    </div>

    <div class="panel mt-6">
        <form method="GET" class="mb-4 flex flex-nowrap items-end gap-2 overflow-x-auto">
            <div>
                <label class="text-xs font-semibold">Employee</label>
                <select name="employee_id" class="form-select" style="min-width: 200px">
                    <option value="">All Employees</option>
                    @foreach($employees as $id => $name)
                        <option value="{{ $id }}" {{ request('employee_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold">Status</label>
                <select name="status" class="form-select" style="min-width: 150px">
                    <option value="">All Status</option>
                    <option value="planned" {{ request('status') == 'planned' ? 'selected' : '' }}>Planned</option>
                    <option value="in-progress" {{ request('status') == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['employee_id', 'status']))
                    <a href="{{ route('admin.hr.training-records.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </div>
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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $r)
                        <tr>
                            <td>{{ $r->employee->full_name }}</td>
                            <td>{{ $r->training_name }}</td>
                            <td>{{ $r->provider ?? '—' }}</td>
                            <td>{{ $r->start_date->format('d M Y') }}</td>
                            <td>{{ $r->end_date?->format('d M Y') ?? '—' }}</td>
                            <td>{{ $r->expiry_date?->format('d M Y') ?? '—' }}</td>
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
                            <td class="flex items-center gap-1">
                                <a href="{{ route('admin.hr.training-records.show', $r) }}" class="btn btn-sm btn-outline-info">View</a>
                                <a href="{{ route('admin.hr.training-records.edit', $r) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form action="{{ route('admin.hr.training-records.destroy', $r) }}" method="POST" class="inline-flex" onsubmit="return confirm('Delete this record?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
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
