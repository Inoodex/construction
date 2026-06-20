@extends('admin.layouts.master')

@section('title', 'Timesheets')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Timesheets</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.hr.timesheets.create') }}" class="btn btn-primary gap-2">+ New Entry</a>
        </div>
    </div>

    <div class="panel mt-6">
        <form method="GET" class="mb-4 grid grid-cols-4 gap-4">
            <div>
                <label class="text-sm font-semibold">Employee</label>
                <select name="employee_id" class="form-select" onchange="this.form.submit()">
                    <option value="">All</option>
                    @foreach($employees as $id => $name)
                        <option value="{{ $id }}" {{ request('employee_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-semibold">Project</label>
                <select name="project_id" class="form-select" onchange="this.form.submit()">
                    <option value="">All</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-semibold">From</label>
                <input type="date" name="date_from" class="form-input" value="{{ request('date_from') }}" onchange="this.form.submit()" />
            </div>
            <div>
                <label class="text-sm font-semibold">To</label>
                <input type="date" name="date_to" class="form-input" value="{{ request('date_to') }}" onchange="this.form.submit()" />
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Employee</th>
                        <th>Project</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Hours</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($timesheets as $t)
                        <tr>
                            <td>{{ $t->date->format('Y-m-d') }}</td>
                            <td class="font-semibold">{{ $t->employee->full_name }}</td>
                            <td>{{ $t->project?->name ?? '-' }}</td>
                            <td>{{ $t->start_time ? \Carbon\Carbon::parse($t->start_time)->format('H:i') : '-' }}</td>
                            <td>{{ $t->end_time ? \Carbon\Carbon::parse($t->end_time)->format('H:i') : '-' }}</td>
                            <td>{{ number_format($t->hours_worked, 1) }}</td>
                            <td><span class="badge badge-{{ $t->status === 'approved' ? 'success' : ($t->status === 'rejected' ? 'danger' : 'secondary') }}">{{ ucfirst($t->status) }}</span></td>
                            <td>
                                <form action="{{ route('admin.hr.timesheets.destroy', $t) }}" method="POST" class="inline" onsubmit="return confirm('Delete this entry?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-gray-400 py-4">No timesheet entries found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $timesheets->links() }}</div>
    </div>
@endsection
