@extends('admin.layouts.master')

@section('title', 'New Contract Amendment')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">New Contract Amendment</h2>
        <a href="{{ route('admin.core.contract-amendments.index') }}" class="btn btn-outline-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg> 
            Back to List </a>
    </div>

    <form action="{{ route('admin.core.contract-amendments.store') }}" method="POST" class="mt-6">
        @csrf
        <div class="panel grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold mb-4">Amendment Details</h3>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Contract <span class="text-danger">*</span></label>
                <select name="contract_id" class="form-select w-full" required>
                    <option value="">Select Contract</option>
                    @foreach($contracts as $contract)
                        <option value="{{ $contract->id }}" {{ ($selectedContract ?? old('contract_id')) == $contract->id ? 'selected' : '' }}>{{ $contract->contract_number }} - {{ $contract->title }}</option>
                    @endforeach
                </select>
                @error('contract_id') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Type <span class="text-danger">*</span></label>
                <select name="type" class="form-select w-full" required>
                    @foreach(['scope_change' => 'Scope Change', 'time_extension' => 'Time Extension', 'value_change' => 'Value Change', 'other' => 'Other'] as $val => $lbl)
                        <option value="{{ $val }}" {{ old('type') == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
                @error('type') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" class="form-input w-full" required />
                @error('title') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Description <span class="text-danger">*</span></label>
                <textarea name="description" rows="3" class="form-input w-full" required>{{ old('description') }}</textarea>
                @error('description') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Cost Impact (+/-)</label>
                <input type="number" step="0.01" name="cost_impact" value="{{ old('cost_impact') }}" class="form-input w-full" />
                @error('cost_impact') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Time Impact (days, +/-)</label>
                <input type="number" name="time_impact_days" value="{{ old('time_impact_days') }}" class="form-input w-full" />
                @error('time_impact_days') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select w-full" required>
                    @foreach(['draft' => 'Draft', 'submitted' => 'Submitted', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $val => $lbl)
                        <option value="{{ $val }}" {{ old('status', 'draft') == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
                @error('status') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Requested By</label>
                <select name="requested_by" class="form-select w-full">
                    <option value="">Select User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('requested_by') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('requested_by') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Notes</label>
                <textarea name="notes" rows="3" class="form-input w-full">{{ old('notes') }}</textarea>
                @error('notes') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2 flex justify-end gap-2">
                <a href="{{ route('admin.core.contract-amendments.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Amendment</button>
            </div>
        </div>
    </form>
@endsection
