@extends('admin.layouts.master')

@section('title', 'Edit Budget')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Budget</h2>
        <a href="{{ route('admin.finance.budgets.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.finance.budgets.update', $budget->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div class="form-group">
                    <label for="project_id">Project <span class="text-danger">*</span></label>
                    <select name="project_id" id="project_id" class="form-select" required>
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $budget->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                    @error('project_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="cost_code">Cost Code <span class="text-danger">*</span></label>
                    <input type="text" name="cost_code" id="cost_code" class="form-input" required value="{{ old('cost_code', $budget->cost_code) }}" />
                    @error('cost_code') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="financial_year">Financial Year</label>
                    <input type="text" name="financial_year" id="financial_year" class="form-input" value="{{ old('financial_year', $budget->financial_year) }}" />
                </div>
                <div class="form-group md:col-span-2">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-input" rows="2">{{ old('description', $budget->description) }}</textarea>
                </div>
                <div class="form-group">
                    <label for="budgeted_amount">Budgeted Amount <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" name="budgeted_amount" id="budgeted_amount" class="form-input" required value="{{ old('budgeted_amount', $budget->budgeted_amount) }}" />
                    @error('budgeted_amount') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
               <br>
             {{-- <hr class="my-6 border-gray-300" />
            <h4 class="font-semibold mb-4 text-xl">Earned Value Management</h4>
            <hr class="my-6 border-gray-300" /> --}}
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div class="form-group">
                    <label for="planned_value">Planned Value (PV) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" name="planned_value" id="planned_value" class="form-input" required value="{{ old('planned_value', $budget->planned_value) }}" />
                    @error('planned_value') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="earned_value">Earned Value (EV) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" name="earned_value" id="earned_value" class="form-input" required value="{{ old('earned_value', $budget->earned_value) }}" />
                    @error('earned_value') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="actual_cost">Actual Cost (AC) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" name="actual_cost" id="actual_cost" class="form-input" required value="{{ old('actual_cost', $budget->actual_cost) }}" />
                    @error('actual_cost') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group md:col-span-3">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-input" rows="2">{{ old('notes', $budget->notes) }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update Budget</button>
                <button type="reset" class="btn btn-outline-danger">Reset</button>
            </div>
        </form>
    </div>
@endsection
