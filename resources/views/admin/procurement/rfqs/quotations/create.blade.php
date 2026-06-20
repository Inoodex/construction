@extends('admin.layouts.master')

@section('title', 'Add Quotation - ' . $vendor->name)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Add Quotation: {{ $vendor->name }}</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.procurement.rfqs.show', $rfq) }}" class="btn btn-secondary gap-2">Back to RFQ</a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-4">
            <p class="text-sm text-white-dark">RFQ: <strong>{{ $rfq->rfq_number }}</strong> - {{ $rfq->title }}</p>
        </div>

        <form action="{{ route('admin.procurement.rfqs.quotations.store', $rfq) }}" method="POST">
            @csrf
            <input type="hidden" name="vendor_id" value="{{ $vendor->id }}" />

            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div class="form-group">
                    <label for="quotation_number">Quotation Ref / Number</label>
                    <input type="text" name="quotation_number" id="quotation_number" class="form-input" value="{{ old('quotation_number') }}" />
                </div>
                <div class="form-group">
                    <label for="submitted_date">Date <span class="text-danger">*</span></label>
                    <input type="date" name="submitted_date" id="submitted_date" class="form-input" required value="{{ old('submitted_date', date('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <input type="text" name="notes" id="notes" class="form-input" value="{{ old('notes') }}" />
                </div>
            </div>

            <div class="mt-6">
                <label class="text-lg font-semibold">Pricing <span class="text-danger">*</span></label>
                <div class="table-responsive mt-2">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Unit</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rfq->items as $item)
                                <tr>
                                    <td>{{ $item->material->name }}
                                        <input type="hidden" name="items[{{ $loop->index }}][rfq_item_id]" value="{{ $item->id }}" />
                                    </td>
                                    <td>{{ $item->unit }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>
                                        <input type="number" name="items[{{ $loop->index }}][unit_price]" class="form-input w-32 unit-price" step="0.01" min="0" required value="{{ old("items.{$loop->index}.unit_price") }}" />
                                    </td>
                                    <td class="line-total font-semibold">-</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="font-bold">
                                <td colspan="4" class="text-right">Grand Total</td>
                                <td id="grand-total">0.00</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Save Quotation</button>
                <a href="{{ route('admin.procurement.rfqs.show', $rfq) }}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('unit-price')) {
        const row = e.target.closest('tr');
        const qty = parseFloat(row.querySelector('td:nth-child(3)').textContent) || 0;
        const price = parseFloat(e.target.value) || 0;
        row.querySelector('.line-total').textContent = (qty * price).toFixed(2);

        let total = 0;
        document.querySelectorAll('.line-total').forEach(el => {
            total += parseFloat(el.textContent) || 0;
        });
        document.getElementById('grand-total').textContent = total.toFixed(2);
    }
});
</script>
@endpush
