@extends('admin.layouts.master')

@section('title', 'Edit Expense')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Expense</h2>
        <a href="{{ route('admin.finance.expenses.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.finance.expenses.update', $expense->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div class="form-group">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-input" required value="{{ old('title', $expense->title) }}" />
                </div>
                <div class="form-group">
                    <label for="category_id">Category <span class="text-danger">*</span></label>
                    <select name="category_id" id="category_id" class="form-select" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $expense->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="expense_date">Expense Date <span class="text-danger">*</span></label>
                    <input type="date" name="expense_date" id="expense_date" class="form-input" required value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="amount">Amount <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" name="amount" id="amount" class="form-input" required value="{{ old('amount', $expense->amount) }}" />
                </div>
                <div class="form-group">
                    <label for="tax_rate">Tax Rate (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="tax_rate" id="tax_rate" class="form-input" value="{{ old('tax_rate', $expense->tax_rate) }}" />
                </div>
                <div class="form-group">
                    <label for="payment_method">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-select">
                        <option value="">Select</option>
                        <option value="cash" {{ old('payment_method', $expense->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bank_transfer" {{ old('payment_method', $expense->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="cheque" {{ old('payment_method', $expense->payment_method) == 'cheque' ? 'selected' : '' }}>Cheque</option>
                        <option value="credit_card" {{ old('payment_method', $expense->payment_method) == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="vendor_id">Vendor</label>
                    <select name="vendor_id" id="vendor_id" class="form-select">
                        <option value="">None</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ old('vendor_id', $expense->vendor_id) == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="project_id">Project</label>
                    <select name="project_id" id="project_id" class="form-select">
                        <option value="">None (General Expense)</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $expense->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="reference_number">Reference #</label>
                    <input type="text" name="reference_number" id="reference_number" class="form-input" value="{{ old('reference_number', $expense->reference_number) }}" />
                </div>
                <div class="form-group md:col-span-3">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-input" rows="2">{{ old('description', $expense->description) }}</textarea>
                </div>
                <div class="form-group md:col-span-3">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-input" rows="2">{{ old('notes', $expense->notes) }}</textarea>
                </div>
                <div class="form-group md:col-span-3">
                    <label for="receipt">Receipt</label>
                    @if($expense->receipt)
                        <div class="mb-2">
                            <a href="{{ asset('storage/' . $expense->receipt) }}" target="_blank" class="btn btn-sm btn-outline-info">View Current Receipt</a>
                        </div>
                    @endif
                    <input type="file" name="receipt" id="receipt" class="form-input" accept=".jpg,.jpeg,.png,.pdf" />
                    <p class="mt-1 text-xs text-white-dark">Accepted: JPG, PNG, PDF (max 2MB). Leave empty to keep current.</p>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update Expense</button>
                <button type="reset" class="btn btn-outline-danger">Reset</button>
            </div>
        </form>
    </div>
@endsection
