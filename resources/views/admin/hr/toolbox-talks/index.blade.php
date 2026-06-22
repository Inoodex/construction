@extends('admin.layouts.master')

@section('title', 'Toolbox Talks')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Toolbox Talks</h2>
        <a href="{{ route('admin.hr.toolbox-talks.create') }}" class="btn btn-primary gap-2">+ New Toolbox Talk</a>
    </div>

    <div class="panel mt-6">
        @if(session('success'))
            <div class="mb-4 rounded-md bg-green-100 p-3 text-green-700">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-4 flex flex-nowrap items-end gap-2 overflow-x-auto">
            <div>
                <label class="text-xs font-semibold">Conducted By</label>
                <select name="employee_id" class="form-select" style="min-width: 220px">
                    <option value="">All Conducted By</option>
                    @foreach($employees as $id => $name)
                        <option value="{{ $id }}" {{ request('employee_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
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
                @if(request()->anyFilled(['employee_id', 'date_from', 'date_to']))
                    <a href="{{ route('admin.hr.toolbox-talks.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Topic</th>
                        <th>Conducted By</th>
                        <th>Duration</th>
                        <th>Location</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $r)
                        <tr>
                            <td>{{ $r->date->format('d M Y') }}</td>
                            <td>{{ $r->topic }}</td>
                            <td>{{ $r->employee?->full_name ?? '—' }}</td>
                            <td>{{ $r->duration_minutes ? $r->duration_minutes . ' min' : '—' }}</td>
                            <td>{{ $r->location ?? '—' }}</td>
                            <td class="flex items-center gap-1">
                                <a href="{{ route('admin.hr.toolbox-talks.show', $r) }}" class="btn btn-sm btn-outline-info">View</a>
                                <a href="{{ route('admin.hr.toolbox-talks.edit', $r) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form action="{{ route('admin.hr.toolbox-talks.destroy', $r) }}" method="POST" class="inline-flex" onsubmit="return confirm('Delete this record?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-gray-400 py-4">No toolbox talks found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $records->links() }}</div>
    </div>
@endsection
