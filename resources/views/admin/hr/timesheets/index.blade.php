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
        <form method="GET" class="mb-4 flex flex-nowrap items-end gap-2 overflow-x-auto">
            <div>
                <label class="text-xs font-semibold">Employee</label>
                <select name="employee_id" class="form-select">
                    <option value="">All</option>
                    @foreach($employees as $id => $name)
                        <option value="{{ $id }}" {{ request('employee_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold">Project</label>
                <select name="project_id" class="form-select">
                    <option value="">All</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold">From</label>
                <input type="date" name="date_from" class="form-input" value="{{ request('date_from') }}" />
            </div>
            <div>
                <label class="text-xs font-semibold">To</label>
                <input type="date" name="date_to" class="form-input" value="{{ request('date_to') }}" />
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['employee_id', 'project_id', 'date_from', 'date_to']))
                    <a href="{{ route('admin.hr.timesheets.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
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
                            <td><span class="badge badge-outline-{{ $t->status === 'approved' ? 'success' : ($t->status === 'rejected' ? 'danger' : 'secondary') }}">{{ ucfirst($t->status) }}</span></td>
                            <td class="text-center">
                                <x-action-dropdown>
                                    <x-slot name="items">
                                        <li class="danger">
                                            <form action="{{ route('admin.hr.timesheets.destroy', $t) }}" method="POST" onsubmit="return confirm('Delete this entry?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 001-1h4a1 1 0 001 1m-6 0h6"/></svg> Delete</button>
                                            </form>
                                        </li>
                                    </x-slot>
                                </x-action-dropdown>
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
