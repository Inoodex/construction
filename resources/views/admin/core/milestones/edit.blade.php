@extends('admin.layouts.master')

@section('title', 'Edit Milestone')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Milestone — {{ $project->name }}</h2>
        <a href="{{ route('admin.core.projects.milestones.index', $project) }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to Milestones
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.core.projects.milestones.update', [$project, $milestone]) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group md:col-span-2">
                    <label for="name">Milestone Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-input" required value="{{ old('name', $milestone->name) }}" />
                    @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="phase_id">Phase</label>
                    <select name="phase_id" id="phase_id" class="form-select">
                        <option value="">No Phase</option>
                        @foreach($phases as $phase)
                            <option value="{{ $phase->id }}" {{ old('phase_id', $milestone->phase_id) == $phase->id ? 'selected' : '' }}>{{ $phase->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="pending" {{ old('status', $milestone->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="achieved" {{ old('status', $milestone->status) == 'achieved' ? 'selected' : '' }}>Achieved</option>
                        <option value="missed" {{ old('status', $milestone->status) == 'missed' ? 'selected' : '' }}>Missed</option>
                    </select>
                    @error('status') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="target_date">Target Date</label>
                    <input type="date" name="target_date" id="target_date" class="form-input" value="{{ old('target_date', $milestone->target_date?->format('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="achieved_date">Achieved Date</label>
                    <input type="date" name="achieved_date" id="achieved_date" class="form-input" value="{{ old('achieved_date', $milestone->achieved_date?->format('Y-m-d')) }}" />
                </div>
            </div>

            <div class="form-group mt-5">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-textarea" rows="4">{{ old('description', $milestone->description) }}</textarea>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update Milestone</button>
                <button type="reset" class="btn btn-outline-danger">Reset</button>
            </div>
        </form>
    </div>
@endsection
