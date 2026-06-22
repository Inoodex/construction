@extends('admin.layouts.master')

@section('title', 'Create Material Submittal')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Create Material Submittal</h2>
        <a href="{{ route('admin.procurement.material-submittals.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.procurement.material-submittals.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group md:col-span-2">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-input" required value="{{ old('title') }}" />
                </div>
                <div class="form-group">
                    <label for="project_id">Project</label>
                    <select name="project_id" id="project_id" class="form-select">
                        <option value="">No Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="material_name">Material Name <span class="text-danger">*</span></label>
                    <select name="material_name" id="material_name" class="form-select" required>
                        <option value="">Select Material</option>
                        @foreach($materials as $material)
                            <option value="{{ $material->name }}" {{ old('material_name') == $material->name ? 'selected' : '' }}>{{ $material->name }} ({{ $material->sku ?? 'no SKU' }})</option>
                        @endforeach
                    </select>
                </div>
                 <div class="form-group">
                    <label for="quantity_unit">Quantity / Unit</label>
                    <input type="text" name="quantity_unit" id="quantity_unit" class="form-input" value="{{ old('quantity_unit') }}" placeholder="e.g. 500 bags, 10 tons" />
                </div>
                <div class="form-group">
                    <label for="manufacturer">Manufacturer</label>
                    <input type="text" name="manufacturer" id="manufacturer" class="form-input" value="{{ old('manufacturer') }}" />
                </div>
                <div class="form-group">
                    <label for="brand">Brand</label>
                    <input type="text" name="brand" id="brand" class="form-input" value="{{ old('brand') }}" />
                </div>
                <div class="form-group">
                    <label for="model_reference">Model / Reference</label>
                    <input type="text" name="model_reference" id="model_reference" class="form-input" value="{{ old('model_reference') }}" />
                </div>
                <div class="form-group md:col-span-2">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-textarea" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="form-group md:col-span-2">
                    <label for="specification_details">Specification Details</label>
                    <textarea name="specification_details" id="specification_details" class="form-textarea" rows="4" placeholder="Technical specifications, test results, standards compliance...">{{ old('specification_details') }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Create Submittal</button>
                <a href="{{ route('admin.procurement.material-submittals.index') }}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </form>
    </div>
@endsection
