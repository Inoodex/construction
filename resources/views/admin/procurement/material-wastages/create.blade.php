@extends('admin.layouts.master')

@section('title', 'Report Wastage')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Report Material Wastage</h2>
        <a href="{{ route('admin.procurement.material-wastages.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.procurement.material-wastages.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-4">
                <div class="form-group">
                    <label for="project_id">Project <span class="text-danger">*</span></label>
                    <select name="project_id" id="project_id" class="form-select" required onchange="fetchSites(this.value)">
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="site_id">Site <span class="text-danger">*</span></label>
                    <select name="site_id" id="site_id" class="form-select" required>
                        <option value="">Select Site</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="material_id">Material <span class="text-danger">*</span></label>
                    <select name="material_id" id="material_id" class="form-select" required>
                        <option value="">Select Material</option>
                        @foreach($materials as $material)
                            <option value="{{ $material->id }}" {{ old('material_id') == $material->id ? 'selected' : '' }}>{{ $material->name }} ({{ $material->unit }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity <span class="text-danger">*</span></label>
                    <input type="number" step="0.0001" min="0.0001" name="quantity" id="quantity" class="form-input" required value="{{ old('quantity') }}" />
                </div>
                <div class="form-group">
                    <label for="reported_date">Date <span class="text-danger">*</span></label>
                    <input type="date" name="reported_date" id="reported_date" class="form-input" required value="{{ old('reported_date', date('Y-m-d')) }}" />
                </div>
                <div class="form-group md:col-span-2">
                    <label for="reason">Reason <span class="text-danger">*</span></label>
                    <textarea name="reason" id="reason" class="form-input" rows="2" required maxlength="500">{{ old('reason') }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Record Wastage</button>
                <button type="reset" class="btn btn-outline-danger">Reset</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
const sites = @json($sites);
document.getElementById('project_id').addEventListener('change', function() {
    const siteSelect = document.getElementById('site_id');
    siteSelect.innerHTML = '<option value="">Select Site</option>';
    sites.filter(s => s.project_id == this.value).forEach(site => {
        const opt = document.createElement('option');
        opt.value = site.id;
        opt.textContent = site.name;
        if ('{{ old('site_id') }}' == site.id) opt.selected = true;
        siteSelect.appendChild(opt);
    });
});
</script>
@endpush
