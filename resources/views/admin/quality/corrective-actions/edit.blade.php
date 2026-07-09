@extends('admin.layouts.master')

@section('title', 'Edit Corrective Action')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit CAR: {{ $correctiveAction->car_number }}</h2>
        <a href="{{ route('admin.quality.corrective-actions.show', $correctiveAction) }}" class="btn btn-secondary gap-2">&larr; Back</a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.quality.corrective-actions.update', $correctiveAction) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">Project <span class="text-danger">*</span></label>
                    <select name="project_id" class="form-select" required>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $correctiveAction->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-input" required value="{{ old('title', $correctiveAction->title) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        @foreach(['open','in_progress','completed','verified','closed'] as $st)
                            <option value="{{ $st }}" {{ old('status', $correctiveAction->status) == $st ? 'selected' : '' }}>{{ str_replace('_', ' ', ucfirst($st)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Link to NCR</label>
                    <select name="ncr_id" class="form-select">
                        <option value="">None</option>
                        @foreach($ncrs as $ncr)
                            <option value="{{ $ncr->id }}" {{ old('ncr_id', $correctiveAction->ncr_id) == $ncr->id ? 'selected' : '' }}>{{ $ncr->ncr_number }} - {{ Str::limit($ncr->title, 30) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Link to Punch List Item</label>
                    <select name="punch_list_item_id" class="form-select">
                        <option value="">None</option>
                        @foreach($punchListItems as $item)
                            <option value="{{ $item->id }}" {{ old('punch_list_item_id', $correctiveAction->punch_list_item_id) == $item->id ? 'selected' : '' }}>{{ Str::limit($item->description, 50) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Description <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-textarea" rows="4" required>{{ old('description', $correctiveAction->description) }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-semibold">Root Cause</label>
                    <textarea name="root_cause" class="form-textarea" rows="3">{{ old('root_cause', $correctiveAction->root_cause) }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-semibold">Corrective Action</label>
                    <textarea name="corrective_action" class="form-textarea" rows="3">{{ old('corrective_action', $correctiveAction->corrective_action) }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-semibold">Preventive Action</label>
                    <textarea name="preventive_action" class="form-textarea" rows="3">{{ old('preventive_action', $correctiveAction->preventive_action) }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-semibold">Responsible Person</label>
                    <input type="text" name="responsible_person" class="form-input" value="{{ old('responsible_person', $correctiveAction->responsible_person) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Target Date</label>
                    <input type="date" name="target_date" class="form-input" value="{{ old('target_date', $correctiveAction->target_date?->format('Y-m-d')) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Completed Date</label>
                    <input type="date" name="completed_date" class="form-input" value="{{ old('completed_date', $correctiveAction->completed_date?->format('Y-m-d')) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Verified By</label>
                    <select name="verified_by" class="form-select">
                        <option value="">Select person</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('verified_by', $correctiveAction->verified_by) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Verified Date</label>
                    <input type="date" name="verified_date" class="form-input" value="{{ old('verified_date', $correctiveAction->verified_date?->format('Y-m-d')) }}" />
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Effectiveness Check</label>
                    <textarea name="effectiveness_check" class="form-textarea" rows="3">{{ old('effectiveness_check', $correctiveAction->effectiveness_check) }}</textarea>
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Notes</label>
                    <textarea name="notes" class="form-textarea" rows="2">{{ old('notes', $correctiveAction->notes) }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Update CAR</button>
        </form>
    </div>
@endsection
