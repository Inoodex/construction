@extends('admin.layouts.master')

@section('title', 'Edit Fuel Log Entry')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Fuel Log Entry</h2>
        <a href="{{ route('admin.hr.fuel-logs.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.hr.fuel-logs.update', $fuelLog) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">Equipment <span class="text-danger">*</span></label>
                    <select name="equipment_id" id="equipment_id" class="form-select" required>
                        <option value="">Select equipment</option>
                        @foreach($equipment as $eq)
                            <option value="{{ $eq->id }}" {{ old('equipment_id', $fuelLog->equipment_id) == $eq->id ? 'selected' : '' }}>{{ $eq->name }} ({{ $eq->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Date <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-input" required value="{{ old('date', $fuelLog->date?->format('Y-m-d')) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Fuel Type <span class="text-danger">*</span></label>
                    <select name="fuel_type" class="form-select" required>
                        <option value="diesel" {{ old('fuel_type', $fuelLog->fuel_type) == 'diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="petrol" {{ old('fuel_type', $fuelLog->fuel_type) == 'petrol' ? 'selected' : '' }}>Petrol</option>
                        <option value="gas" {{ old('fuel_type', $fuelLog->fuel_type) == 'gas' ? 'selected' : '' }}>Gas</option>
                        <option value="other" {{ old('fuel_type', $fuelLog->fuel_type) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Unit <span class="text-danger">*</span></label>
                    <select name="unit" class="form-select" required>
                        <option value="liters" {{ old('unit', $fuelLog->unit) == 'liters' ? 'selected' : '' }}>Liters</option>
                        <option value="gallons" {{ old('unit', $fuelLog->unit) == 'gallons' ? 'selected' : '' }}>Gallons</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Quantity <span class="text-danger">*</span></label>
                    <input type="number" step="0.1" name="quantity" class="form-input" required value="{{ old('quantity', $fuelLog->quantity) }}" min="0" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Unit Cost <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="unit_cost" class="form-input" required value="{{ old('unit_cost', $fuelLog->unit_cost) }}" min="0" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Meter Hours</label>
                    <input type="number" name="meter_hours" class="form-input" value="{{ old('meter_hours', $fuelLog->meter_hours) }}" min="0" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Vendor</label>
                    <input type="text" name="vendor" id="vendor" class="form-input" value="{{ old('vendor', $fuelLog->vendor) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Receipt No.</label>
                    <input type="text" name="receipt_no" class="form-input" value="{{ old('receipt_no', $fuelLog->receipt_no) }}" />
                </div>
            </div>
            <div class="mt-4">
                <label class="text-sm font-semibold">Notes</label>
                <textarea name="notes" class="form-textarea" rows="2">{{ old('notes', $fuelLog->notes) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Update Entry</button>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('equipment_id').addEventListener('change', function() {
        var eqId = this.value;
        var vendorInput = document.getElementById('vendor');
        if (eqId) {
            fetch('/dashboard/hr/fuel-logs/equipment/' + eqId + '/details')
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    vendorInput.value = data.hire_vendor || data.last_vendor || '';
                });
        }
    });
</script>
@endpush
