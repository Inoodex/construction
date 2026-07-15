@extends('admin.layouts.master')

@section('title', 'Edit Bank Guarantee')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Bank Guarantee: {{ $bankGuarantee->reference_number }}</h2>
        <a href="{{ route('admin.finance.bank-guarantees.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.finance.bank-guarantees.update', $bankGuarantee) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="reference_number">Reference # <span class="text-danger">*</span></label>
                    <input type="text" name="reference_number" id="reference_number" class="form-input" required value="{{ old('reference_number', $bankGuarantee->reference_number) }}" />
                    @error('reference_number') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="type">Type <span class="text-danger">*</span></label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="">Select</option>
                        <option value="bid" {{ old('type', $bankGuarantee->type) == 'bid' ? 'selected' : '' }}>Bid Bond</option>
                        <option value="performance" {{ old('type', $bankGuarantee->type) == 'performance' ? 'selected' : '' }}>Performance Bond</option>
                        <option value="advance" {{ old('type', $bankGuarantee->type) == 'advance' ? 'selected' : '' }}>Advance Payment Guarantee</option>
                        <option value="retention" {{ old('type', $bankGuarantee->type) == 'retention' ? 'selected' : '' }}>Retention Money Guarantee</option>
                    </select>
                    @error('type') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="issuing_bank">Issuing Bank <span class="text-danger">*</span></label>
                    <input type="text" name="issuing_bank" id="issuing_bank" class="form-input" required value="{{ old('issuing_bank', $bankGuarantee->issuing_bank) }}" />
                    @error('issuing_bank') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="project_id">Project</label>
                    <select name="project_id" id="project_id" class="form-select">
                        <option value="">— None —</option>
                        @foreach($projects as $id => $name)
                            <option value="{{ $id }}" {{ old('project_id', $bankGuarantee->project_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="beneficiary">Beneficiary <span class="text-danger">*</span></label>
                    <input type="text" name="beneficiary" id="beneficiary" class="form-input" required value="{{ old('beneficiary', $bankGuarantee->beneficiary) }}" />
                    @error('beneficiary') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="amount">Amount (৳) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" name="amount" id="amount" class="form-input" required value="{{ old('amount', $bankGuarantee->amount) }}" />
                    @error('amount') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="issue_date">Issue Date <span class="text-danger">*</span></label>
                    <input type="date" name="issue_date" id="issue_date" class="form-input" required value="{{ old('issue_date', $bankGuarantee->issue_date->format('Y-m-d')) }}" />
                    @error('issue_date') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="expiry_date">Expiry Date <span class="text-danger">*</span></label>
                    <input type="date" name="expiry_date" id="expiry_date" class="form-input" required value="{{ old('expiry_date', $bankGuarantee->expiry_date->format('Y-m-d')) }}" />
                    @error('expiry_date') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group md:col-span-2">
                    <label for="narration">Narration</label>
                    <textarea name="narration" id="narration" class="form-textarea" rows="2">{{ old('narration', $bankGuarantee->narration) }}</textarea>
                </div>
                <div class="form-group">
                    <label for="document">Replace Document (PDF/Image)</label>
                    <input type="file" name="document" id="document" class="form-input" accept=".pdf,.jpg,.jpeg,.png" />
                    @if($bankGuarantee->document_path)
                        <p class="text-xs text-white-dark mt-1">Current: <a href="{{ asset('storage/' . $bankGuarantee->document_path) }}" target="_blank" class="text-info">View</a></p>
                    @endif
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update Guarantee</button>
            </div>
        </form>
    </div>
@endsection
