@extends('admin.layouts.master')

@section('title', 'Create Material')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Create Material</h2>
        <a href="{{ route('admin.procurement.materials.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.procurement.materials.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="name">Material Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-input" required
                        value="{{ old('name') }}" />
                    @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="sku">SKU</label>
                    <input type="text" name="sku" id="sku" class="form-input" value="{{ old('sku') }}" placeholder="e.g. CMT-001" />
                    @error('sku') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="unit">Unit <span class="text-danger">*</span></label>
                    <select name="unit" id="unit" class="form-select" required>
                        <option value="">Select Unit</option>
                        <option value="pcs" {{ old('unit') == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                        <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                        <option value="ton" {{ old('unit') == 'ton' ? 'selected' : '' }}>Ton</option>
                        <option value="bag" {{ old('unit') == 'bag' ? 'selected' : '' }}>Bag</option>
                        <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>Liter</option>
                        <option value="m3" {{ old('unit') == 'm3' ? 'selected' : '' }}>Cubic Meter (m³)</option>
                        <option value="sqm" {{ old('unit') == 'sqm' ? 'selected' : '' }}>Square Meter (m²)</option>
                        <option value="m" {{ old('unit') == 'm' ? 'selected' : '' }}>Meter (m)</option>
                        <option value="ft" {{ old('unit') == 'ft' ? 'selected' : '' }}>Feet (ft)</option>
                        <option value="roll" {{ old('unit') == 'roll' ? 'selected' : '' }}>Roll</option>
                    </select>
                    @error('unit') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="reorder_level">Reorder Level</label>
                    <input type="number" step="0.0001" min="0" name="reorder_level" id="reorder_level" class="form-input"
                        value="{{ old('reorder_level') }}" placeholder="e.g. 100" />
                    <p class="text-xs text-white-dark mt-1">Default minimum stock threshold when creating stock entries</p>
                </div>
            </div>

            <div class="form-group mt-5">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-textarea" rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Save Material</button>
                <button type="reset" class="btn btn-outline-danger">Reset Form</button>
            </div>
        </form>
    </div>
@endsection
