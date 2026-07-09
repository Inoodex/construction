@extends('admin.layouts.master')

@section('title', 'Edit Material Test Certificate')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Certificate: {{ $materialTestCertificate->certificate_number }}</h2>
        <a href="{{ route('admin.quality.material-test-certificates.show', $materialTestCertificate) }}" class="btn btn-secondary gap-2">&larr; Back</a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.quality.material-test-certificates.update', $materialTestCertificate) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">Project <span class="text-danger">*</span></label>
                    <select name="project_id" class="form-select" required>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $materialTestCertificate->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Certificate Number <span class="text-danger">*</span></label>
                    <input type="text" name="certificate_number" class="form-input" required value="{{ old('certificate_number', $materialTestCertificate->certificate_number) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Material Name <span class="text-danger">*</span></label>
                    <input type="text" name="material_name" class="form-input" required value="{{ old('material_name', $materialTestCertificate->material_name) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Material Type <span class="text-danger">*</span></label>
                    <select name="material_type" class="form-select" required>
                        @foreach(['concrete','steel','soil','aggregate','cement','other'] as $type)
                            <option value="{{ $type }}" {{ old('material_type', $materialTestCertificate->material_type) == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Supplier</label>
                    <input type="text" name="supplier" class="form-input" value="{{ old('supplier', $materialTestCertificate->supplier) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Batch Number</label>
                    <input type="text" name="batch_number" class="form-input" value="{{ old('batch_number', $materialTestCertificate->batch_number) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Test Date <span class="text-danger">*</span></label>
                    <input type="date" name="test_date" class="form-input" required value="{{ old('test_date', $materialTestCertificate->test_date->format('Y-m-d')) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Test Result <span class="text-danger">*</span></label>
                    <select name="test_result" class="form-select" required>
                        @foreach(['pass','fail','conditional'] as $res)
                            <option value="{{ $res }}" {{ old('test_result', $materialTestCertificate->test_result) == $res ? 'selected' : '' }}>{{ ucfirst($res) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Compliance Status <span class="text-danger">*</span></label>
                    <select name="compliance_status" class="form-select" required>
                        @foreach(['pending','compliant','non_compliant'] as $cs)
                            <option value="{{ $cs }}" {{ old('compliance_status', $materialTestCertificate->compliance_status) == $cs ? 'selected' : '' }}>{{ str_replace('_', ' ', ucfirst($cs)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Valid Until</label>
                    <input type="date" name="valid_until" class="form-input" value="{{ old('valid_until', $materialTestCertificate->valid_until?->format('Y-m-d')) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Replace Certificate File</label>
                    <input type="file" name="certificate_file" class="form-input" accept=".pdf,.jpg,.jpeg,.png" />
                    @if($materialTestCertificate->certificate_file)
                        <p class="text-xs text-gray-400 mt-1">Current: {{ basename($materialTestCertificate->certificate_file) }}</p>
                    @endif
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Test Parameters</label>
                    <textarea name="test_parameters" class="form-textarea" rows="3">{{ old('test_parameters', $materialTestCertificate->test_parameters) }}</textarea>
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Notes</label>
                    <textarea name="notes" class="form-textarea" rows="2">{{ old('notes', $materialTestCertificate->notes) }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Update Certificate</button>
        </form>
    </div>
@endsection
