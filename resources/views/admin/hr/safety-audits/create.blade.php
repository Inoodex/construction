@extends('admin.layouts.master')

@section('title', 'New Safety Audit')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">New Safety Audit</h2>
        <a href="{{ route('admin.hr.safety-audits.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.hr.safety-audits.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">Project</label>
                    <select name="project_id" id="project_id" class="form-select">
                        <option value="">Select project</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}" {{ old('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Site</label>
                    <select name="site_id" id="site_id" class="form-select">
                        <option value="">Select site</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Auditor <span class="text-danger">*</span></label>
                    <select name="auditor_id" class="form-select" required>
                        <option value="">Select auditor</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ old('auditor_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                    @error('auditor_id') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Audit Date <span class="text-danger">*</span></label>
                    <input type="date" name="audit_date" class="form-input" required value="{{ old('audit_date', date('Y-m-d')) }}" />
                    @error('audit_date') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Audit Type <span class="text-danger">*</span></label>
                    <select name="audit_type" class="form-select" required>
                        <option value="">Select type</option>
                        <option value="internal" {{ old('audit_type') == 'internal' ? 'selected' : '' }}>Internal</option>
                        <option value="external" {{ old('audit_type') == 'external' ? 'selected' : '' }}>External</option>
                        <option value="regulatory" {{ old('audit_type') == 'regulatory' ? 'selected' : '' }}>Regulatory</option>
                        <option value="client" {{ old('audit_type') == 'client' ? 'selected' : '' }}>Client</option>
                        <option value="other" {{ old('audit_type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('audit_type') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="scheduled" {{ old('status', 'scheduled') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="follow_up" {{ old('status') == 'follow_up' ? 'selected' : '' }}>Follow Up</option>
                    </select>
                    @error('status') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Score (0-100)</label>
                    <input type="number" name="score" class="form-input" min="0" max="100" value="{{ old('score') }}" placeholder="0-100" />
                    @error('score') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Scope <span class="text-danger">*</span></label>
                    <input type="text" name="scope" class="form-input" required value="{{ old('scope') }}" placeholder="Scope of the audit" />
                    @error('scope') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Findings</label>
                    <textarea name="findings" class="form-textarea" rows="4">{{ old('findings') }}</textarea>
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Non-Conformances</label>
                    <textarea name="non_conformances" class="form-textarea" rows="4">{{ old('non_conformances') }}</textarea>
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Recommendations</label>
                    <textarea name="recommendations" class="form-textarea" rows="4">{{ old('recommendations') }}</textarea>
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Notes</label>
                    <textarea name="notes" class="form-textarea" rows="3">{{ old('notes') }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Save Audit</button>
        </form>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('project_id').addEventListener('change', function () {
    const projectId = this.value;
    const siteSelect = document.getElementById('site_id');
    siteSelect.innerHTML = '<option value="">Select site</option>';
    if (projectId) {
        fetch('{{ route('admin.hr.hse-checklists.sites-by-project') }}?project_id=' + projectId)
            .then(r => r.json())
            .then(sites => sites.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.textContent = s.name;
                siteSelect.appendChild(opt);
            }));
    }
});
</script>
@endpush
