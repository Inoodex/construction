@extends('admin.layouts.master')

@section('title', 'Edit Contract Claim')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Claim: {{ $contractClaim->claim_number }}</h2>
        <a href="{{ route('admin.core.contract-claims.index') }}" class="btn btn-outline-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List</a>
    </div>

    <form action="{{ route('admin.core.contract-claims.update', $contractClaim) }}" method="POST" class="mt-6">
        @csrf
        @method('PUT')
        <div class="panel grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold mb-4">Claim Details</h3>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Contract <span class="text-danger">*</span></label>
                <select name="contract_id" class="form-select w-full" required>
                    <option value="">Select Contract</option>
                    @foreach($contracts as $contract)
                        <option value="{{ $contract->id }}" {{ old('contract_id', $contractClaim->contract_id) == $contract->id ? 'selected' : '' }}>{{ $contract->contract_number }} - {{ $contract->title }}</option>
                    @endforeach
                </select>
                @error('contract_id') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Type <span class="text-danger">*</span></label>
                <select name="type" class="form-select w-full" required>
                    @foreach(['time_extension' => 'Time Extension', 'cost_compensation' => 'Cost Compensation', 'both' => 'Time & Cost'] as $val => $lbl)
                        <option value="{{ $val }}" {{ old('type', $contractClaim->type) == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
                @error('type') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" value="{{ old('title', $contractClaim->title) }}" class="form-input w-full" required />
                @error('title') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Description <span class="text-danger">*</span></label>
                <textarea name="description" rows="4" class="form-input w-full" required>{{ old('description', $contractClaim->description) }}</textarea>
                @error('description') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Claimed Amount</label>
                <input type="number" step="0.01" name="claimed_amount" value="{{ old('claimed_amount', $contractClaim->claimed_amount) }}" class="form-input w-full" />
                @error('claimed_amount') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Claimed Days</label>
                <input type="number" name="claimed_days" value="{{ old('claimed_days', $contractClaim->claimed_days) }}" class="form-input w-full" />
                @error('claimed_days') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold mb-4">Review Outcome</h3>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Granted Amount</label>
                <input type="number" step="0.01" name="granted_amount" value="{{ old('granted_amount', $contractClaim->granted_amount) }}" class="form-input w-full" />
                @error('granted_amount') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Granted Days</label>
                <input type="number" name="granted_days" value="{{ old('granted_days', $contractClaim->granted_days) }}" class="form-input w-full" />
                @error('granted_days') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select w-full" required>
                    @foreach(['draft' => 'Draft', 'submitted' => 'Submitted', 'under_review' => 'Under Review', 'granted' => 'Granted', 'partially_granted' => 'Partially Granted', 'rejected' => 'Rejected'] as $val => $lbl)
                        <option value="{{ $val }}" {{ old('status', $contractClaim->status) == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
                @error('status') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Notes</label>
                <textarea name="notes" rows="3" class="form-input w-full">{{ old('notes', $contractClaim->notes) }}</textarea>
                @error('notes') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2 flex justify-end gap-2">
                <a href="{{ route('admin.core.contract-claims.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Claim</button>
            </div>
        </div>
    </form>
@endsection
