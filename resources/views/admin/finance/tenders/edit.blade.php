@extends('admin.layouts.master')

@section('title', 'Edit Tender')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Tender</h2>
        <a href="{{ route('admin.finance.tenders.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.finance.tenders.update', $tender->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div class="form-group">
                    <label for="project_id">Project <span class="text-danger">*</span></label>
                    <select name="project_id" id="project_id" class="form-select" required>
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $tender->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-input" required value="{{ old('title', $tender->title) }}" />
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="draft" {{ old('status', $tender->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="open" {{ old('status', $tender->status) == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="closed" {{ old('status', $tender->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                        <option value="awarded" {{ old('status', $tender->status) == 'awarded' ? 'selected' : '' }}>Awarded</option>
                        <option value="cancelled" {{ old('status', $tender->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="issue_date">Issue Date <span class="text-danger">*</span></label>
                    <input type="date" name="issue_date" id="issue_date" class="form-input" required value="{{ old('issue_date', $tender->issue_date->format('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="close_date">Close Date <span class="text-danger">*</span></label>
                    <input type="date" name="close_date" id="close_date" class="form-input" required value="{{ old('close_date', $tender->close_date->format('Y-m-d')) }}" />
                </div>
                <div class="form-group md:col-span-3">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-input" rows="2">{{ old('description', $tender->description) }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update Tender</button>
                <button type="reset" class="btn btn-outline-danger">Reset</button>
            </div>
        </form>
    </div>
@endsection
