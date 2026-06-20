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
            <div class="mb-4 flex items-center gap-4">
                <div>
                    <label for="date" class="text-sm font-semibold">Date <span class="text-danger">*</span></label>
                    <input type="date" name="date" id="date" class="form-input" required value="{{ old('date', date('Y-m-d')) }}" onchange="loadExisting(this.value)" />
                </div>
                <div class="flex gap-2 mt-6">
                    <button type="button" onclick="setAll('present')" class="btn btn-sm btn-outline-success">All Present</button>
                    <button type="button" onclick="setAll('absent')" class="btn btn-sm btn-outline-danger">All Absent</button>
                    <button type="button" onclick="setAll('late')" class="btn btn-sm btn-outline-warning">All Late</button>
                    <button type="button" onclick="setAll('half-day')" class="btn btn-sm btn-outline-info">All Half Day</button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto" id="attendance-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Status</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $emp)
                            <tr>
                                <td class="font-semibold">{{ $emp->full_name }}
                                    <input type="hidden" name="attendances[{{ $loop->index }}][employee_id]" value="{{ $emp->id }}" />
                                </td>
                                <td>
                                    <select name="attendances[{{ $loop->index }}][status]" class="form-select status-select" required>
                                        <option value="present">Present</option>
                                        <option value="absent">Absent</option>
                                        <option value="late">Late</option>
                                        <option value="half-day">Half Day</option>
                                        <option value="holiday">Holiday</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="time" name="attendances[{{ $loop->index }}][clock_in]" class="form-input clock-in" />
                                </td>
                                <td>
                                    <input type="time" name="attendances[{{ $loop->index }}][clock_out]" class="form-input clock-out" />
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

@push('scripts')
<script>
function setAll(status) {
    document.querySelectorAll('.status-select').forEach(s => s.value = status);
}

function loadExisting(date) {
    if (!date) return;
    fetch(`{{ route('admin.hr.attendance.index') }}?date=${date}&json=1`)
        .then(r => r.json())
        .catch(() => {});
}
</script>
@endpush
