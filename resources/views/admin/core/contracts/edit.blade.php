@extends('admin.layouts.master')

@section('title', 'Edit Contract')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Contract: {{ $contract->contract_number }}</h2>
        <a href="{{ route('admin.core.contracts.index') }}" class="btn btn-outline-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List</a>
    </div>

    <form action="{{ route('admin.core.contracts.update', $contract) }}" method="POST" class="mt-6">
        @csrf
        @method('PUT')
        <div class="panel grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold mb-4">Contract Information</h3>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" value="{{ old('title', $contract->title) }}" class="form-input w-full" required />
                @error('title') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Project <span class="text-danger">*</span></label>
                <select name="project_id" class="form-select w-full" required>
                    <option value="">Select Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ old('project_id', $contract->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
                @error('project_id') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Client Name <span class="text-danger">*</span></label>
                <input type="text" name="client_name" value="{{ old('client_name', $contract->client_name) }}" class="form-input w-full" required />
                @error('client_name') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Contract Type <span class="text-danger">*</span></label>
                <select name="contract_type" class="form-select w-full" required>
                    @foreach(['main' => 'Main', 'subcontract' => 'Subcontract', 'supply' => 'Supply', 'consultancy' => 'Consultancy'] as $val => $lbl)
                        <option value="{{ $val }}" {{ old('contract_type', $contract->contract_type) == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
                @error('contract_type') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Contract Value <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="contract_value" value="{{ old('contract_value', $contract->contract_value) }}" class="form-input w-full" required />
                @error('contract_value') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Currency</label>
                <input type="text" name="currency" value="{{ old('currency', $contract->currency) }}" class="form-input w-full" />
                @error('currency') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select w-full" required>
                    @foreach(['draft' => 'Draft', 'active' => 'Active', 'suspended' => 'Suspended', 'completed' => 'Completed', 'terminated' => 'Terminated'] as $val => $lbl)
                        <option value="{{ $val }}" {{ old('status', $contract->status) == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
                @error('status') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold mb-4">Dates</h3>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Signing Date <span class="text-danger">*</span></label>
                <input type="date" name="signing_date" value="{{ old('signing_date', $contract->signing_date?->format('Y-m-d')) }}" class="form-input w-full" required />
                @error('signing_date') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Commencement Date <span class="text-danger">*</span></label>
                <input type="date" name="commencement_date" value="{{ old('commencement_date', $contract->commencement_date?->format('Y-m-d')) }}" class="form-input w-full" required />
                @error('commencement_date') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Completion Date</label>
                <input type="date" name="completion_date" value="{{ old('completion_date', $contract->completion_date?->format('Y-m-d')) }}" class="form-input w-full" />
                @error('completion_date') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Extended Completion Date</label>
                <input type="date" name="extended_completion_date" value="{{ old('extended_completion_date', $contract->extended_completion_date?->format('Y-m-d')) }}" class="form-input w-full" />
                @error('extended_completion_date') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold mb-4">Financial Terms</h3>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Retention Percentage (%)</label>
                <input type="number" step="0.01" name="retention_percentage" value="{{ old('retention_percentage', $contract->retention_percentage) }}" class="form-input w-full" />
                @error('retention_percentage') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Liquidated Damages Rate (per day)</label>
                <input type="number" step="0.01" name="liquidated_damages_rate" value="{{ old('liquidated_damages_rate', $contract->liquidated_damages_rate) }}" class="form-input w-full" />
                @error('liquidated_damages_rate') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Advance Payment Percentage (%)</label>
                <input type="number" step="0.01" name="advance_payment_percentage" value="{{ old('advance_payment_percentage', $contract->advance_payment_percentage) }}" class="form-input w-full" />
                @error('advance_payment_percentage') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Notes</label>
                <textarea name="notes" rows="3" class="form-input w-full">{{ old('notes', $contract->notes) }}</textarea>
                @error('notes') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2 flex justify-end gap-2">
                <a href="{{ route('admin.core.contracts.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Contract</button>
            </div>
        </div>
    </form>
@endsection
