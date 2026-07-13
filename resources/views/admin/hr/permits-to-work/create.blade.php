@extends('admin.layouts.master')

@section('title', 'New Permit to Work')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">New Permit to Work</h2>
        <a href="{{ route('admin.hr.permits-to-work.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.hr.permits-to-work.store') }}" method="POST">
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
                    <label class="text-sm font-semibold">Requested By <span class="text-danger">*</span></label>
                    <select name="requested_by" class="form-select" required>
                        <option value="">Select requester</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ old('requested_by') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                    @error('requested_by') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Permit Type <span class="text-danger">*</span></label>
                    <select name="permit_type" class="form-select" required>
                        <option value="">Select type</option>
                        <option value="hot_work" {{ old('permit_type') == 'hot_work' ? 'selected' : '' }}>Hot Work</option>
                        <option value="confined_space" {{ old('permit_type') == 'confined_space' ? 'selected' : '' }}>Confined Space</option>
                        <option value="working_at_height" {{ old('permit_type') == 'working_at_height' ? 'selected' : '' }}>Working at Height</option>
                        <option value="electrical" {{ old('permit_type') == 'electrical' ? 'selected' : '' }}>Electrical</option>
                        <option value="excavation" {{ old('permit_type') == 'excavation' ? 'selected' : '' }}>Excavation</option>
                        <option value="lifting" {{ old('permit_type') == 'lifting' ? 'selected' : '' }}>Lifting</option>
                        <option value="radiography" {{ old('permit_type') == 'radiography' ? 'selected' : '' }}>Radiography</option>
                        <option value="other" {{ old('permit_type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('permit_type') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Work Location <span class="text-danger">*</span></label>
                    <input type="text" name="work_location" class="form-input" required value="{{ old('work_location') }}" placeholder="Where the work will be performed" />
                    @error('work_location') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Valid From <span class="text-danger">*</span></label>
                    <input type="date" name="valid_from" class="form-input" required value="{{ old('valid_from', date('Y-m-d')) }}" />
                    @error('valid_from') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Valid Until <span class="text-danger">*</span></label>
                    <input type="date" name="valid_until" class="form-input" required value="{{ old('valid_until') }}" />
                    @error('valid_until') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Description of Work <span class="text-danger">*</span></label>
                    <textarea name="description_of_work" class="form-textarea" rows="4" required>{{ old('description_of_work') }}</textarea>
                    @error('description_of_work') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Hazards Identified <span class="text-danger">*</span></label>
                    <textarea name="hazards_identified" class="form-textarea" rows="4" required>{{ old('hazards_identified') }}</textarea>
                    @error('hazards_identified') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Safety Measures <span class="text-danger">*</span></label>
                    <textarea name="safety_measures" class="form-textarea" rows="4" required>{{ old('safety_measures') }}</textarea>
                    @error('safety_measures') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Conditions</label>
                    <textarea name="conditions" class="form-textarea" rows="3">{{ old('conditions') }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Save Permit</button>
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
