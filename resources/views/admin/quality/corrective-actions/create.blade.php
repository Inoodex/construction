@extends('admin.layouts.master')

@section('title', 'Create Corrective Action')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Create Corrective Action</h2>
        <a href="{{ route('admin.quality.corrective-actions.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.quality.corrective-actions.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">Project <span class="text-danger">*</span></label>
                    <select name="project_id" class="form-select" required>
                        <option value="">Select project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                    @error('project_id') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-input" required value="{{ old('title') }}" />
                    @error('title') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Link to NCR</label>
                    <select name="ncr_id" class="form-select">
                        <option value="">None</option>
                        @foreach($ncrs as $ncr)
                            <option value="{{ $ncr->id }}" {{ old('ncr_id') == $ncr->id ? 'selected' : '' }}>{{ $ncr->ncr_number }} - {{ Str::limit($ncr->title, 30) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Link to Punch List Item</label>
                    <select name="punch_list_item_id" class="form-select">
                        <option value="">None</option>
                        @foreach($punchListItems as $item)
                            <option value="{{ $item->id }}" {{ old('punch_list_item_id') == $item->id ? 'selected' : '' }}>{{ Str::limit($item->description, 50) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Description <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-textarea" rows="4" required>{{ old('description') }}</textarea>
                    @error('description') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Root Cause</label>
                    <textarea name="root_cause" class="form-textarea" rows="3">{{ old('root_cause') }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-semibold">Corrective Action</label>
                    <textarea name="corrective_action" class="form-textarea" rows="3">{{ old('corrective_action') }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-semibold">Preventive Action</label>
                    <textarea name="preventive_action" class="form-textarea" rows="3">{{ old('preventive_action') }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-semibold">Responsible Person</label>
                    <input type="text" name="responsible_person" class="form-input" value="{{ old('responsible_person') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Target Date</label>
                    <input type="date" name="target_date" class="form-input" value="{{ old('target_date') }}" />
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Notes</label>
                    <textarea name="notes" class="form-textarea" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Create CAR</button>
        </form>
    </div>
@endsection
