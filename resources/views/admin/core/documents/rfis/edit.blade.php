@extends('admin.layouts.master')

@section('title', 'Edit RFI')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit RFI — {{ $rfi->rfi_number }}</h2>
        <a href="{{ route('admin.core.documents.rfis.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.core.documents.rfis.update', $rfi) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="project_id">Project <span class="text-danger">*</span></label>
                    <select name="project_id" id="project_id" class="form-select" required>
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $rfi->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                    @error('project_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="subject">Subject <span class="text-danger">*</span></label>
                    <input type="text" name="subject" id="subject" class="form-input" required value="{{ old('subject', $rfi->subject) }}" />
                    @error('subject') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group md:col-span-2">
                    <label for="question">Question <span class="text-danger">*</span></label>
                    <textarea name="question" id="question" class="form-textarea" rows="4" required>{{ old('question', $rfi->question) }}</textarea>
                    @error('question') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="drawing_id">Related Drawing</label>
                    <select name="drawing_id" id="drawing_id" class="form-select">
                        <option value="">None</option>
                        @foreach($drawings as $drawing)
                            <option value="{{ $drawing->id }}" {{ old('drawing_id', $rfi->drawing_id) == $drawing->id ? 'selected' : '' }}>{{ $drawing->drawing_number }} — {{ $drawing->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="priority">Priority <span class="text-danger">*</span></label>
                    <select name="priority" id="priority" class="form-select" required>
                        <option value="low" {{ old('priority', $rfi->priority) == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $rfi->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority', $rfi->priority) == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="assigned_to">Assign To</label>
                    <select name="assigned_to" id="assigned_to" class="form-select">
                        <option value="">Unassigned</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to', $rfi->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="due_date">Due Date</label>
                    <input type="date" name="due_date" id="due_date" class="form-input" value="{{ old('due_date', $rfi->due_date?->format('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="open" {{ old('status', $rfi->status) == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="answered" {{ old('status', $rfi->status) == 'answered' ? 'selected' : '' }}>Answered</option>
                        <option value="closed" {{ old('status', $rfi->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <div class="form-group md:col-span-2">
                    <label for="attachment">Attachment (replace existing)</label>
                    <input type="file" name="attachment" id="attachment" class="form-input" />
                    @error('attachment') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update RFI</button>
                <button type="reset" class="btn btn-outline-danger">Reset</button>
            </div>
        </form>
    </div>
@endsection
