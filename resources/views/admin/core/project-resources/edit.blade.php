@extends('admin.layouts.master')

@section('title', 'Edit Resource')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Resource — {{ $project->name }}</h2>
        <a href="{{ route('admin.core.projects.resources.index', $project) }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to Resources
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.core.projects.resources.update', [$project, $resource]) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group md:col-span-2">
                    <label for="name">Resource Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-input" required value="{{ old('name', $resource->name) }}" />
                    @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="resource_type">Type <span class="text-danger">*</span></label>
                    <select name="resource_type" id="resource_type" class="form-select" required>
                        <option value="labor" {{ old('resource_type', $resource->resource_type) == 'labor' ? 'selected' : '' }}>Labor</option>
                        <option value="equipment" {{ old('resource_type', $resource->resource_type) == 'equipment' ? 'selected' : '' }}>Equipment</option>
                        <option value="material" {{ old('resource_type', $resource->resource_type) == 'material' ? 'selected' : '' }}>Material</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="unit">Unit</label>
                    <input type="text" name="unit" id="unit" class="form-input" value="{{ old('unit', $resource->unit) }}" />
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" id="quantity" class="form-input" step="0.01" min="0" required value="{{ old('quantity', $resource->quantity) }}" />
                </div>
                <div class="form-group">
                    <label for="unit_cost">Unit Cost (৳) <span class="text-danger">*</span></label>
                    <input type="number" name="unit_cost" id="unit_cost" class="form-input" step="0.01" min="0" required value="{{ old('unit_cost', $resource->unit_cost) }}" />
                </div>
                <div class="form-group md:col-span-2">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-textarea" rows="3">{{ old('description', $resource->description) }}</textarea>
                </div>
                <div class="form-group md:col-span-2">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-textarea" rows="3">{{ old('notes', $resource->notes) }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update Resource</button>
                <button type="reset" class="btn btn-outline-danger">Reset</button>
            </div>
        </form>
    </div>
@endsection
