@extends('admin.layouts.master')

@section('title', 'Edit Report Template')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Report Template</h2>
        <a href="{{ route('admin.reports.report-templates.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.reports.report-templates.update', $reportTemplate->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="name">Template Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-input" required value="{{ old('name', $reportTemplate->name) }}" />
                </div>
                <div class="form-group">
                    <label for="report_type">Report Type <span class="text-danger">*</span></label>
                    <select name="report_type" id="report_type" class="form-select" required>
                        <option value="">Select Type</option>
                        @foreach($reportTypes as $type)
                            <option value="{{ $type }}" {{ old('report_type', $reportTemplate->report_type) == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group md:col-span-2">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-input" rows="2">{{ old('description', $reportTemplate->description) }}</textarea>
                </div>
                <div class="form-group md:col-span-2">
                    <label for="configuration">Configuration (JSON)</label>
                    <textarea name="configuration" id="configuration" class="form-input" rows="4">{{ old('configuration', json_encode($reportTemplate->configuration, JSON_PRETTY_PRINT)) }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update Template</button>
                <button type="reset" class="btn btn-outline-danger">Reset</button>
            </div>
        </form>
    </div>
@endsection
