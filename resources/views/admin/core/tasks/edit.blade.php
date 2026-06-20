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

            <div class="mt-6 border-t pt-6">
                <h5 class="mb-4 text-base font-semibold">Resource Allocations</h5>
                <div id="resource-allocations">
                    <div class="text-sm text-white-dark">Select a project above to see available resources.</div>
                </div>
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
const allResources = @json($resources);
const existingAllocations = @json($task->resources->keyBy('project_resource_id'));
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

document.getElementById('project_id').addEventListener('change', function() {
    renderResourceAllocations(this.value);
});

function renderResourceAllocations(projectId) {
    const container = document.getElementById('resource-allocations');
    if (!projectId) {
        container.innerHTML = '<div class="text-sm text-white-dark">Select a project above to see available resources.</div>';
        return;
    }
    const projectResources = allResources.filter(r => r.project_id == projectId);
    if (projectResources.length === 0) {
        container.innerHTML = '<div class="text-sm text-white-dark">No resources planned for this project.</div>';
        return;
    }
    let html = '<div class="grid grid-cols-1 gap-4">';
    projectResources.forEach(r => {
        const alloc = existingAllocations[r.id];
        const checked = alloc ? 'checked' : '';
        const hiddenClass = alloc ? '' : 'hidden';
        const qty = alloc ? alloc.allocated_quantity : 0;
        const sd = alloc && alloc.start_date ? alloc.start_date.split(' ')[0] : '';
        const ed = alloc && alloc.end_date ? alloc.end_date.split(' ')[0] : '';
        const notes = alloc ? (alloc.notes || '') : '';
        const unitLabel = r.unit ? ' (' + r.unit + ')' : '';
        html += `
            <div class="rounded-lg border p-4 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="font-semibold">${r.name}</span>
                        <span class="ml-2 badge badge-outline-${r.resource_type === 'labor' ? 'info' : r.resource_type === 'equipment' ? 'warning' : 'primary'} capitalize text-xs">${r.resource_type}</span>
                        <span class="ml-2 text-xs text-white-dark">Available: ${parseFloat(r.quantity).toFixed(2)} ${r.unit || ''}</span>
                    </div>
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" class="form-checkbox resource-toggle" data-resource-id="${r.id}" onchange="toggleResourceRow(${r.id})" ${checked} />
                        Allocate
                    </label>
                </div>
                <div id="resource-row-${r.id}" class="mt-3 grid grid-cols-1 gap-3 md:grid-cols-4 ${hiddenClass}">
                    <input type="hidden" name="resource_allocations[${r.id}][project_resource_id]" value="${r.id}" />
                    <div class="form-group">
                        <label class="text-xs text-white-dark">Quantity${unitLabel}</label>
                        <input type="number" name="resource_allocations[${r.id}][allocated_quantity]" class="form-input" step="0.01" min="0" value="${qty}" />
                    </div>
                    <div class="form-group">
                        <label class="text-xs text-white-dark">Start Date</label>
                        <input type="date" name="resource_allocations[${r.id}][start_date]" class="form-input" value="${sd}" />
                    </div>
                    <div class="form-group">
                        <label class="text-xs text-white-dark">End Date</label>
                        <input type="date" name="resource_allocations[${r.id}][end_date]" class="form-input" value="${ed}" />
                    </div>
                    <div class="form-group">
                        <label class="text-xs text-white-dark">Notes</label>
                        <input type="text" name="resource_allocations[${r.id}][notes]" class="form-input" placeholder="Optional" value="${notes}" />
                    </div>
                </div>
            </div>
        `;
    });
    html += '</div>';
    container.innerHTML = html;
}

function toggleResourceRow(id) {
    const row = document.getElementById('resource-row-' + id);
    row.classList.toggle('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    const pid = document.getElementById('project_id').value;
    if (pid) renderResourceAllocations(pid);
});
</script>
@endpush
