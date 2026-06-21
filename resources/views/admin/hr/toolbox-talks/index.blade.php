@extends('admin.layouts.master')

@section('title', 'Toolbox Talks')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Toolbox Talks</h5>
        <a href="{{ route('admin.hr.toolbox-talks.create') }}" class="btn btn-primary">+ New Toolbox Talk</a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-100 p-3 text-green-700">{{ session('success') }}</div>
    @endif

    <form method="GET" class="mb-4 flex flex-nowrap items-center gap-2 overflow-x-auto">
        <select name="employee_id" class="form-select" onchange="this.form.submit()">
            <option value="">All Conducted By</option>
            @foreach($employees as $id => $name)
                <option value="{{ $id }}" {{ request('employee_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
        <input type="date" name="date_from" class="form-input" value="{{ request('date_from') }}" placeholder="From" onchange="this.form.submit()" />
        <input type="date" name="date_to" class="form-input" value="{{ request('date_to') }}" placeholder="To" onchange="this.form.submit()" />
        @if(request()->anyFilled(['employee_id', 'date_from', 'date_to']))
            <a href="{{ route('admin.hr.toolbox-talks.index') }}" class="btn btn-outline-danger btn-sm">Reset</a>
        @endif
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
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $r)
                    <tr>
                        <td class="text-xs whitespace-nowrap">{{ $r->date->format('d M Y') }}</td>
                        <td class="font-semibold">{{ $r->topic }}</td>
                        <td class="text-xs">{{ $r->employee?->full_name ?? '—' }}</td>
                        <td class="text-xs">{{ $r->duration_minutes ? $r->duration_minutes . ' min' : '—' }}</td>
                        <td class="text-xs">{{ $r->location ?? '—' }}</td>
                        <td class="flex gap-1">
                            <a href="{{ route('admin.hr.toolbox-talks.show', $r) }}" class="btn btn-xs btn-outline-info">View</a>
                            <a href="{{ route('admin.hr.toolbox-talks.edit', $r) }}" class="btn btn-xs btn-outline-secondary">Edit</a>
                            <form action="{{ route('admin.hr.toolbox-talks.destroy', $r) }}" method="POST" class="inline" onsubmit="return confirm('Delete this record?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-outline-danger">×</button>
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
