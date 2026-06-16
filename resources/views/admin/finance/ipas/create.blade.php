@extends('admin.layouts.master')

@section('title', 'Create IPA')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Create Interim Payment Application</h2>
        <a href="{{ route('admin.finance.ipas.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.finance.ipas.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div class="form-group">
                    <label for="project_id">Project <span class="text-danger">*</span></label>
                    <select name="project_id" id="project_id" class="form-select" required>
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-input" required value="{{ old('title') }}" placeholder="e.g. 1st Interim Payment" />
                </div>
                <div class="form-group">
                    <label for="application_date">Application Date <span class="text-danger">*</span></label>
                    <input type="date" name="application_date" id="application_date" class="form-input" required value="{{ old('application_date', now()->format('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="period_start">Period Start <span class="text-danger">*</span></label>
                    <input type="date" name="period_start" id="period_start" class="form-input" required value="{{ old('period_start') }}" />
                </div>
                <div class="form-group">
                    <label for="period_end">Period End <span class="text-danger">*</span></label>
                    <input type="date" name="period_end" id="period_end" class="form-input" required value="{{ old('period_end') }}" />
                </div>
                <div class="form-group">
                    <label for="retention_rate">Retention Rate (%) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" max="100" name="retention_rate" id="retention_rate" class="form-input" required value="{{ old('retention_rate', 5) }}" />
                </div>
                <div class="form-group md:col-span-3">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-input" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Create IPA</button>
                <button type="reset" class="btn btn-outline-danger">Reset</button>
            </div>
        </form>
    </div>
@endsection
