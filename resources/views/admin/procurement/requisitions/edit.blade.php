@extends('admin.layouts.master')

@section('title', 'Edit Requisition')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Requisition</h2>
        <a href="{{ route('admin.procurement.requisitions.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.procurement.requisitions.update', $requisition->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div class="form-group">
                    <label for="project_id">Project <span class="text-danger">*</span></label>
                    <select name="project_id" id="project_id" class="form-select" required>
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $requisition->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                    @error('project_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="required_date">Required Date</label>
                    <input type="date" name="required_date" id="required_date" class="form-input"
                        value="{{ old('required_date', $requisition->required_date?->format('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="draft" {{ old('status', $requisition->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="submitted" {{ old('status', $requisition->status) == 'submitted' ? 'selected' : '' }}>Submitted</option>
                        <option value="approved" {{ old('status', $requisition->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ old('status', $requisition->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
            </div>

            <div class="mt-6">
                <div class="flex items-center justify-between">
                    <h5 class="text-base font-semibold">Requisition Items</h5>
                    <button type="button" onclick="addItem()" class="btn btn-sm btn-outline-primary">+ Add Item</button>
                </div>
                @error('items') <p class="mt-1 text-danger text-sm">{{ $message }}</p> @enderror

                <div class="mt-3 overflow-x-auto">
                    <table class="table-hover w-full table-auto" id="items-table">
                        <thead>
                            <tr>
                                <th class="w-1/2">Material</th>
                                <th>Quantity</th>
                                <th>Est. Unit Price</th>
                                <th>Total</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="items-body"></tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update Requisition</button>
                <button type="button" onclick="window.location.href='{{ route('admin.procurement.requisitions.index') }}'"
                    class="btn btn-outline-danger">Cancel</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
const materials = @json($materials);
const existingItems = @json($requisition->items);
let itemIndex = 0;

function addItem(data = {}) {
    const i = itemIndex++;
    const row = document.createElement('tr');
    row.id = 'item-' + i;
    row.innerHTML = `
        <td>
            <select name="items[${i}][material_id]" class="form-select" required>
                <option value="">Select Material</option>
                ${materials.map(m => `<option value="${m.id}" ${data.material_id == m.id ? 'selected' : ''}>${m.name} (${m.unit})</option>`).join('')}
            </select>
        </td>
        <td><input type="number" step="0.0001" min="0.0001" name="items[${i}][quantity]" class="form-input" required value="${data.quantity || ''}" oninput="calcRow(${i})" /></td>
        <td><input type="number" step="0.01" min="0" name="items[${i}][estimated_unit_price]" class="form-input" value="${data.estimated_unit_price || ''}" oninput="calcRow(${i})" /></td>
        <td class="text-center font-semibold" id="total-${i}">—</td>
        <td class="text-center"><button type="button" onclick="document.getElementById('item-${i}').remove()" class="btn btn-sm btn-outline-danger">Remove</button></td>
    `;
    document.getElementById('items-body').appendChild(row);
    if (data.material_id) calcRow(i);
}

function calcRow(i) {
    const qty = parseFloat(document.querySelector(`#item-${i} input[name*="[quantity]"]`).value) || 0;
    const price = parseFloat(document.querySelector(`#item-${i} input[name*="[estimated_unit_price]"]`).value) || 0;
    document.getElementById('total-' + i).textContent = qty && price ? '৳' + (qty * price).toLocaleString() : '—';
}

existingItems.forEach(item => addItem(item));
</script>
@endpush
