@extends('admin.layouts.master')

@section('title', 'Mark Attendance')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Mark Attendance</h2>
        <a href="{{ route('admin.hr.attendance.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.hr.attendance.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="date" class="text-sm font-semibold">Date <span class="text-danger">*</span></label>
                <input type="date" name="date" id="date" class="form-input w-64" required value="{{ old('date', date('Y-m-d')) }}" />
            </div>

            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr><th>Employee</th><th>Status</th><th>Note</th></tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $emp)
                            <tr>
                                <td class="font-semibold">{{ $emp->full_name }}
                                    <input type="hidden" name="attendances[{{ $loop->index }}][employee_id]" value="{{ $emp->id }}" />
                                </td>
                                <td>
                                    <select name="attendances[{{ $loop->index }}][status]" class="form-select" required>
                                        <option value="present">Present</option>
                                        <option value="absent">Absent</option>
                                        <option value="late">Late</option>
                                        <option value="half-day">Half Day</option>
                                        <option value="holiday">Holiday</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="attendances[{{ $loop->index }}][note]" class="form-input" placeholder="Optional note" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-primary mt-6">Save Attendance</button>
        </form>
    </div>
@endsection
