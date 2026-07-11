@extends('admin.layouts.master')

@section('title', 'New Change Order')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">New Change Order</h2>
        <a href="{{ route('admin.core.documents.change-orders.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.core.documents.change-orders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="project_id">Project <span class="text-danger">*</span></label>
                    <select name="project_id" id="project_id" class="form-select" required>
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                    @error('project_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="type">Type <span class="text-danger">*</span></label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="variation" {{ old('type') == 'variation' ? 'selected' : '' }}>Variation</option>
                        <option value="change_order" {{ old('type') == 'change_order' ? 'selected' : '' }}>Change Order</option>
                        <option value="extension" {{ old('type') == 'extension' ? 'selected' : '' }}>Extension</option>
                    </select>
                </div>
                <div class="form-group md:col-span-2">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-input" required value="{{ old('title') }}" />
                    @error('title') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group md:col-span-2">
                    <label for="description">Description <span class="text-danger">*</span></label>
                    <textarea name="description" id="description" class="form-textarea" rows="4" required>{{ old('description') }}</textarea>
                    @error('description') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="cost_impact">Cost Impact</label>
                    <input type="number" name="cost_impact" id="cost_impact" class="form-input" step="0.01" min="0" value="{{ old('cost_impact') }}" />
                </div>
                <div class="form-group">
                    <label for="time_impact_days">Time Impact (Days)</label>
                    <input type="number" name="time_impact_days" id="time_impact_days" class="form-input" min="0" value="{{ old('time_impact_days') }}" />
                </div>
                <div class="form-group">
                    <label for="rfi_id">Related RFI</label>
                    <select name="rfi_id" id="rfi_id" class="form-select">
                        <option value="">None</option>
                        @foreach($rfis as $rfi)
                            <option value="{{ $rfi->id }}" {{ old('rfi_id') == $rfi->id ? 'selected' : '' }}>{{ $rfi->rfi_number }} — {{ $rfi->subject }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="attachment">Attachment</label>
                    <input type="file" name="attachment" id="attachment" class="form-input" />
                </div>
                <div class="form-group md:col-span-2">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-textarea" rows="3">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Create Change Order</button>
                <button type="reset" class="btn btn-outline-danger">Reset</button>
            </div>
        </form>
    </div>
@endsection
