@extends('admin.layouts.master')

@section('title', 'Attendance Summary')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Attendance Summary</h2>
        <a href="{{ route('admin.hr.attendance.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back
        </a>
    </div>

    <div class="panel mt-6">
        <form method="GET" class="mb-4 flex items-center gap-4">
            <div>
                <label class="text-sm font-semibold">Month</label>
                <select name="month" class="form-select" onchange="this.form.submit()">
                    @foreach($months as $val => $label)
                        <option value="{{ $val }}" {{ $val === $month ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th class="text-center text-green-600">Present</th>
                        <th class="text-center text-red-600">Absent</th>
                        <th class="text-center text-yellow-600">Late</th>
                        <th class="text-center text-blue-600">Half Day</th>
                        <th class="text-center text-gray-500">Holiday</th>
                        <th class="text-center">Total Hours</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($summary as $s)
                        <tr>
                            <td class="font-semibold">{{ $s['employee']->full_name }}</td>
                            <td class="text-center">{{ $s['present'] }}</td>
                            <td class="text-center">{{ $s['absent'] }}</td>
                            <td class="text-center">{{ $s['late'] }}</td>
                            <td class="text-center">{{ $s['half_day'] }}</td>
                            <td class="text-center">{{ $s['holiday'] }}</td>
                            <td class="text-center">{{ number_format($s['total_hours'], 1) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-gray-400 py-4">No records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
