@extends('admin.layouts.master')

@section('title', 'BG #' . $bankGuarantee->reference_number)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">BG #{{ $bankGuarantee->reference_number }}</h2>
        <a href="{{ route('admin.finance.bank-guarantees.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
    </div>

    <div class="panel mt-6">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div><span class="text-xs text-white-dark">Reference</span><p class="font-mono font-semibold">{{ $bankGuarantee->reference_number }}</p></div>
            <div><span class="text-xs text-white-dark">Type</span><p><span class="badge badge-outline-info capitalize">{{ $bankGuarantee->type }}</span></p></div>
            <div><span class="text-xs text-white-dark">Bank</span><p>{{ $bankGuarantee->issuing_bank }}</p></div>
            <div><span class="text-xs text-white-dark">Beneficiary</span><p>{{ $bankGuarantee->beneficiary }}</p></div>
            <div><span class="text-xs text-white-dark">Amount</span><p class="font-mono font-semibold">৳ {{ number_format($bankGuarantee->amount, 2) }}</p></div>
            <div><span class="text-xs text-white-dark">Project</span><p>{{ $bankGuarantee->project?->name ?? '—' }}</p></div>
            <div><span class="text-xs text-white-dark">Issue Date</span><p>{{ $bankGuarantee->issue_date->format('d M Y') }}</p></div>
            <div><span class="text-xs text-white-dark">Expiry Date</span><p>{{ $bankGuarantee->expiry_date->format('d M Y') }}</p></div>
            <div><span class="text-xs text-white-dark">Return Date</span><p>{{ $bankGuarantee->return_date?->format('d M Y') ?? '—' }}</p></div>
            <div>
                <span class="text-xs text-white-dark">Status</span>
                @php $cls = match($bankGuarantee->status) { 'active' => 'badge-outline-success', 'expired' => 'badge-outline-secondary', 'encashed' => 'badge-outline-danger', 'returned' => 'badge-outline-primary', default => 'badge-outline-warning' }; @endphp
                <span class="badge {{ $cls }} capitalize block w-fit">{{ $bankGuarantee->status }}</span>
            </div>
            @if($bankGuarantee->narration)
                <div class="md:col-span-4"><span class="text-xs text-white-dark">Narration</span><p>{{ $bankGuarantee->narration }}</p></div>
            @endif
            @if($bankGuarantee->document_path)
                <div class="md:col-span-4"><a href="{{ asset('storage/' . $bankGuarantee->document_path) }}" target="_blank" class="btn btn-sm btn-outline-info">View Document</a></div>
            @endif
        </div>

        <div class="mt-6 border-t border-gray-200 pt-4">
            <h4 class="mb-3 text-base font-semibold">Update Status</h4>
            <form action="{{ route('admin.finance.bank-guarantees.status', $bankGuarantee) }}" method="POST" class="flex flex-wrap items-end gap-3">
                @csrf @method('PATCH')
                <div>
                    <label class="text-xs">Status</label>
                    <select name="status" class="form-select">
                        <option value="active" {{ $bankGuarantee->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="expired" {{ $bankGuarantee->status == 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="encashed" {{ $bankGuarantee->status == 'encashed' ? 'selected' : '' }}>Encashed</option>
                        <option value="returned" {{ $bankGuarantee->status == 'returned' ? 'selected' : '' }}>Returned</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs">Return Date</label>
                    <input type="date" name="return_date" class="form-input" value="{{ old('return_date', $bankGuarantee->return_date?->format('Y-m-d')) }}" />
                </div>
                <div class="flex-1">
                    <label class="text-xs">Narration</label>
                    <input type="text" name="narration" class="form-input" placeholder="Reason for change..." value="{{ old('narration') }}" />
                </div>
                <button type="submit" class="btn btn-warning">Update Status</button>
            </form>
        </div>
    </div>
@endsection
