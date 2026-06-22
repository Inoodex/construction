@extends('admin.layouts.master')

@section('title', 'New Progress Payment Certificate')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">New Progress Payment Certificate</h2>
        <a href="{{ route('admin.procurement.subcontract-progress-payments.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.procurement.subcontract-progress-payments.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="subcontract_agreement_id">Subcontract Agreement <span class="text-danger">*</span></label>
                    <select name="subcontract_agreement_id" id="subcontract_agreement_id" class="form-select" required>
                        <option value="">Select Agreement</option>
                        @foreach($agreements as $a)
<option value="{{ $a->id }}" {{ old('subcontract_agreement_id') == $a->id ? 'selected' : '' }}
    data-retention="{{ $a->retention_percentage }}"
    data-subcontractor="{{ $a->subcontractor->name ?? '' }}"
    data-start-date="{{ $a->start_date->format('Y-m-d') }}"
    data-end-date="{{ $a->end_date?->format('Y-m-d') ?? '' }}"
    data-contract-value="{{ $a->contract_value }}">
    {{ $a->agreement_number }} — {{ $a->subcontractor->name ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('subcontract_agreement_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label class="text-xs text-white-dark">Retention %</label>
                    <p id="retention-display" class="font-semibold py-2">—</p>
                </div>
                <div class="form-group">
                    <label for="period_start">Period Start <span class="text-danger">*</span></label>
                    <input type="date" name="period_start" id="period_start" class="form-input" required value="{{ old('period_start') }}" />
                    @error('period_start') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="period_end">Period End <span class="text-danger">*</span></label>
                    <input type="date" name="period_end" id="period_end" class="form-input" required value="{{ old('period_end') }}" />
                    @error('period_end') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="work_completed_value">Work Completed Value (৳) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" name="work_completed_value" id="work_completed_value" class="form-input" required value="{{ old('work_completed_value') }}" />
                    @error('work_completed_value') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label class="text-xs text-white-dark">Calculated Retention</label>
                    <p id="calc-retention" class="font-semibold py-2">৳0.00</p>
                </div>
                <div class="form-group">
                    <label class="text-xs text-white-dark">Estimated Net Payable</label>
                    <p id="calc-net-payable" class="font-semibold py-2 text-lg text-success">৳0.00</p>
                </div>
            </div>
            <div class="form-group mt-5">
                <label for="notes">Notes</label>
                <textarea name="notes" id="notes" class="form-textarea" rows="3">{{ old('notes') }}</textarea>
            </div>
            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Create Certificate</button>
                <button type="reset" class="btn btn-outline-danger">Reset</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('subcontract_agreement_id').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    const retention = opt ? parseFloat(opt.dataset.retention || 0) : 0;
    document.getElementById('retention-display').textContent = retention > 0 ? retention + '%' : '0%';
    if (opt) {
        if (opt.dataset.startDate) document.getElementById('period_start').value = opt.dataset.startDate;
        if (opt.dataset.endDate) document.getElementById('period_end').value = opt.dataset.endDate;
        if (opt.dataset.contractValue) document.getElementById('work_completed_value').value = opt.dataset.contractValue;
    }
    recalc();
});

document.getElementById('work_completed_value').addEventListener('input', recalc);

function recalc() {
    const opt = document.getElementById('subcontract_agreement_id').options[document.getElementById('subcontract_agreement_id').selectedIndex];
    const retentionPct = opt ? parseFloat(opt.dataset.retention || 0) : 0;
    const value = parseFloat(document.getElementById('work_completed_value').value) || 0;
    const retention = value * (retentionPct / 100);
    const net = value - retention;
    document.getElementById('calc-retention').textContent = '৳' + retention.toLocaleString(undefined, {minimumFractionDigits: 2});
    document.getElementById('calc-net-payable').textContent = '৳' + net.toLocaleString(undefined, {minimumFractionDigits: 2});
}
</script>
@endpush
