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
                            <td class="text-center">
                                <x-action-dropdown>
                                    <x-slot name="items">
                                        <li><a href="{{ route('admin.hr.wage-slips.show', $ws) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                        <li><a href="{{ route('admin.hr.wage-slips.pdf', $ws) }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> PDF</a></li>
                                        <li><a href="{{ route('admin.hr.wage-slips.print', $ws) }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg> Print</a></li>
                                        <li class="divider"></li>
                                        <li class="danger">
                                            <form action="{{ route('admin.hr.wage-slips.destroy', $ws) }}" method="POST" onsubmit="return confirm('Delete this wage slip?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 001-1h4a1 1 0 001 1m-6 0h6"/></svg> Delete</button>
                                            </form>
                                        </li>
                                    </x-slot>
                                </x-action-dropdown>
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
