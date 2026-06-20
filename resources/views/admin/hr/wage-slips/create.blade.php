@extends('admin.layouts.master')

@section('title', 'Generate Wage Slips')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Generate Wage Slips</h2>
        <a href="{{ route('admin.hr.wage-slips.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
    </div>

    <div class="panel mt-6 max-w-2xl">
        <form action="{{ route('admin.hr.wage-slips.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="text-sm font-semibold">Pay Period (Month) <span class="text-danger">*</span></label>
                <select name="month" class="form-select" required>
                    <option value="">Select month</option>
                    @foreach($months as $val => $label)
                        <option value="{{ $val }}" {{ old('month') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('month') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="text-sm font-semibold">Select Employees <span class="text-danger">*</span></label>
                <div class="mt-1 flex items-center gap-2">
                    <label class="flex items-center gap-1 text-sm">
                        <input type="checkbox" onchange="toggleAll(this)" /> Select All
                    </label>
                </div>
                <div class="mt-2 grid grid-cols-2 gap-1 max-h-64 overflow-y-auto border rounded p-2">
                    @foreach($employees as $emp)
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="employee_ids[]" value="{{ $emp->id }}" class="emp-checkbox" />
                            {{ $emp->full_name }} ({{ $emp->employee_code }})
                        </label>
                    @endforeach
                </div>
                @error('employee_ids') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Generate Wage Slips</button>
        </form>
    </div>
@endsection

@push('scripts')
<script>
function toggleAll(el) {
    document.querySelectorAll('.emp-checkbox').forEach(cb => cb.checked = el.checked);
}
</script>
@endpush
