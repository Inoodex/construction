@extends('admin.layouts.master')

@section('title', 'Register Equipment')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Register Equipment</h2>
        <a href="{{ route('admin.hr.equipment.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
    </div>

    <div class="panel mt-6 max-w-3xl">
        <form action="{{ route('admin.hr.equipment.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">Equipment Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-input" required value="{{ old('code') }}" placeholder="e.g. EQ-001" />
                    @error('code') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-input" required value="{{ old('name') }}" placeholder="e.g. Excavator CAT 320" />
                    @error('name') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Category</label>
                    <input type="text" name="category" class="form-input" value="{{ old('category') }}" placeholder="e.g. Excavator, Crane, Mixer" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Make</label>
                    <input type="text" name="make" class="form-input" value="{{ old('make') }}" placeholder="e.g. Caterpillar" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Model</label>
                    <input type="text" name="model" class="form-input" value="{{ old('model') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Year</label>
                    <input type="number" name="year" class="form-input" value="{{ old('year') }}" min="1900" max="2099" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Serial Number</label>
                    <input type="text" name="serial_number" class="form-input" value="{{ old('serial_number') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Acquisition Type <span class="text-danger">*</span></label>
                    <select name="acquisition_type" class="form-select" required>
                        <option value="owned" {{ old('acquisition_type') == 'owned' ? 'selected' : '' }}>Owned</option>
                        <option value="hired" {{ old('acquisition_type') == 'hired' ? 'selected' : '' }}>Hired</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Purchase Cost <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="purchase_cost" class="form-input" required value="{{ old('purchase_cost', 0) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Purchase Date</label>
                    <input type="date" name="purchase_date" class="form-input" value="{{ old('purchase_date') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Useful Life (Years) <span class="text-danger">*</span></label>
                    <input type="number" name="useful_life_years" class="form-input" required value="{{ old('useful_life_years', 5) }}" min="1" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Salvage Value <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="salvage_value" class="form-input" required value="{{ old('salvage_value', 0) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="under-maintenance" {{ old('status') == 'under-maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                        <option value="retired" {{ old('status') == 'retired' ? 'selected' : '' }}>Retired</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Location</label>
                    <input type="text" name="location" class="form-input" value="{{ old('location') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Operator</label>
                    <input type="text" name="operator" class="form-input" value="{{ old('operator') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Meter Hours <span class="text-danger">*</span></label>
                    <input type="number" name="meter_hours" class="form-input" required value="{{ old('meter_hours', 0) }}" min="0" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Maintenance Interval (Hrs)</label>
                    <input type="number" name="maintenance_interval_hours" class="form-input" value="{{ old('maintenance_interval_hours') }}" min="0" placeholder="e.g. 250" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Next Maintenance (Hrs)</label>
                    <input type="number" name="next_maintenance_hours" class="form-input" value="{{ old('next_maintenance_hours') }}" min="0" />
                </div>
            </div>

            <div class="mt-4">
                <label class="text-sm font-semibold">Notes</label>
                <textarea name="notes" class="form-textarea" rows="3">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary mt-4">Register Equipment</button>
        </form>
    </div>
@endsection
