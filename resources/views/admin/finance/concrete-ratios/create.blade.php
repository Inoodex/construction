@extends('admin.layouts.master')

@section('title', 'Create Concrete Ratio')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Create Concrete Ratio</h2>
        <a href="{{ route('admin.finance.concrete-ratios.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.finance.concrete-ratios.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div class="form-group">
                    <label for="project_id">Project <span class="text-danger">*</span></label>
                    <select name="project_id" id="project_id" class="form-select" required>
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-input" required value="{{ old('title') }}" />
                </div>
                <div class="form-group">
                    <label for="grade">Grade</label>
                    <select name="grade" id="grade" class="form-select">
                        <option value="">Select Grade</option>
                        @foreach(\App\Constants\ConcreteRatioConstants::GRADES as $g)
                            <option value="{{ $g }}" {{ old('grade') == $g ? 'selected' : '' }}>{{ $g }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="waste_percent">Waste %</label>
                    <input type="number" name="waste_percent" id="waste_percent" class="form-input" value="{{ old('waste_percent') }}" step="0.01" min="0" max="100" />
                </div>
                <div class="form-group md:col-span-2">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-input" rows="2">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Create Concrete Ratio</button>
                <button type="reset" class="btn btn-outline-danger">Reset</button>
            </div>
        </form>
    </div>
@endsection
