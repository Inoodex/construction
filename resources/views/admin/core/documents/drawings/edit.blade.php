@extends('admin.layouts.master')

@section('title', 'Edit Drawing')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Drawing — {{ $drawing->drawing_number }}</h2>
        <a href="{{ route('admin.core.documents.drawings.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.core.documents.drawings.update', $drawing) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="project_id">Project <span class="text-danger">*</span></label>
                    <select name="project_id" id="project_id" class="form-select" required>
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $drawing->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                    @error('project_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-input" required value="{{ old('title', $drawing->title) }}" />
                    @error('title') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="drawing_type">Drawing Type <span class="text-danger">*</span></label>
                    <select name="drawing_type" id="drawing_type" class="form-select" required>
                        @foreach(['architectural','structural','mep','shop','as_built','other'] as $type)
                            <option value="{{ $type }}" {{ old('drawing_type', $drawing->drawing_type) == $type ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="discipline">Discipline</label>
                    <input type="text" name="discipline" id="discipline" class="form-input" value="{{ old('discipline', $drawing->discipline) }}" />
                </div>
                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        @foreach(['draft','issued','superseded','obsolete'] as $status)
                            <option value="{{ $status }}" {{ old('status', $drawing->status) == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group md:col-span-2">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-textarea" rows="3">{{ old('description', $drawing->description) }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update Drawing</button>
                <button type="reset" class="btn btn-outline-danger">Reset</button>
            </div>
        </form>
    </div>
@endsection
