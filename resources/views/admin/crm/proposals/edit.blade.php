@extends('admin.layouts.master')

@section('title', 'Edit Proposal')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Edit Proposal</h5>
        <a href="{{ route('admin.crm.proposals.show', $proposal) }}" class="btn btn-outline-info">Cancel</a>
    </div>

    <form action="{{ route('admin.crm.proposals.update', $proposal) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-5 grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <label class="mb-1 block text-xs">Link to Lead</label>
                <select name="lead_id" class="form-select">
                    <option value="">—</option>
                    @foreach($leads as $lead)
                        <option value="{{ $lead->id }}" {{ old('lead_id', $proposal->lead_id) == $lead->id ? 'selected' : '' }}>{{ $lead->company_name }} ({{ $lead->status }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs">Link to Client</label>
                <select name="client_id" class="form-select">
                    <option value="">—</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id', $proposal->client_id) == $client->id ? 'selected' : '' }}>{{ $client->company_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs">Status</label>
                <select name="status" class="form-select">
                    <option value="draft" {{ old('status', $proposal->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="sent" {{ old('status', $proposal->status) == 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="accepted" {{ old('status', $proposal->status) == 'accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="rejected" {{ old('status', $proposal->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="expired" {{ old('status', $proposal->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-xs">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-input" value="{{ old('title', $proposal->title) }}" required />
            </div>
            <div><label class="mb-1 block text-xs">Tax Rate (%)</label><input type="number" step="0.01" min="0" max="100" name="tax_rate" class="form-input" value="{{ old('tax_rate', $proposal->tax_rate) }}" /></div>
            <div><label class="mb-1 block text-xs">Valid Until</label><input type="date" name="valid_until" class="form-input" value="{{ old('valid_until', $proposal->valid_until?->format('Y-m-d')) }}" /></div>
            <div class="md:col-span-3"><label class="mb-1 block text-xs">Notes</label><textarea name="notes" class="form-textarea" rows="2">{{ old('notes', $proposal->notes) }}</textarea></div>
        </div>

        <div class="mb-5">
            <h6 class="mb-3 text-sm font-semibold">Proposal Items</h6>
            <div class="table-responsive">
                <table class="table-hover table" id="items-table">
                    <thead>
                        <tr>
                            <th class="w-2/5">Description</th>
                            <th class="w-20">Quantity</th>
                            <th class="w-20">Unit</th>
                            <th class="w-28">Unit Price (৳)</th>
                            <th class="w-28">Total (৳)</th>
                            <th class="w-16 text-center"></th>
                        </tr>
                    </thead>
                    <tbody id="items-body">
                        @foreach($proposal->items as $i => $item)
                            <tr>
                                <td><input type="text" name="items[{{ $i }}][description]" class="form-input text-xs" required value="{{ $item->description }}" /></td>
                                <td><input type="number" step="0.01" min="0.01" name="items[{{ $i }}][quantity]" class="form-input text-xs qty" value="{{ $item->quantity }}" required oninput="calcRow({{ $i }})" /></td>
                                <td><input type="text" name="items[{{ $i }}][unit]" class="form-input text-xs" value="{{ $item->unit }}" placeholder="e.g. pcs" /></td>
                                <td><input type="number" step="0.01" min="0" name="items[{{ $i }}][unit_price]" class="form-input text-xs price" value="{{ $item->unit_price }}" required oninput="calcRow({{ $i }})" /></td>
                                <td><span class="row-total text-xs font-mono">{{ number_format($item->total_price, 2) }}</span></td>
                                <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)">×</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-right font-semibold">Subtotal: <span id="subtotal">{{ number_format($proposal->subtotal, 2) }}</span></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-right font-semibold">Tax: <span id="tax-display">{{ number_format($proposal->tax_amount, 2) }}</span></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-right font-bold text-lg">Total: <span id="total">{{ number_format($proposal->total_amount, 2) }}</span></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <button type="button" onclick="addRow()" class="btn btn-sm btn-outline-primary mt-2">+ Add Item</button>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary">Update Proposal</button>
            <a href="{{ route('admin.crm.proposals.show', $proposal) }}" class="btn btn-outline-danger">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
let rowIndex = {{ count($proposal->items) }};
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
    const rows = document.querySelectorAll('#items-body tr');
    if (!rows[i]) return;
    const qty = parseFloat(rows[i].querySelector('.qty').value) || 0;
    const price = parseFloat(rows[i].querySelector('.price').value) || 0;
    rows[i].querySelector('.row-total').textContent = (qty * price).toFixed(2);
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
</script>
@endpush
@endsection
