@extends('admin.layouts.master')

@section('title', 'Edit Phase')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Phase — {{ $project->name }}</h2>
        <a href="{{ route('admin.core.projects.phases.index', $project) }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to Phases
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.core.projects.phases.update', [$project, $phase]) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group md:col-span-2">
                    <label for="name">Phase Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-input" required value="{{ old('name', $phase->name) }}" />
                    @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="planned" {{ old('status', $phase->status) == 'planned' ? 'selected' : '' }}>Planned</option>
                        <option value="active" {{ old('status', $phase->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ old('status', $phase->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="delayed" {{ old('status', $phase->status) == 'delayed' ? 'selected' : '' }}>Delayed</option>
                    </select>
                    @error('status') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="order_index">Order</label>
                    <input type="number" name="order_index" id="order_index" class="form-input" min="0" value="{{ old('order_index', $phase->order_index) }}" />
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-input" value="{{ old('start_date', $phase->start_date?->format('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-input" value="{{ old('end_date', $phase->end_date?->format('Y-m-d')) }}" />
                </div>
            </div>

            <div class="form-group mt-5">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-textarea" rows="4">{{ old('description', $phase->description) }}</textarea>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update Phase</button>
                <button type="reset" class="btn btn-outline-danger">Reset</button>
            </div>
        </form>
    </div>
@endsection
