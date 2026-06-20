@extends('admin.layouts.master')

@section('title', 'Create Task')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Create Task</h2>
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
        <form action="{{ route('admin.core.tasks.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group md:col-span-2">
                    <label for="name">Task Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-input" required
                        value="{{ old('name') }}" />
                    @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="project_id">Project <span class="text-danger">*</span></label>
                    <select name="project_id" id="project_id" class="form-select" required onchange="fetchSites(this.value);fetchPhases(this.value)">
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
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
                            <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
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
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>
                    @error('priority') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="review" {{ old('status') == 'review' ? 'selected' : '' }}>Review</option>
                        <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                    @error('status') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-input"
                        value="{{ old('start_date') }}" />
                </div>
                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-input"
                        value="{{ old('end_date') }}" />
                </div>
            </div>

            <div class="form-group mt-5">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-textarea" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="mt-6 border-t pt-6">
                <h5 class="mb-4 text-base font-semibold">Task Dependencies</h5>
                <select name="dependency_ids[]" class="form-select" multiple size="5">
                    @foreach($tasks as $t)
                        <option value="{{ $t->id }}" {{ in_array($t->id, old('dependency_ids', [])) ? 'selected' : '' }}>
                            [{{ $t->project?->name ?? 'N/A' }}] {{ $t->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Ctrl+click to select multiple tasks that this task depends on.</p>
            </div>

            <div class="mt-6 border-t pt-6">
                <h5 class="mb-4 text-base font-semibold">Resource Allocations</h5>
                <div id="resource-allocations">
                    <div class="text-sm text-white-dark">Select a project above to see available resources.</div>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Save Task</button>
                <button type="reset" class="btn btn-outline-danger">Reset Form</button>
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

function fetchSites(projectId) {
    const siteSelect = document.getElementById('site_id');
    siteSelect.innerHTML = '<option value="">Select Site</option>';
    if (!projectId) return;
    sites.filter(s => s.project_id == projectId).forEach(site => {
        const opt = document.createElement('option');
        opt.value = site.id;
        opt.textContent = site.name;
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
        container.innerHTML = '<div class="text-sm text-white-dark">No resources planned for this project. <a href="{{ route('admin.core.resources.index') }}" class="text-primary underline">Add resources first.</a></div>';
        return;
    }
    let html = '<div class="grid grid-cols-1 gap-4">';
    projectResources.forEach(r => {
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
                        <input type="checkbox" class="form-checkbox resource-toggle" data-resource-id="${r.id}" onchange="toggleResourceRow(${r.id})" />
                        Allocate
                    </label>
                </div>
                <div id="resource-row-${r.id}" class="mt-3 grid grid-cols-1 gap-3 md:grid-cols-4 hidden">
                    <input type="hidden" name="resource_allocations[${r.id}][project_resource_id]" value="${r.id}" />
                    <div class="form-group">
                        <label class="text-xs text-white-dark">Quantity${unitLabel}</label>
                        <input type="number" name="resource_allocations[${r.id}][allocated_quantity]" class="form-input" step="0.01" min="0" value="0" />
                    </div>
                    <div class="form-group">
                        <label class="text-xs text-white-dark">Start Date</label>
                        <input type="date" name="resource_allocations[${r.id}][start_date]" class="form-input" />
                    </div>
                    <div class="form-group">
                        <label class="text-xs text-white-dark">End Date</label>
                        <input type="date" name="resource_allocations[${r.id}][end_date]" class="form-input" />
                    </div>
                    <div class="form-group">
                        <label class="text-xs text-white-dark">Notes</label>
                        <input type="text" name="resource_allocations[${r.id}][notes]" class="form-input" placeholder="Optional" />
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
