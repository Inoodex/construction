@extends('admin.layouts.master')

@section('title', 'New HSE Checklist')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">New HSE Checklist</h2>
        <a href="{{ route('admin.hr.hse-checklists.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.hr.hse-checklists.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-input" required value="{{ old('title') }}" />
                    @error('title') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Checklist Type <span class="text-danger">*</span></label>
                    <select name="checklist_type" class="form-select" required>
                        <option value="general" {{ old('checklist_type', 'general') == 'general' ? 'selected' : '' }}>General</option>
                        <option value="fire" {{ old('checklist_type') == 'fire' ? 'selected' : '' }}>Fire Safety</option>
                        <option value="electrical" {{ old('checklist_type') == 'electrical' ? 'selected' : '' }}>Electrical</option>
                        <option value="scaffolding" {{ old('checklist_type') == 'scaffolding' ? 'selected' : '' }}>Scaffolding</option>
                        <option value="ppe" {{ old('checklist_type') == 'ppe' ? 'selected' : '' }}>PPE Compliance</option>
                        <option value="excavation" {{ old('checklist_type') == 'excavation' ? 'selected' : '' }}>Excavation</option>
                        <option value="other" {{ old('checklist_type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Inspector</label>
                    <select name="employee_id" class="form-select">
                        <option value="">Select inspector</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Location</label>
                    <input type="text" name="location" class="form-input" value="{{ old('location') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Inspection Date <span class="text-danger">*</span></label>
                    <input type="date" name="inspection_date" class="form-input" required value="{{ old('inspection_date', date('Y-m-d')) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="open" {{ old('status', 'open') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Closure Date</label>
                    <input type="date" name="closure_date" class="form-input" value="{{ old('closure_date') }}" />
                </div>
            </div>

            <div class="mt-4">
                <label class="text-sm font-semibold">Findings</label>
                <textarea name="findings" class="form-textarea" rows="3">{{ old('findings') }}</textarea>
            </div>
            <div class="mt-4">
                <label class="text-sm font-semibold">Corrective Actions</label>
                <textarea name="corrective_actions" class="form-textarea" rows="3">{{ old('corrective_actions') }}</textarea>
            </div>

            <div class="mt-5">
                <div class="mb-3 flex items-center justify-between">
                    <label class="text-sm font-semibold">Checklist Items</label>
                    <button type="button" onclick="addItem()" class="btn btn-sm btn-outline-primary">+ Add Item</button>
                </div>
                <div id="items-container" class="space-y-2">
                    <div class="item-row grid items-center gap-3 rounded-lg border p-3" style="grid-template-columns: 1fr auto auto;">
                        <input type="text" name="items[0][item_name]" placeholder="Item description" class="form-input text-sm w-full" required />
                        <label class="flex items-center gap-1.5 text-xs whitespace-nowrap">
                            <input type="hidden" name="items[0][is_compliant]" value="0" />
                            <input type="checkbox" name="items[0][is_compliant]" value="1" class="form-checkbox h-4 w-4" />
                            Compliant
                        </label>
                        <button type="button" onclick="this.closest('.item-row').remove()" class="text-danger text-xs hover:underline">Remove</button>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <label class="text-sm font-semibold">Notes</label>
                <textarea name="notes" class="form-textarea" rows="2">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary mt-4">Save Checklist</button>
        </form>
    </div>
@endsection

@push('scripts')
<script>
let itemIndex = 1;
function addItem() {
    const container = document.getElementById('items-container');
    const div = document.createElement('div');
    div.className = 'item-row grid items-center gap-3 rounded-lg border p-3';
    div.style.gridTemplateColumns = '1fr auto auto';
    div.innerHTML = `
        <input type="text" name="items[${itemIndex}][item_name]" placeholder="Item description" class="form-input text-sm w-full" required />
        <label class="flex items-center gap-1.5 text-xs whitespace-nowrap">
            <input type="hidden" name="items[${itemIndex}][is_compliant]" value="0" />
            <input type="checkbox" name="items[${itemIndex}][is_compliant]" value="1" class="form-checkbox h-4 w-4" />
            Compliant
        </label>
        <button type="button" onclick="this.closest('.item-row').remove()" class="text-danger text-xs hover:underline">Remove</button>
    `;
    container.appendChild(div);
    itemIndex++;
}
</script>
@endpush
