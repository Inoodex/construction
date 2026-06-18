@extends('admin.layouts.master')

@section('title', 'Add Resource')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Add Resource — {{ $project->name }}</h2>
        <a href="{{ route('admin.core.projects.resources.index', $project) }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to Resources
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.core.projects.resources.store', $project) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group md:col-span-2">
                    <label for="name">Resource Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-input" required value="{{ old('name') }}" />
                    @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="resource_type">Type <span class="text-danger">*</span></label>
                    <select name="resource_type" id="resource_type" class="form-select" required>
                        <option value="">Select Type</option>
                        @foreach(\App\Models\Category::resourceTypes()->get() as $cat)
                            <option value="{{ $cat->value }}" {{ old('resource_type') == $cat->value ? 'selected' : '' }}>{{ $cat->label }}</option>
                        @endforeach
                    </select>
                    @error('resource_type') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="unit">Unit</label>
                    <input type="text" name="unit" id="unit" class="form-input" value="{{ old('unit') }}" placeholder="e.g. hours, days, pcs, kg" />
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" id="quantity" class="form-input" step="0.01" min="0" required value="{{ old('quantity', 0) }}" />
                </div>
                <div class="form-group">
                    <label for="unit_cost">Unit Cost (৳) <span class="text-danger">*</span></label>
                    <input type="number" name="unit_cost" id="unit_cost" class="form-input" step="0.01" min="0" required value="{{ old('unit_cost', 0) }}" />
                </div>
                <div class="form-group md:col-span-2">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-textarea" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="form-group md:col-span-2">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-textarea" rows="3">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Save Resource</button>
                <button type="reset" class="btn btn-outline-danger">Reset</button>
            </div>
        </form>
    </div>
@endsection
