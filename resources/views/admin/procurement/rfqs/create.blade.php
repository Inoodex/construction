@extends('admin.layouts.master')

@section('title', 'Create RFQ')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Create RFQ</h2>
        <a href="{{ route('admin.procurement.rfqs.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.procurement.rfqs.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div class="form-group md:col-span-2">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-input" required value="{{ old('title') }}" />
                </div>
                <div class="form-group">
                    <label for="project_id">Project</label>
                    <select name="project_id" id="project_id" class="form-select">
                        <option value="">No Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="issue_date">Issue Date <span class="text-danger">*</span></label>
                    <input type="date" name="issue_date" id="issue_date" class="form-input" required value="{{ old('issue_date', date('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="closing_date">Closing Date <span class="text-danger">*</span></label>
                    <input type="date" name="closing_date" id="closing_date" class="form-input" required value="{{ old('closing_date', date('Y-m-d', strtotime('+7 days'))) }}" />
                </div>
                <div class="form-group md:col-span-3">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-textarea" rows="3">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="mt-6">
                <label class="text-lg font-semibold">Items <span class="text-danger">*</span></label>
                <div id="items-container">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-2 items-end item-row">
                        <div class="form-group">
                            <label>Material</label>
                            <select name="items[0][material_id]" class="form-select" required>
                                <option value="">Select Material</option>
                                @foreach($materials as $material)
                                    <option value="{{ $material->id }}" data-unit="{{ $material->unit }}">{{ $material->name }} ({{ $material->unit }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" name="items[0][quantity]" class="form-input" step="0.01" min="0.01" required />
                        </div>
                        <div class="form-group">
                            <label>Unit</label>
                            <input type="text" class="form-input unit-display" readonly />
                        </div>
                        <div class="form-group flex items-end">
                            <button type="button" class="btn btn-outline-danger remove-item hidden">Remove</button>
                        </div>
                    </div>
                </div>
                <button type="button" id="add-item" class="btn btn-outline-secondary mt-2">+ Add Item</button>
            </div>

            <div class="mt-6">
                <label class="text-lg font-semibold">Invited Vendors <span class="text-danger">*</span></label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-2">
                    @foreach($vendors as $vendor)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="vendor_ids[]" value="{{ $vendor->id }}" class="form-checkbox" {{ in_array($vendor->id, old('vendor_ids', [])) ? 'checked' : '' }} />
                            <span>{{ $vendor->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('vendor_ids')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Create RFQ</button>
                <a href="{{ route('admin.procurement.rfqs.index') }}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
let itemIndex = 1;

document.getElementById('add-item').addEventListener('click', function() {
    const container = document.getElementById('items-container');
    const firstRow = container.querySelector('.item-row');
    const newRow = firstRow.cloneNode(true);

    newRow.querySelectorAll('input, select').forEach(el => {
        const name = el.getAttribute('name');
        if (name) el.setAttribute('name', name.replace(/\[\d+\]/, `[${itemIndex}]`));
        if (el.tagName === 'INPUT') el.value = '';
        if (el.tagName === 'SELECT') el.selectedIndex = 0;
    });

    newRow.querySelector('.remove-item').classList.remove('hidden');
    container.appendChild(newRow);
    itemIndex++;
});

document.addEventListener('change', function(e) {
    if (e.target.matches('[name*="[material_id]"]')) {
        const row = e.target.closest('.item-row');
        const selected = e.target.options[e.target.selectedIndex];
        const unit = selected.getAttribute('data-unit') || '';
        row.querySelector('.unit-display').value = unit;
    }
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-item')) {
        e.target.closest('.item-row').remove();
    }
});
</script>
@endpush
