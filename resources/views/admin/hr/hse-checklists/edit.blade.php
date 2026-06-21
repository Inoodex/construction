@extends('admin.layouts.master')

@section('title', 'Edit HSE Checklist')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit HSE Checklist</h2>
        <a href="{{ route('admin.hr.hse-checklists.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
    </div>

    <div class="panel mt-6 max-w-3xl">
        <form action="{{ route('admin.hr.hse-checklists.update', $hseChecklist) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-input" required value="{{ old('title', $hseChecklist->title) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Checklist Type <span class="text-danger">*</span></label>
                    <select name="checklist_type" class="form-select" required>
                        <option value="general" {{ old('checklist_type', $hseChecklist->checklist_type) == 'general' ? 'selected' : '' }}>General</option>
                        <option value="fire" {{ old('checklist_type', $hseChecklist->checklist_type) == 'fire' ? 'selected' : '' }}>Fire Safety</option>
                        <option value="electrical" {{ old('checklist_type', $hseChecklist->checklist_type) == 'electrical' ? 'selected' : '' }}>Electrical</option>
                        <option value="scaffolding" {{ old('checklist_type', $hseChecklist->checklist_type) == 'scaffolding' ? 'selected' : '' }}>Scaffolding</option>
                        <option value="ppe" {{ old('checklist_type', $hseChecklist->checklist_type) == 'ppe' ? 'selected' : '' }}>PPE Compliance</option>
                        <option value="excavation" {{ old('checklist_type', $hseChecklist->checklist_type) == 'excavation' ? 'selected' : '' }}>Excavation</option>
                        <option value="other" {{ old('checklist_type', $hseChecklist->checklist_type) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Inspector</label>
                    <select name="employee_id" class="form-select">
                        <option value="">Select inspector</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id', $hseChecklist->employee_id) == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Location</label>
                    <input type="text" name="location" class="form-input" value="{{ old('location', $hseChecklist->location) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Inspection Date <span class="text-danger">*</span></label>
                    <input type="date" name="inspection_date" class="form-input" required value="{{ old('inspection_date', $hseChecklist->inspection_date?->format('Y-m-d')) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="open" {{ old('status', $hseChecklist->status) == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="closed" {{ old('status', $hseChecklist->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Closure Date</label>
                    <input type="date" name="closure_date" class="form-input" value="{{ old('closure_date', $hseChecklist->closure_date?->format('Y-m-d')) }}" />
                </div>
            </div>

            <div class="mt-4">
                <label class="text-sm font-semibold">Findings</label>
                <textarea name="findings" class="form-textarea" rows="3">{{ old('findings', $hseChecklist->findings) }}</textarea>
            </div>
            <div class="mt-4">
                <label class="text-sm font-semibold">Corrective Actions</label>
                <textarea name="corrective_actions" class="form-textarea" rows="3">{{ old('corrective_actions', $hseChecklist->corrective_actions) }}</textarea>
            </div>

            <div class="mt-5">
                <div class="mb-3 flex items-center justify-between">
                    <label class="text-sm font-semibold">Checklist Items</label>
                    <button type="button" onclick="addItem()" class="btn btn-sm btn-outline-primary">+ Add Item</button>
                </div>
                <div id="items-container" class="space-y-2">
                    @forelse($hseChecklist->items as $i => $item)
                        <div class="item-row grid items-center gap-3 rounded-lg border p-3" style="grid-template-columns: 1fr auto auto;">
                            <input type="hidden" name="items[{{ $i }}][id]" value="{{ $item->id }}" />
                            <input type="text" name="items[{{ $i }}][item_name]" value="{{ $item->item_name }}" class="form-input text-sm w-full" required />
                            <label class="flex items-center gap-1.5 text-xs whitespace-nowrap">
                                <input type="hidden" name="items[{{ $i }}][is_compliant]" value="0" />
                                <input type="checkbox" name="items[{{ $i }}][is_compliant]" value="1" class="form-checkbox h-4 w-4" {{ $item->is_compliant ? 'checked' : '' }} />
                                Compliant
                            </label>
                            <button type="button" onclick="this.closest('.item-row').remove()" class="text-danger text-xs hover:underline">Remove</button>
                        </div>
                    @empty
                        <div class="item-row grid items-center gap-3 rounded-lg border p-3" style="grid-template-columns: 1fr auto auto;">
                            <input type="text" name="items[0][item_name]" placeholder="Item description" class="form-input text-sm w-full" required />
                            <label class="flex items-center gap-1.5 text-xs whitespace-nowrap">
                                <input type="hidden" name="items[0][is_compliant]" value="0" />
                                <input type="checkbox" name="items[0][is_compliant]" value="1" class="form-checkbox h-4 w-4" />
                                Compliant
                            </label>
                            <button type="button" onclick="this.closest('.item-row').remove()" class="text-danger text-xs hover:underline">Remove</button>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-4">
                <label class="text-sm font-semibold">Notes</label>
                <textarea name="notes" class="form-textarea" rows="2">{{ old('notes', $hseChecklist->notes) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary mt-4">Update Checklist</button>
        </form>
    </div>
@endsection

@push('scripts')
<script>
let itemIndex = {{ max(count($hseChecklist->items), 1) }};
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
