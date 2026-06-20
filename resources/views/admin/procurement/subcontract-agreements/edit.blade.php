@extends('admin.layouts.master')

@section('title', 'Edit Subcontract Agreement')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit: {{ $subcontractAgreement->agreement_number }}</h2>
        <a href="{{ route('admin.procurement.subcontract-agreements.show', $subcontractAgreement) }}" class="btn btn-secondary gap-2">Back</a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.procurement.subcontract-agreements.update', $subcontractAgreement) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div class="form-group md:col-span-2">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-input" required value="{{ old('title', $subcontractAgreement->title) }}" />
                </div>
                <div class="form-group">
                    <label for="project_id">Project</label>
                    <select name="project_id" id="project_id" class="form-select">
                        <option value="">No Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $subcontractAgreement->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="subcontractor_id">Subcontractor <span class="text-danger">*</span></label>
                    <select name="subcontractor_id" id="subcontractor_id" class="form-select" required>
                        <option value="">Select</option>
                        @foreach($subcontractors as $sub)
                            <option value="{{ $sub->id }}" {{ old('subcontractor_id', $subcontractAgreement->subcontractor_id) == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="agreement_date">Agreement Date <span class="text-danger">*</span></label>
                    <input type="date" name="agreement_date" id="agreement_date" class="form-input" required value="{{ old('agreement_date', $subcontractAgreement->agreement_date->format('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" id="start_date" class="form-input" required value="{{ old('start_date', $subcontractAgreement->start_date->format('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-input" value="{{ old('end_date', $subcontractAgreement->end_date?->format('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="contract_value">Contract Value <span class="text-danger">*</span></label>
                    <input type="number" name="contract_value" id="contract_value" class="form-input" step="0.01" min="0" required value="{{ old('contract_value', $subcontractAgreement->contract_value) }}" />
                </div>
                <div class="form-group">
                    <label for="retention_percentage">Retention (%)</label>
                    <input type="number" name="retention_percentage" id="retention_percentage" class="form-input" step="0.01" min="0" max="100" value="{{ old('retention_percentage', $subcontractAgreement->retention_percentage) }}" />
                </div>
                <div class="form-group">
                    <label for="payment_terms">Payment Terms</label>
                    <input type="text" name="payment_terms" id="payment_terms" class="form-input" value="{{ old('payment_terms', $subcontractAgreement->payment_terms) }}" />
                </div>
                <div class="form-group md:col-span-3">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="draft" {{ old('status', $subcontractAgreement->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="active" {{ old('status', $subcontractAgreement->status) == 'active' ? 'selected' : '' }}>Active</option>
                    </select>
                </div>
                <div class="form-group md:col-span-3">
                    <label for="scope_of_work">Scope of Work</label>
                    <textarea name="scope_of_work" id="scope_of_work" class="form-textarea" rows="4">{{ old('scope_of_work', $subcontractAgreement->scope_of_work) }}</textarea>
                </div>
                <div class="form-group md:col-span-3">
                    <label for="special_conditions">Special Conditions</label>
                    <textarea name="special_conditions" id="special_conditions" class="form-textarea" rows="3">{{ old('special_conditions', $subcontractAgreement->special_conditions) }}</textarea>
                </div>
                <div class="form-group md:col-span-3">
                    <label for="insurance_requirements">Insurance Requirements</label>
                    <textarea name="insurance_requirements" id="insurance_requirements" class="form-textarea" rows="2">{{ old('insurance_requirements', $subcontractAgreement->insurance_requirements) }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update Agreement</button>
                <a href="{{ route('admin.procurement.subcontract-agreements.show', $subcontractAgreement) }}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </form>
    </div>
@endsection
