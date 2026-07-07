@extends('admin.layouts.master')

@section('title', 'Create Project')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Create Project</h2>
        <a href="{{ route('admin.core.projects.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.core.projects.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group md:col-span-2">
                    <label for="name">Project Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-input" required
                        value="{{ old('name') }}" />
                    @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="budget">Budget (৳) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" name="budget" id="budget" class="form-input" required
                        value="{{ old('budget') }}" />
                    @error('budget') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="planning" {{ old('status') == 'planning' ? 'selected' : '' }}>Planning</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="on_hold" {{ old('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    @error('status') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="client_id">Client</label>
                    <select name="client_id" id="client_id" class="form-select">
                        <option value="">No Client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->company_name }}</option>
                        @endforeach
                    </select>
                    @error('client_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" id="start_date" class="form-input" required
                        value="{{ old('start_date') }}" />
                    @error('start_date') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="end_date">End Date <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" id="end_date" class="form-input" required
                        value="{{ old('end_date') }}" />
                    @error('end_date') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group mt-5">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-textarea" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Save Project</button>
                <button type="reset" class="btn btn-outline-danger">Reset Form</button>
            </div>
        </form>
    </div>
@endsection
