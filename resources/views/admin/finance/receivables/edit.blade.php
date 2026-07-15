@extends('admin.layouts.master')

@section('title', 'Edit Receivable')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Receivable: {{ $receivable->receivable_number }}</h2>
        <a href="{{ route('admin.finance.receivables.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.finance.receivables.update', $receivable) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="receivable_number">Receivable # <span class="text-danger">*</span></label>
                    <input type="text" name="receivable_number" id="receivable_number" class="form-input" required value="{{ old('receivable_number', $receivable->receivable_number) }}" />
                    @error('receivable_number') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="payer_name">Payer Name <span class="text-danger">*</span></label>
                    <input type="text" name="payer_name" id="payer_name" class="form-input" required value="{{ old('payer_name', $receivable->payer_name) }}" />
                    @error('payer_name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="amount">Amount (৳) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" name="amount" id="amount" class="form-input" required value="{{ old('amount', $receivable->amount) }}" />
                    @error('amount') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="due_date">Due Date <span class="text-danger">*</span></label>
                    <input type="date" name="due_date" id="due_date" class="form-input" required value="{{ old('due_date', $receivable->due_date->format('Y-m-d')) }}" />
                    @error('due_date') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="project_id">Project</label>
                    <select name="project_id" id="project_id" class="form-select">
                        <option value="">— None —</option>
                        @foreach($projects as $id => $name)
                            <option value="{{ $id }}" {{ old('project_id', $receivable->project_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-textarea" rows="2">{{ old('notes', $receivable->notes) }}</textarea>
                </div>
                <div class="form-group md:col-span-2">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-textarea" rows="2">{{ old('description', $receivable->description) }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update Receivable</button>
            </div>
        </form>
    </div>
@endsection
