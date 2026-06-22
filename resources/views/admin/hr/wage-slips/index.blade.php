@extends('admin.layouts.master')

@section('title', 'Wage Slips')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Wage Slips</h2>
        <a href="{{ route('admin.hr.wage-slips.create') }}" class="btn btn-primary gap-2">+ Generate Wage Slips</a>
    </div>

    <div class="panel mt-6">
        <form method="GET" class="mb-4 grid grid-cols-2 gap-4">
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
                <label class="text-sm font-semibold">Month</label>
                <select name="month" class="form-select" onchange="this.form.submit()">
                    <option value="">All</option>
                    @foreach($months as $val => $label)
                        <option value="{{ $val }}" {{ request('month') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Period</th>
                        <th>Present</th>
                        <th>Absent</th>
                        <th class="text-right">Basic</th>
                        <th class="text-right">Overtime</th>
                        <th class="text-right">Net Pay</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wageSlips as $ws)
                        <tr>
                            <td class="font-semibold">{{ $ws->employee->full_name }}</td>
                            <td>{{ $ws->period_start->format('M Y') }}</td>
                            <td>{{ $ws->present_days }}</td>
                            <td>{{ $ws->absent_days }}</td>
                            <td class="text-right">{{ number_format($ws->basic_pay, 2) }}</td>
                            <td class="text-right">{{ number_format($ws->overtime_pay, 2) }}</td>
                            <td class="text-right font-bold">{{ number_format($ws->net_pay, 2) }}</td>
                            <td><span class="badge badge-{{ $ws->status === 'paid' ? 'success' : ($ws->status === 'cancelled' ? 'danger' : 'secondary') }}">{{ ucfirst($ws->status) }}</span></td>
                            <td class="flex gap-1">
                                <a href="{{ route('admin.hr.wage-slips.show', $ws) }}" class="btn btn-sm btn-outline-info">View</a>
                                <a href="{{ route('admin.hr.wage-slips.print', $ws) }}" target="_blank" class="btn btn-sm btn-outline-primary">Print</a>
                                <form action="{{ route('admin.hr.wage-slips.destroy', $ws) }}" method="POST" class="inline" onsubmit="return confirm('Delete this wage slip?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-gray-400 py-4">No wage slips found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $wageSlips->links() }}</div>
    </div>
@endsection
