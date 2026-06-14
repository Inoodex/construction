@extends('admin.layouts.master')

@section('title', 'Edit Work Order')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Work Order — {{ $workOrder->work_order_number }}</h2>
        <a href="{{ route('admin.core.work-orders.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.core.work-orders.update', $workOrder) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group md:col-span-2">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-input" required value="{{ old('title', $workOrder->title) }}" />
                </div>
                <div class="form-group">
                    <label for="project_id">Project <span class="text-danger">*</span></label>
                    <select name="project_id" id="project_id" class="form-select" required>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $workOrder->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="task_id">Related Task</label>
                    <select name="task_id" id="task_id" class="form-select">
                        <option value="">No Task</option>
                        @foreach($tasks as $task)
                            <option value="{{ $task->id }}" {{ old('task_id', $workOrder->task_id) == $task->id ? 'selected' : '' }}>{{ $task->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="site_id">Site</label>
                    <select name="site_id" id="site_id" class="form-select">
                        <option value="">Select Site</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ old('site_id', $workOrder->site_id) == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="assigned_to">Assign To</label>
                    <select name="assigned_to" id="assigned_to" class="form-select">
                        <option value="">Unassigned</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to', $workOrder->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="draft" {{ old('status', $workOrder->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="issued" {{ old('status', $workOrder->status) == 'issued' ? 'selected' : '' }}>Issued</option>
                        <option value="in_progress" {{ old('status', $workOrder->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ old('status', $workOrder->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $workOrder->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="issue_date">Issue Date</label>
                    <input type="date" name="issue_date" id="issue_date" class="form-input" value="{{ old('issue_date', $workOrder->issue_date?->format('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="due_date">Due Date</label>
                    <input type="date" name="due_date" id="due_date" class="form-input" value="{{ old('due_date', $workOrder->due_date?->format('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="completed_date">Completed Date</label>
                    <input type="date" name="completed_date" id="completed_date" class="form-input" value="{{ old('completed_date', $workOrder->completed_date?->format('Y-m-d')) }}" />
                </div>
                <div class="form-group md:col-span-2">
                    <label for="instructions">Work Instructions</label>
                    <textarea name="instructions" id="instructions" class="form-textarea" rows="4">{{ old('instructions', $workOrder->instructions) }}</textarea>
                </div>
                <div class="form-group md:col-span-2">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-textarea" rows="3">{{ old('notes', $workOrder->notes) }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update</button>
            </div>
        </form>
    </div>
@endsection
