@extends('admin.layouts.master')

@section('title', 'Edit Task')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Task</h2>
        <a href="{{ route('admin.core.tasks.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.core.tasks.update', $task->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group md:col-span-2">
                    <label for="name">Task Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-input" required
                        value="{{ old('name', $task->name) }}" />
                    @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="project_id">Project <span class="text-danger">*</span></label>
                    <select name="project_id" id="project_id" class="form-select" required onchange="fetchSites(this.value);fetchPhases(this.value)">
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                    @error('project_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="site_id">Site</label>
                    <select name="site_id" id="site_id" class="form-select">
                        <option value="">Select Site</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="assigned_to">Assign To</label>
                    <select name="assigned_to" id="assigned_to" class="form-select">
                        <option value="">Unassigned</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to', $task->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="phase_id">Phase</label>
                    <select name="phase_id" id="phase_id" class="form-select">
                        <option value="">Select Phase</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="milestone_id">Milestone</label>
                    <select name="milestone_id" id="milestone_id" class="form-select">
                        <option value="">Select Milestone</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="priority">Priority <span class="text-danger">*</span></label>
                    <select name="priority" id="priority" class="form-select" required>
                        <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>High</option>
                        <option value="critical" {{ old('priority', $task->priority) == 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>
                    @error('priority') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="open" {{ old('status', $task->status) == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="review" {{ old('status', $task->status) == 'review' ? 'selected' : '' }}>Review</option>
                        <option value="closed" {{ old('status', $task->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                    @error('status') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="progress_percent">Progress (%)</label>
                    <input type="number" min="0" max="100" name="progress_percent" id="progress_percent" class="form-input"
                        value="{{ old('progress_percent', $task->progress_percent) }}" />
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-input"
                        value="{{ old('start_date', $task->start_date?->format('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-input"
                        value="{{ old('end_date', $task->end_date?->format('Y-m-d')) }}" />
                </div>
            </div>

            <div class="form-group mt-5">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-textarea" rows="4">{{ old('description', $task->description) }}</textarea>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update Task</button>
                <button type="button" onclick="window.location.href='{{ route('admin.core.tasks.index') }}'"
                    class="btn btn-outline-danger">Cancel</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
const sites = @json($sites);
const phases = @json($phases);
const milestones = @json($milestones);
const currentPhase = '{{ old('phase_id', $task->phase_id) }}';
const currentMilestone = '{{ old('milestone_id', $task->milestone_id) }}';

fetchSites(document.getElementById('project_id').value);
fetchPhases(document.getElementById('project_id').value);

function fetchSites(projectId) {
    const siteSelect = document.getElementById('site_id');
    if (!projectId) return;
    const current = '{{ old('site_id', $task->site_id) }}';
    siteSelect.innerHTML = '<option value="">Select Site</option>';
    sites.filter(s => s.project_id == projectId).forEach(site => {
        const opt = document.createElement('option');
        opt.value = site.id;
        opt.textContent = site.name;
        if (site.id == current) opt.selected = true;
        siteSelect.appendChild(opt);
    });
}
function fetchPhases(projectId) {
    const phaseSelect = document.getElementById('phase_id');
    phaseSelect.innerHTML = '<option value="">Select Phase</option>';
    document.getElementById('milestone_id').innerHTML = '<option value="">Select Milestone</option>';
    if (!projectId) return;
    phases.filter(p => p.project_id == projectId).forEach(phase => {
        const opt = document.createElement('option');
        opt.value = phase.id;
        opt.textContent = phase.name;
        if (phase.id == currentPhase) opt.selected = true;
        phaseSelect.appendChild(opt);
    });
    fetchMilestones(projectId);
}
function fetchMilestones(projectId) {
    const msSelect = document.getElementById('milestone_id');
    msSelect.innerHTML = '<option value="">Select Milestone</option>';
    if (!projectId) return;
    milestones.filter(m => m.project_id == projectId).forEach(ms => {
        const opt = document.createElement('option');
        opt.value = ms.id;
        opt.textContent = ms.name;
        if (ms.id == currentMilestone) opt.selected = true;
        msSelect.appendChild(opt);
    });
}
</script>
@endpush
