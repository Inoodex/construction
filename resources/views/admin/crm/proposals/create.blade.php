@extends('admin.layouts.master')

@section('title', 'Create Proposal')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Create Proposal</h5>
        <a href="{{ route('admin.crm.proposals.index') }}" class="btn btn-outline-danger">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg> 
            Back to Proposals</a>
    </div>

    <form action="{{ route('admin.crm.proposals.store') }}" method="POST">
        @csrf

        <div class="mb-5 grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <label class="mb-1 block text-xs">Link to Lead</label>
                <select name="lead_id" class="form-select">
                    <option value="">—</option>
                    @foreach($leads as $lead)
                    <option value="{{ $lead->id }}" {{ old('lead_id', $selectedLead?->id) == $lead->id ? 'selected' : '' }}>{{ $lead->company_name }} ({{ $lead->status }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs">Link to Client</label>
                <select name="client_id" class="form-select">
                    <option value="">—</option>
                    @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ old('client_id', $selectedClient?->id) == $client->id ? 'selected' : '' }}>{{ $client->company_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs">Status</label>
                <select name="status" class="form-select">
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="sent" {{ old('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-xs">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-input" value="{{ old('title') }}" required />
                @error('title') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-xs">Tax Rate (%)</label>
                <input type="number" step="0.01" min="0" max="100" name="tax_rate" class="form-input" value="{{ old('tax_rate', 0) }}" />
            </div>
            <div>
                <label class="mb-1 block text-xs">Valid Until</label>
                <input type="date" name="valid_until" class="form-input" value="{{ old('valid_until') }}" />
            </div>
            <div class="md:col-span-3">
                <label class="mb-1 block text-xs">Notes</label>
                <textarea name="notes" class="form-textarea" rows="2">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="mb-5">
            <h6 class="mb-3 text-sm font-semibold">Proposal Items</h6>
            <div class="table-responsive">
                <table class="table-hover table" id="items-table">
                    <thead>
                        <tr>
                            <th class="w-2/5">Description</th>
                            <th class="w-20">Quantity</th>
                            <th class="w-28">Unit</th>
                            <th class="w-28">Unit Price (৳)</th>
                            <th class="w-28">Total (৳)</th>
                            <th class="w-16 text-center"></th>
                        </tr>
                    </thead>
                    <tbody id="items-body">
                        <tr>
                            <td><input type="text" name="items[0][description]" class="form-input text-xs" required placeholder="Item description" /></td>
                            <td><input type="number" step="0.01" min="0.01" name="items[0][quantity]" class="form-input text-xs qty" value="1" required oninput="calcRow(0)" /></td>
                            <td><input type="text" name="items[0][unit]" class="form-input text-xs" placeholder="e.g. pcs" /></td>
                            <td><input type="number" step="0.01" min="0" name="items[0][unit_price]" class="form-input text-xs price" value="0" required oninput="calcRow(0)" /></td>
                            <td><span class="row-total text-xs font-mono">0.00</span></td>
                            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)">×</button></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-right font-semibold">Subtotal: <span id="subtotal">0.00</span></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-right font-semibold">Tax: <span id="tax-display">0.00</span></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-right font-bold text-lg">Total: <span id="total">0.00</span></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <button type="button" onclick="addRow()" class="btn btn-sm btn-outline-primary mt-2">+ Add Item</button>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary">Create Proposal</button>
            <a href="{{ route('admin.crm.proposals.index') }}" class="btn btn-outline-danger">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let rowIndex = 1;

    function addRow() {
        const html = `<tr>
        <td><input type="text" name="items[${rowIndex}][description]" class="form-input text-xs" required placeholder="Item description" /></td>
        <td><input type="number" step="0.01" min="0.01" name="items[${rowIndex}][quantity]" class="form-input text-xs qty" value="1" required oninput="calcRow(${rowIndex})" /></td>
        <td><input type="text" name="items[${rowIndex}][unit]" class="form-input text-xs" placeholder="e.g. pcs" /></td>
        <td><input type="number" step="0.01" min="0" name="items[${rowIndex}][unit_price]" class="form-input text-xs price" value="0" required oninput="calcRow(${rowIndex})" /></td>
        <td><span class="row-total text-xs font-mono">0.00</span></td>
        <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)">×</button></td>
    </tr>`;
        document.getElementById('items-body').insertAdjacentHTML('beforeend', html);
        rowIndex++;
    }

    function removeRow(btn) {
        const rows = document.querySelectorAll('#items-body tr');
        if (rows.length > 1) {
            btn.closest('tr').remove();
            calcTotal();
        }
    }

    function calcRow(i) {
        const row = document.querySelectorAll('#items-body tr')[i];
        if (!row) return;
        const qty = parseFloat(row.querySelector('.qty').value) || 0;
        const price = parseFloat(row.querySelector('.price').value) || 0;
        row.querySelector('.row-total').textContent = (qty * price).toFixed(2);
        calcTotal();
    }

    function calcTotal() {
        let subtotal = 0;
        document.querySelectorAll('.row-total').forEach(el => subtotal += parseFloat(el.textContent) || 0);
        document.getElementById('subtotal').textContent = subtotal.toFixed(2);
        const taxRate = parseFloat(document.querySelector('[name=tax_rate]').value) || 0;
        const taxAmount = subtotal * taxRate / 100;
        document.getElementById('tax-display').textContent = taxAmount.toFixed(2);
        document.getElementById('total').textContent = (subtotal + taxAmount).toFixed(2);
    }
    document.querySelector('[name=tax_rate]').addEventListener('input', calcTotal);
    calcRow(0);
</script>
@endpush
@endsection