@extends('admin.layouts.master')

@section('title', 'Edit Category')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Category</h2>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="type">Type <span class="text-danger">*</span></label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="">Select Type</option>
                        <option value="trade_category" {{ old('type', $category->type) == 'trade_category' ? 'selected' : '' }}>Trade Category</option>
                        <option value="resource_type" {{ old('type', $category->type) == 'resource_type' ? 'selected' : '' }}>Resource Type</option>
                        <option value="equipment_category" {{ old('type', $category->type) == 'equipment_category' ? 'selected' : '' }}>Equipment Category</option>
                        <option value="material_category" {{ old('type', $category->type) == 'material_category' ? 'selected' : '' }}>Material Category</option>
                        <option value="document_type" {{ old('type', $category->type) == 'document_type' ? 'selected' : '' }}>Document Type</option>
                        <option value="expense_type" {{ old('type', $category->type) == 'expense_type' ? 'selected' : '' }}>Expense Type</option>
                        <option value="skill_level" {{ old('type', $category->type) == 'skill_level' ? 'selected' : '' }}>Skill Level</option>
                        <option value="certification_type" {{ old('type', $category->type) == 'certification_type' ? 'selected' : '' }}>Certification Type</option>
                        <option value="incident_type" {{ old('type', $category->type) == 'incident_type' ? 'selected' : '' }}>Incident Type</option>
                        <option value="compliance_type" {{ old('type', $category->type) == 'compliance_type' ? 'selected' : '' }}>Compliance Type</option>
                    </select>
                    @error('type') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="value">Value <span class="text-danger">*</span></label>
                    <input type="text" name="value" id="value" class="form-input" required value="{{ old('value', $category->value) }}" placeholder="Internal identifier (e.g. steel)" />
                    <span class="text-xs text-white-dark mt-1 block">Lowercase, stored in DB</span>
                    @error('value') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="sort_order">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-input" value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0" />
                    @error('sort_order') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group mt-5">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} class="form-checkbox" />
                    <span>Active</span>
                </label>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update Category</button>
                <button type="button" onclick="window.location.href='{{ route('admin.categories.index') }}'" class="btn btn-outline-danger">Cancel</button>
            </div>
        </form>
    </div>

@endsection
