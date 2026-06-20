@extends('admin.layouts.master')

@section('title', 'Edit Material Submittal')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Submittal: {{ $materialSubmittal->submittal_number }}</h2>
        <a href="{{ route('admin.procurement.material-submittals.show', $materialSubmittal) }}" class="btn btn-secondary gap-2">Back</a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.procurement.material-submittals.update', $materialSubmittal) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group md:col-span-2">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-input" required value="{{ old('title', $materialSubmittal->title) }}" />
                </div>
                <div class="form-group">
                    <label for="project_id">Project</label>
                    <select name="project_id" id="project_id" class="form-select">
                        <option value="">No Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $materialSubmittal->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="quantity_unit">Quantity / Unit</label>
                    <input type="text" name="quantity_unit" id="quantity_unit" class="form-input" value="{{ old('quantity_unit', $materialSubmittal->quantity_unit) }}" />
                </div>
                <div class="form-group">
                    <label for="material_name">Material Name <span class="text-danger">*</span></label>
                    <input type="text" name="material_name" id="material_name" class="form-input" required value="{{ old('material_name', $materialSubmittal->material_name) }}" />
                </div>
                <div class="form-group">
                    <label for="manufacturer">Manufacturer</label>
                    <input type="text" name="manufacturer" id="manufacturer" class="form-input" value="{{ old('manufacturer', $materialSubmittal->manufacturer) }}" />
                </div>
                <div class="form-group">
                    <label for="brand">Brand</label>
                    <input type="text" name="brand" id="brand" class="form-input" value="{{ old('brand', $materialSubmittal->brand) }}" />
                </div>
                <div class="form-group">
                    <label for="model_reference">Model / Reference</label>
                    <input type="text" name="model_reference" id="model_reference" class="form-input" value="{{ old('model_reference', $materialSubmittal->model_reference) }}" />
                </div>
                <div class="form-group md:col-span-2">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-textarea" rows="3">{{ old('description', $materialSubmittal->description) }}</textarea>
                </div>
                <div class="form-group md:col-span-2">
                    <label for="specification_details">Specification Details</label>
                    <textarea name="specification_details" id="specification_details" class="form-textarea" rows="4">{{ old('specification_details', $materialSubmittal->specification_details) }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update Submittal</button>
                <a href="{{ route('admin.procurement.material-submittals.show', $materialSubmittal) }}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </form>
    </div>
@endsection
