@extends('admin.layouts.master')

@section('title', 'Labour Cost Allocation')

@section('content')
<div class="panel">
        <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Labour Cost Allocation</h5>
        <div class="flex gap-2">
            <a href="{{ route('admin.finance.labour-entries.pdf', request()->query()) }}" target="_blank" class="btn btn-outline-success">PDF</a>
            <a href="{{ route('admin.finance.labour-entries.create') }}" class="btn btn-primary">+ New Entry</a>
        </div>
    </div>

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
                        <td class="text-center">
                            <x-action-dropdown>
                                <x-slot name="items">
                                    <li class="danger">
                                        <form action="{{ route('admin.finance.labour-entries.destroy', $e) }}" method="POST" onsubmit="return confirm('Delete?');">
                                            @csrf @method('DELETE')
                                            <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 001-1h4a1 1 0 001 1m-6 0h6"/></svg> Delete</button>
                                        </form>
                                    </li>
                                </x-slot>
                            </x-action-dropdown>
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
