@extends('admin.layouts.master')

@section('title', 'Create Material Transfer')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Create Material Transfer</h2>
        <a href="{{ route('admin.procurement.material-transfers.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.procurement.material-transfers.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div class="form-group">
                    <label for="from_warehouse_id">From Warehouse <span class="text-danger">*</span></label>
                    <select name="from_warehouse_id" id="from_warehouse_id" class="form-select" required>
                        <option value="">Select Warehouse</option>
                        @foreach($warehouses as $w)
                            <option value="{{ $w->id }}" {{ old('from_warehouse_id') == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                        @endforeach
                    </select>
                    @error('from_warehouse_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="to_site_id">To Site <span class="text-danger">*</span></label>
                    <select name="to_site_id" id="to_site_id" class="form-select" required>
                        <option value="">Select Site</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ old('to_site_id') == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                        @endforeach
                    </select>
                    @error('to_site_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="transfer_date">Transfer Date <span class="text-danger">*</span></label>
                    <input type="date" name="transfer_date" id="transfer_date" class="form-input" required
                        value="{{ old('transfer_date', date('Y-m-d')) }}" />
                </div>
            </div>

            <div class="mt-6">
                <div class="flex items-center justify-between">
                    <h5 class="text-base font-semibold">Items to Transfer</h5>
                    <button type="button" onclick="addItem()" class="btn btn-sm btn-outline-primary">+ Add Item</button>
                </div>
                @error('items') <p class="mt-1 text-danger text-sm">{{ $message }}</p> @enderror

                <div class="mt-3 overflow-x-auto">
                    <table class="table-hover w-full table-auto" id="items-table">
                        <thead>
                            <tr>
                                <th class="w-1/2">Material</th>
                                <th>Quantity</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="items-body"></tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Create Transfer</button>
                <button type="reset" class="btn btn-outline-danger">Reset Form</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
const materials = @json($materials);
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
        <td><input type="number" step="0.0001" min="0.0001" name="items[${i}][quantity]" class="form-input" required value="${data.quantity || ''}" /></td>
        <td class="text-center"><button type="button" onclick="document.getElementById('item-${i}').remove()" class="btn btn-sm btn-outline-danger">Remove</button></td>
    `;
    document.getElementById('items-body').appendChild(row);
}
</script>
@endpush
