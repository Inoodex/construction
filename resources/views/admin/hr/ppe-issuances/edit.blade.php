@extends('admin.layouts.master')

@section('title', 'Edit PPE Issuance')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit PPE Issuance</h2>
        <a href="{{ route('admin.hr.ppe-issuances.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.hr.ppe-issuances.update', $ppeIssuance) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">Employee <span class="text-danger">*</span></label>
                    <select name="employee_id" class="form-select" required>
                        <option value="">Select employee</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id', $ppeIssuance->employee_id) == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }}</option>
                        @endforeach
                    </select>
                    @error('employee_id') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Item Name <span class="text-danger">*</span></label>
                    <input type="text" name="item_name" class="form-input" required value="{{ old('item_name', $ppeIssuance->item_name) }}" />
                    @error('item_name') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Equipment Category</label>
                    <select name="category" class="form-select">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->value }}" {{ old('category', $ppeIssuance->category) == $cat->value ? 'selected' : '' }}>{{ $cat->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Size</label>
                    <input type="text" name="size" class="form-input" value="{{ old('size', $ppeIssuance->size) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" class="form-input" required value="{{ old('quantity', $ppeIssuance->quantity) }}" min="1" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Issue Date <span class="text-danger">*</span></label>
                    <input type="date" name="issue_date" class="form-input" required value="{{ old('issue_date', $ppeIssuance->issue_date?->format('Y-m-d')) }}" />
                    @error('issue_date') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Condition on Issue</label>
                    <input type="text" name="condition_on_issue" class="form-input" value="{{ old('condition_on_issue', $ppeIssuance->condition_on_issue) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Return Date</label>
                    <input type="date" name="return_date" class="form-input" value="{{ old('return_date', $ppeIssuance->return_date?->format('Y-m-d')) }}" />
                    @error('return_date') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Condition on Return</label>
                    <input type="text" name="condition_on_return" class="form-input" value="{{ old('condition_on_return', $ppeIssuance->condition_on_return) }}" />
                </div>
            </div>
            <div class="mt-4">
                <label class="text-sm font-semibold">Notes</label>
                <textarea name="notes" class="form-textarea" rows="3">{{ old('notes', $ppeIssuance->notes) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Update Issuance</button>
        </form>
    </div>
@endsection
