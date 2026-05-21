@extends('admin.layouts.master')

@section('title', 'Edit Purchase Order')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Purchase Order</h2>
        <a href="{{ route('admin.procurement.purchase-orders.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.procurement.purchase-orders.update', $purchaseOrder->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-4">
                <div class="form-group">
                    <label for="vendor_id">Vendor <span class="text-danger">*</span></label>
                    <select name="vendor_id" id="vendor_id" class="form-select" required>
                        <option value="">Select Vendor</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ old('vendor_id', $purchaseOrder->vendor_id) == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="purchase_requisition_id">From Requisition</label>
                    <select name="purchase_requisition_id" id="purchase_requisition_id" class="form-select">
                        <option value="">Direct Order</option>
                        @foreach($requisitions as $pr)
                            <option value="{{ $pr->id }}" {{ old('purchase_requisition_id', $purchaseOrder->purchase_requisition_id) == $pr->id ? 'selected' : '' }}>{{ $pr->requisition_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="order_date">Order Date <span class="text-danger">*</span></label>
                    <input type="date" name="order_date" id="order_date" class="form-input" required
                        value="{{ old('order_date', $purchaseOrder->order_date->format('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="draft" {{ old('status', $purchaseOrder->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="ordered" {{ old('status', $purchaseOrder->status) == 'ordered' ? 'selected' : '' }}>Ordered</option>
                        <option value="partially_received" {{ old('status', $purchaseOrder->status) == 'partially_received' ? 'selected' : '' }}>Partially Received</option>
                        <option value="received" {{ old('status', $purchaseOrder->status) == 'received' ? 'selected' : '' }}>Received</option>
                        <option value="cancelled" {{ old('status', $purchaseOrder->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="mt-6">
                <div class="flex items-center justify-between">
                    <h5 class="text-base font-semibold">PO Items</h5>
                    <button type="button" onclick="addItem()" class="btn btn-sm btn-outline-primary">+ Add Item</button>
                </div>
                @error('items') <p class="mt-1 text-danger text-sm">{{ $message }}</p> @enderror

                <div class="mt-3 overflow-x-auto">
                    <table class="table-hover w-full table-auto" id="items-table">
                        <thead>
                            <tr>
                                <th class="w-2/5">Material</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="items-body"></tbody>
                        <tfoot>
                            <tr class="font-semibold">
                                <td colspan="3" class="text-right">Grand Total:</td>
                                <td class="text-center" id="grand-total">৳0</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update PO</button>
                <button type="button" onclick="window.location.href='{{ route('admin.procurement.purchase-orders.index') }}'"
                    class="btn btn-outline-danger">Cancel</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
const materials = @json($materials);
const existingItems = @json($purchaseOrder->items);
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
        <td><input type="number" step="0.01" min="0" name="items[${i}][unit_price]" class="form-input" required value="${data.unit_price || ''}" oninput="calcRow(${i})" /></td>
        <td class="text-center font-semibold" id="total-${i}">—</td>
        <td class="text-center"><button type="button" onclick="document.getElementById('item-${i}').remove(); calcTotal();" class="btn btn-sm btn-outline-danger">Remove</button></td>
    `;
    document.getElementById('items-body').appendChild(row);
    if (data.material_id) { calcRow(i); calcTotal(); }
}

function calcRow(i) {
    const qty = parseFloat(document.querySelector(`#item-${i} input[name*="[quantity]"]`).value) || 0;
    const price = parseFloat(document.querySelector(`#item-${i} input[name*="[unit_price]"]`).value) || 0;
    document.getElementById('total-' + i).textContent = qty && price ? '৳' + (qty * price).toLocaleString() : '—';
    calcTotal();
}

function calcTotal() {
    let total = 0;
    document.querySelectorAll('#items-body tr').forEach(row => {
        const qty = parseFloat(row.querySelector('input[name*="[quantity]"]')?.value) || 0;
        const price = parseFloat(row.querySelector('input[name*="[unit_price]"]')?.value) || 0;
        total += qty * price;
    });
    document.getElementById('grand-total').textContent = '৳' + total.toLocaleString();
}

existingItems.forEach(item => addItem(item));
</script>
@endpush
