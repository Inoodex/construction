@extends('admin.layouts.master')

@section('title', 'Edit IPA')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit IPA: {{ $ipa->ipa_number }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.finance.ipas.show', $ipa->id) }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Cancel
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.finance.ipas.update', $ipa->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div class="form-group">
                    <label for="project_id">Project <span class="text-danger">*</span></label>
                    <select name="project_id" id="project_id" class="form-select" required>
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $ipa->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-input" required value="{{ old('title', $ipa->title) }}" />
                </div>
                <div class="form-group">
                    <label for="application_date">Application Date <span class="text-danger">*</span></label>
                    <input type="date" name="application_date" id="application_date" class="form-input" required value="{{ old('application_date', $ipa->application_date->format('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="period_start">Period Start <span class="text-danger">*</span></label>
                    <input type="date" name="period_start" id="period_start" class="form-input" required value="{{ old('period_start', $ipa->period_start->format('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="period_end">Period End <span class="text-danger">*</span></label>
                    <input type="date" name="period_end" id="period_end" class="form-input" required value="{{ old('period_end', $ipa->period_end->format('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="retention_rate">Retention Rate (%) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" max="100" name="retention_rate" id="retention_rate" class="form-input" required value="{{ old('retention_rate', $ipa->retention_rate) }}" />
                </div>
                <div class="form-group md:col-span-3">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-input" rows="2">{{ old('notes', $ipa->notes) }}</textarea>
                </div>
            </div>

            <div class="mt-8">
                <h5 class="mb-4 text-base font-semibold">Progress Items</h5>
                <div class="overflow-x-auto">
                    <table class="table-hover w-full table-auto" id="itemsTable">
                        <thead>
                            <tr>
                                <th>Item / Description / Unit</th>
                                <th>Prev Qty</th>
                                <th>This Period</th>
                                <th>Cumulative</th>
                                <th>Unit Price</th>
                                <th>Prev Amount</th>
                                <th>This Period</th>
                                <th>Cumulative</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ipa->items as $index => $item)
                                <tr>
                                    <td class="text-xs">
                                        <span class="font-mono font-semibold">{{ $item->item_number }}</span>
                                        <span class="text-white-dark">|</span>
                                        {{ $item->description }}
                                        <span class="text-white-dark">|</span>
                                        <span class="italic">{{ $item->unit }}</span>
                                    </td>
                                    <td class="prev-qty text-xs" data-value="{{ $item->previous_quantity }}">{{ number_format($item->previous_quantity, 2) }}</td>
                                    <td>
                                        <input type="number" step="0.0001" min="0" name="items[{{ $index }}][current_quantity]" value="{{ old("items.$index.current_quantity", $item->current_quantity) }}" class="form-input current-qty" required />
                                    </td>
                                    <td class="cumulative-qty text-xs font-semibold">{{ number_format($item->cumulative_quantity, 2) }}</td>
                                    <td>
                                        <input type="number" step="0.01" min="0" name="items[{{ $index }}][unit_price]" value="{{ old("items.$index.unit_price", $item->unit_price) }}" class="form-input unit-price" required />
                                    </td>
                                    <td class="prev-amount text-xs">{{ number_format($item->previous_amount, 2) }}</td>
                                    <td class="current-amount font-semibold">{{ number_format($item->current_amount, 2) }}</td>
                                    <td class="cumulative-amount font-semibold">{{ number_format($item->cumulative_amount, 2) }}</td>
                                    <td class="text-center">
                                        <button type="submit" form="remove-item-{{ $item->id }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove this item from IPA?');">Remove</button>
                                    </td>
                                    <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}" />
                                </tr>
                            @empty
                                <tr><td colspan="9" class="text-center">No BOQ items found for this project.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Save Changes</button>
                <a href="{{ route('admin.finance.ipas.show', $ipa->id) }}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </form>

        @foreach($ipa->items as $item)
            <form id="remove-item-{{ $item->id }}" action="{{ route('admin.finance.ipas.items.destroy', [$ipa->id, $item->id]) }}" method="POST" class="hidden">
                @csrf @method('DELETE')
            </form>
        @endforeach
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function recalcRow(row) {
        var prevQty = parseFloat(row.querySelector('.prev-qty')?.dataset.value) || 0;
        var currentQty = parseFloat(row.querySelector('.current-qty')?.value) || 0;
        var unitPrice = parseFloat(row.querySelector('.unit-price')?.value) || 0;
        var cumulativeQty = prevQty + currentQty;
        var prevAmount = prevQty * unitPrice;
        var currentAmount = currentQty * unitPrice;
        var cumulativeAmount = cumulativeQty * unitPrice;

        var cumQtyCell = row.querySelector('.cumulative-qty');
        var prevAmtCell = row.querySelector('.prev-amount');
        var curAmtCell = row.querySelector('.current-amount');
        var cumAmtCell = row.querySelector('.cumulative-amount');

        if (cumQtyCell) cumQtyCell.textContent = cumulativeQty.toFixed(2);
        if (prevAmtCell) prevAmtCell.textContent = prevAmount.toFixed(2);
        if (curAmtCell) curAmtCell.textContent = currentAmount.toFixed(2);
        if (cumAmtCell) cumAmtCell.textContent = cumulativeAmount.toFixed(2);
    }

    document.querySelectorAll('#itemsTable tbody tr').forEach(function(row) {
        var inputs = row.querySelectorAll('.current-qty, .unit-price');
        inputs.forEach(function(input) {
            input.addEventListener('input', function() { recalcRow(row); });
        });
    });
});
</script>
@endpush