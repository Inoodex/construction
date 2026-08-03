@extends('admin.layouts.master')

@section('title', 'Create Bill')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Create Bill Payable</h2>
        <a href="{{ route('admin.finance.bills.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.finance.bills.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-4">
                <div class="form-group lg:col-span-2">
                    <label for="purchase_order_id">From Purchase Order (PO)</label>
                    <select name="purchase_order_id" id="purchase_order_id" class="form-select">
                        <option value="">Direct Bill (No PO)</option>
                        @foreach($purchaseOrders as $po)
                            <option value="{{ $po->id }}" {{ old('purchase_order_id') == $po->id ? 'selected' : '' }}>
                                {{ $po->po_number }} — {{ $po->vendor?->name }} (৳{{ number_format($po->total_amount, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('purchase_order_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="project_id">Project <span class="text-danger">*</span></label>
                    <select name="project_id" id="project_id" class="form-select" required>
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                    @error('project_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="vendor_id">Vendor <span class="text-danger">*</span></label>
                    <select name="vendor_id" id="vendor_id" class="form-select" required>
                        <option value="">Select Vendor</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                    @error('vendor_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group lg:col-span-2">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-input" required value="{{ old('title') }}" placeholder="e.g. Vendor Invoice for Materials" />
                    @error('title') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="reference">Reference (PO #)</label>
                    <input type="text" name="reference" id="reference" class="form-input" value="{{ old('reference') }}" placeholder="PO #" />
                </div>
                <div class="form-group">
                    <label for="tax_rate">Tax Rate (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="tax_rate" id="tax_rate" class="form-input" value="{{ old('tax_rate', 0) }}" />
                    @error('tax_rate') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="bill_date">Bill Date <span class="text-danger">*</span></label>
                    <input type="date" name="bill_date" id="bill_date" class="form-input" required value="{{ old('bill_date', now()->format('Y-m-d')) }}" />
                    @error('bill_date') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="due_date">Due Date <span class="text-danger">*</span></label>
                    <input type="date" name="due_date" id="due_date" class="form-input" required value="{{ old('due_date', now()->addDays(30)->format('Y-m-d')) }}" />
                    @error('due_date') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group lg:col-span-4">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-input" rows="2" placeholder="Additional billing notes or payment terms">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- Bill Line Items --}}
            <div class="mt-6 border-t pt-5 dark:border-gray-800">
                <div class="flex items-center justify-between">
                    <h5 class="text-base font-semibold">Bill Line Items</h5>
                    <button type="button" onclick="addItem()" class="btn btn-sm btn-outline-primary">+ Add Item</button>
                </div>

                <div class="mt-3 overflow-x-auto">
                    <table class="table-hover w-full table-auto" id="items-table">
                        <thead>
                            <tr>
                                <th class="w-2/5">Description / Item</th>
                                <th>Quantity</th>
                                <th>Unit Price (৳)</th>
                                <th>Total (৳)</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="items-body"></tbody>
                        <tfoot>
                            <tr class="font-semibold">
                                <td colspan="3" class="text-right">Subtotal:</td>
                                <td class="text-center font-mono text-primary font-bold" id="subtotal-display">৳0.00</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Create Bill</button>
                <button type="reset" class="btn btn-outline-danger">Reset</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
let itemIndex = 0;

function addItem(data = {}) {
    const i = itemIndex++;
    const row = document.createElement('tr');
    row.id = 'item-' + i;
    row.innerHTML = `
        <td>
            <input type="text" name="items[${i}][description]" class="form-input" placeholder="Item description / material" required value="${data.description || ''}" />
        </td>
        <td><input type="number" step="0.0001" min="0.0001" name="items[${i}][quantity]" class="form-input" required value="${data.quantity || 1}" oninput="calcRow(${i})" /></td>
        <td><input type="number" step="0.01" min="0" name="items[${i}][unit_price]" class="form-input" required value="${data.unit_price || 0}" oninput="calcRow(${i})" /></td>
        <td class="text-center font-semibold font-mono" id="total-${i}">—</td>
        <td class="text-center"><button type="button" onclick="document.getElementById('item-${i}').remove(); calcTotal();" class="btn btn-sm btn-outline-danger">Remove</button></td>
    `;
    document.getElementById('items-body').appendChild(row);
    if (data.description) calcRow(i);
}

function calcRow(i) {
    const qty = parseFloat(document.querySelector(`#item-${i} input[name*="[quantity]"]`).value) || 0;
    const price = parseFloat(document.querySelector(`#item-${i} input[name*="[unit_price]"]`).value) || 0;
    document.getElementById('total-' + i).textContent = qty && price ? '৳' + (qty * price).toLocaleString(undefined, {minimumFractionDigits: 2}) : '—';
    calcTotal();
}

function calcTotal() {
    let subtotal = 0;
    document.querySelectorAll('#items-body tr').forEach(row => {
        const qty = parseFloat(row.querySelector('input[name*="[quantity]"]')?.value) || 0;
        const price = parseFloat(row.querySelector('input[name*="[unit_price]"]')?.value) || 0;
        subtotal += qty * price;
    });
    document.getElementById('subtotal-display').textContent = '৳' + subtotal.toLocaleString(undefined, {minimumFractionDigits: 2});
}

document.getElementById('purchase_order_id').addEventListener('change', function() {
    const poId = this.value;
    if (!poId) return;

    fetch(`/dashboard/finance/bills/po-items/${poId}`)
        .then(r => r.json())
        .then(data => {
            if (data.project_id) {
                document.getElementById('project_id').value = data.project_id;
            }
            if (data.vendor_id) {
                document.getElementById('vendor_id').value = data.vendor_id;
            }
            if (data.po_number) {
                document.getElementById('reference').value = 'PO #' + data.po_number;
                const titleInput = document.getElementById('title');
                if (!titleInput.value || titleInput.value.startsWith('Bill for PO')) {
                    titleInput.value = `Bill for PO #${data.po_number} - ${data.vendor_name || ''}`;
                }
            }

            if (data.items && data.items.length > 0) {
                document.getElementById('items-body').innerHTML = '';
                itemIndex = 0;
                data.items.forEach(item => {
                    addItem({
                        description: item.description,
                        quantity: item.quantity,
                        unit_price: item.unit_price
                    });
                });
            }
        });
});
</script>
@endpush
