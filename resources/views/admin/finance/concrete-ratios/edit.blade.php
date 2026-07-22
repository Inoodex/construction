@extends('admin.layouts.master')

@section('title', 'Edit Concrete Ratio')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit: {{ $concreteRatio->reference_no }}</h2>
        <a href="{{ route('admin.finance.concrete-ratios.show', $concreteRatio->id) }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to Details
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.finance.concrete-ratios.update', $concreteRatio->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div class="form-group">
                    <label class="text-xs text-white-dark">Reference No</label>
                    <p class="font-mono font-semibold text-primary">{{ $concreteRatio->reference_no }}</p>
                </div>
                <div class="form-group">
                    <label class="text-xs text-white-dark">Project</label>
                    <p class="font-semibold">{{ $concreteRatio->project->name }}</p>
                </div>
                <div class="form-group">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-input" required value="{{ old('title', $concreteRatio->title) }}" />
                </div>
                <div class="form-group">
                    <label for="grade">Grade</label>
                    <select name="grade" id="grade" class="form-select">
                        <option value="">Select Grade</option>
                        @foreach(\App\Constants\ConcreteRatioConstants::GRADES as $g)
                            <option value="{{ $g }}" {{ old('grade', $concreteRatio->grade) == $g ? 'selected' : '' }}>{{ $g }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="waste_percent">Waste %</label>
                    <input type="number" name="waste_percent" id="waste_percent" class="form-input" value="{{ old('waste_percent', $concreteRatio->waste_percent) }}" step="0.01" min="0" max="100" />
                </div>
                <div class="form-group md:col-span-3">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-input" rows="2">{{ old('description', $concreteRatio->description) }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update</button>
            </div>
        </form>
    </div>
@endsection
