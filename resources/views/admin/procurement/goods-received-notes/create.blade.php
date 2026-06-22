@extends('admin.layouts.master')

@section('title', 'Create Goods Received Note')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Create Goods Received Note</h2>
        <a href="{{ route('admin.procurement.goods-received-notes.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.procurement.goods-received-notes.store') }}" method="POST" id="grn-form">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="purchase_order_id">Purchase Order <span class="text-danger">*</span></label>
                    <select name="purchase_order_id" id="purchase_order_id" class="form-select" required onchange="loadPOItems(this.value);filterSites(this.value)">
                        <option value="">Select PO</option>
                        @foreach($orders as $po)
                            <option value="{{ $po->id }}" {{ old('purchase_order_id') == $po->id ? 'selected' : '' }}>
                                {{ $po->po_number }} — {{ $po->vendor->name ?? 'N/A' }} ({{ $po->status }})
                            </option>
                        @endforeach
                    </select>
                    @error('purchase_order_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="site_id">Delivery Site</label>
                    <select name="site_id" id="site_id" class="form-select">
                        <option value="">Select Site</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}" data-project="{{ $site->project_id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>{{ $site->name }} ({{ $site->project->name }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="received_date">Received Date <span class="text-danger">*</span></label>
                    <input type="date" name="received_date" id="received_date" class="form-input" required
                        value="{{ old('received_date', date('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="delivery_note">Delivery Note / Reference</label>
                    <input type="text" name="delivery_note" id="delivery_note" class="form-input" value="{{ old('delivery_note') }}" placeholder="e.g. Challan #123" />
                </div>
                <div class="form-group">
                    <label for="vehicle_number">Vehicle Number</label>
                    <input type="text" name="vehicle_number" id="vehicle_number" class="form-input" value="{{ old('vehicle_number') }}" placeholder="e.g. Dhaka Metro 12-3456" />
                </div>
            </div>

            <div class="mt-6">
                <h5 class="mb-4 text-base font-semibold">Received Items</h5>
                @error('items') <p class="mt-1 mb-2 text-danger text-sm">{{ $message }}</p> @enderror

                <div class="overflow-x-auto">
                    <table class="table-hover w-full table-auto" id="items-table">
                        <thead>
                            <tr>
                                <th>Material</th>
                                <th class="w-40">Qty Received</th>
                                <th class="w-40">Qty Accepted</th>
                                <th class="w-40">Qty Rejected</th>
                            </tr>
                        </thead>
                        <tbody id="items-body"></tbody>
                    </table>
                </div>
                <p id="no-po-msg" class="mt-4 text-center text-sm text-white-dark hidden">Select a PO to load its items.</p>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Save GRN</button>
                <button type="reset" class="btn btn-outline-danger">Reset Form</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
const orders = @json($orders);
const allSites = @json($sites);

function filterSites(poId) {
    const siteSelect = document.getElementById('site_id');
    siteSelect.innerHTML = '<option value="">Select Site</option>';
    if (!poId) return;
    const po = orders.find(o => o.id == poId);
    const projectId = po?.requisition?.project_id ?? po?.project_id;
    if (!projectId) return;
    allSites.filter(s => s.project_id == projectId).forEach(site => {
        const opt = document.createElement('option');
        opt.value = site.id;
        opt.textContent = site.name;
        siteSelect.appendChild(opt);
    });
}

function loadPOItems(poId) {
    const tbody = document.getElementById('items-body');
    const msg = document.getElementById('no-po-msg');
    tbody.innerHTML = '';
    if (!poId) { msg.classList.remove('hidden'); return; }
    msg.classList.add('hidden');

    const po = orders.find(o => o.id == poId);
    if (!po || !po.items) return;

    po.items.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <span class="font-semibold">${item.material?.name || 'Unknown'}</span>
                <input type="hidden" name="items[${item.id}][material_id]" value="${item.material_id}" />
            </td>
            <td><input type="number" step="0.0001" min="0" name="items[${item.id}][quantity_received]" class="form-input" required value="${item.quantity}" oninput="syncQty(this)" /></td>
            <td><input type="number" step="0.0001" min="0" name="items[${item.id}][quantity_accepted]" class="form-input" required value="${item.quantity}" /></td>
            <td><input type="number" step="0.0001" min="0" name="items[${item.id}][quantity_rejected]" class="form-input" required value="0" /></td>
        `;
        tbody.appendChild(row);
    });
}

function syncQty(input) {
    const row = input.closest('tr');
    const accepted = row.querySelector('input[name*="[quantity_accepted]"]');
    const rejected = row.querySelector('input[name*="[quantity_rejected]"]');
    const val = parseFloat(input.value) || 0;
    if (!accepted.value || parseFloat(accepted.value) > val) accepted.value = val;
}

document.addEventListener('DOMContentLoaded', function () {
    const selected = document.getElementById('purchase_order_id').value;
    if (selected) loadPOItems(selected);
});
</script>
@endpush
