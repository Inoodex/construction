@extends('admin.layouts.master')

@section('title', 'Labour Cost Allocation')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Labour Cost Allocation</h5>
        <a href="{{ route('admin.finance.labour-entries.create') }}" class="btn btn-primary">+ New Entry</a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-100 p-3 text-green-700">{{ session('success') }}</div>
    @endif

    {{-- <form method="GET" class="mb-4 flex flex-wrap items-center gap-3"> --}}
    <form method="GET" class="mb-4 flex flex-nowrap items-center gap-2 overflow-x-auto">
        <select name="project_id" class="form-select">
            <option value="">All Projects</option>
            @foreach($projects as $id => $name)
                <option value="{{ $id }}" {{ request('project_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
        <select name="employee_id" class="form-select">
            <option value="">All Employees</option>
            @foreach($employees as $id => $name)
                <option value="{{ $id }}" {{ request('employee_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
        <input type="date" name="date_from" class="form-input" value="{{ request('date_from') }}" />
        <input type="date" name="date_to" class="form-input" value="{{ request('date_to') }}" />
        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->anyFilled(['project_id', 'employee_id', 'date_from', 'date_to']))
            <a href="{{ route('admin.finance.labour-entries.index') }}" class="btn btn-outline-danger">Reset</a>
        @endif
    </form>

    @if($summary->count() > 0)
        <div class="mb-4 overflow-x-auto">
            <table class="table-hover w-full table-auto text-xs">
                <thead><tr><th>Project</th><th class="text-right">Total Hours</th><th class="text-right">Total Cost (৳)</th></tr></thead>
                <tbody>
                    @foreach($summary as $s)
                        <tr>
                            <td class="font-semibold">{{ $s->project?->name ?? 'Unknown' }}</td>
                            <td class="font-mono text-right">{{ number_format($s->total_hours, 2) }}</td>
                            <td class="font-mono text-right">{{ number_format($s->total_cost, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="table-hover w-full table-auto">
            <thead>
                <tr><th>Date</th><th>Project</th><th>Employee</th><th>Hours</th><th>Rate</th><th>Cost</th><th>Description</th><th class="text-center">Action</th></tr>
            </thead>
            <tbody>
                @forelse($entries as $e)
                    <tr>
                        <td>{{ $e->date->format('d M Y') }}</td>
                        <td class="font-semibold">{{ $e->project->name ?? '—' }}</td>
                        <td>{{ $e->employee->full_name ?? '—' }}</td>
                        <td class="font-mono">{{ number_format($e->hours, 2) }}</td>
                        <td class="font-mono">৳ {{ number_format($e->hourly_rate, 2) }}</td>
                        <td class="font-mono font-semibold">৳ {{ number_format($e->total_cost, 2) }}</td>
                        <td class="text-xs">{{ $e->description ?? '—' }}</td>
                        <td>
                            <form action="{{ route('admin.finance.labour-entries.destroy', $e) }}" method="POST" onsubmit="return confirm('Delete?');" class="flex justify-center">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-gray-500">No entries found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $entries->links() }}</div>
</div>
@endsection
