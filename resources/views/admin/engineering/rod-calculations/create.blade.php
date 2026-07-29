@extends('admin.layouts.master')

@section('title', 'Create Rod & Concrete Calculation Sheet')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold uppercase text-primary">Create Rod &amp; Concrete Calculation Sheet</h2>
            <p class="text-xs text-white-dark mt-0.5">Unify Structural Reinforcement (BBS) and Concrete Mix Volume &amp; Material Estimations in one sheet.</p>
        </div>
        <a href="{{ route('admin.finance.rod-calculations.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.finance.rod-calculations.store') }}" method="POST">
            @csrf

            {{-- 1. General Sheet Details --}}
            <div class="mb-6 border-b pb-4 dark:border-gray-700">
                <h5 class="text-sm font-bold uppercase tracking-wider text-primary mb-3">General Information</h5>
                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <div class="form-group">
                        <label for="project_id" class="text-xs font-semibold">Project <span class="text-danger">*</span></label>
                        <select name="project_id" id="project_id" class="form-select" required>
                            <option value="">Select Project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="title" class="text-xs font-semibold">Sheet Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-input" required value="{{ old('title') }}" placeholder="e.g. Ground Floor Columns &amp; Beams Structural Sheet" />
                    </div>
                    <div class="form-group">
                        <label for="revision" class="text-xs font-semibold">Revision / Drawing Ref</label>
                        <input type="text" name="revision" id="revision" class="form-input" value="{{ old('revision') }}" placeholder="e.g. Rev 01" />
                    </div>
                </div>
            </div>

            {{-- 2. Steel Reinforcement Parameters --}}
            <div class="mb-6 border-b pb-4 dark:border-gray-700">
                <h5 class="text-sm font-bold uppercase tracking-wider text-info mb-3">Steel Reinforcement (BBS) Parameters</h5>
                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <div class="form-group">
                        <label for="steel_grade" class="text-xs font-semibold">Steel Grade</label>
                        <input type="text" name="steel_grade" id="steel_grade" class="form-input" value="{{ old('steel_grade') }}" placeholder="e.g. Grade 60 (500W)" />
                    </div>
                    <div class="form-group">
                        <label for="waste_percent" class="text-xs font-semibold">Steel Cutting Waste (%)</label>
                        <input type="number" name="waste_percent" id="waste_percent" class="form-input" value="{{ old('waste_percent') }}" step="0.01" min="0" max="100" placeholder="e.g. 3.00" />
                    </div>
                    <div class="form-group">
                        <label for="steel_price_per_kg" class="text-xs font-semibold">Steel Price (৳ / kg)</label>
                        <input type="number" name="steel_price_per_kg" id="steel_price_per_kg" class="form-input" value="{{ old('steel_price_per_kg') }}" step="0.01" min="0" placeholder="e.g. 95 (৳95,000 / Ton)" />
                    </div>
                </div>
            </div>

            {{-- 3. Concrete Mix Design Parameters --}}
            <div class="mb-6 border-b pb-4 dark:border-gray-700">
                <h5 class="text-sm font-bold uppercase tracking-wider text-success mb-3">Concrete Mix Design &amp; Unit Pricing</h5>
                <div class="grid grid-cols-1 gap-5 md:grid-cols-4">
                    <div class="form-group">
                        <label for="concrete_grade" class="text-xs font-semibold">Concrete Grade (Ratio)</label>
                        <select name="concrete_grade" id="concrete_grade" class="form-select">
                            <option value="">Select Concrete Grade</option>
                            @foreach(App\Constants\ConcreteRatioConstants::GRADE_LABELS as $key => $label)
                                <option value="{{ $key }}" {{ old('concrete_grade') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="concrete_waste_percent" class="text-xs font-semibold">Concrete Wastage (%)</label>
                        <input type="number" name="concrete_waste_percent" id="concrete_waste_percent" class="form-input" value="{{ old('concrete_waste_percent') }}" step="0.01" min="0" max="100" placeholder="e.g. 2.00" />
                    </div>
                    <div class="form-group">
                        <label for="cement_price_per_bag" class="text-xs font-semibold">Cement Price (৳ / 50kg Bag)</label>
                        <input type="number" name="cement_price_per_bag" id="cement_price_per_bag" class="form-input" value="{{ old('cement_price_per_bag') }}" step="0.01" min="0" placeholder="e.g. 550" />
                    </div>
                    <div class="form-group">
                        <label for="sand_price_per_cft" class="text-xs font-semibold">Sand Price (৳ / CFT)</label>
                        <input type="number" name="sand_price_per_cft" id="sand_price_per_cft" class="form-input" value="{{ old('sand_price_per_cft') }}" step="0.01" min="0" placeholder="e.g. 45" />
                    </div>
                    <div class="form-group md:col-span-1">
                        <label for="aggregate_price_per_cft" class="text-xs font-semibold">Aggregate/Stone Price (৳ / CFT)</label>
                        <input type="number" name="aggregate_price_per_cft" id="aggregate_price_per_cft" class="form-input" value="{{ old('aggregate_price_per_cft') }}" step="0.01" min="0" placeholder="e.g. 110" />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="description" class="text-xs font-semibold">Description / Notes</label>
                <textarea name="description" id="description" class="form-input" rows="2" placeholder="General notes regarding members, concrete pouring specs, or structural drawings...">{{ old('description') }}</textarea>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10 font-bold">Save &amp; Add Structural Members</button>
                <button type="reset" class="btn btn-outline-danger">Reset</button>
            </div>
        </form>
    </div>
@endsection
