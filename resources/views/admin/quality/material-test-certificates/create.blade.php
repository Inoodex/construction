@extends('admin.layouts.master')

@section('title', 'Create Material Test Certificate')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Create Material Test Certificate</h2>
        <a href="{{ route('admin.quality.material-test-certificates.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.quality.material-test-certificates.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">Project <span class="text-danger">*</span></label>
                    <select name="project_id" class="form-select" required>
                        <option value="">Select project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                    @error('project_id') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Certificate Number <span class="text-danger">*</span></label>
                    <input type="text" name="certificate_number" class="form-input" required value="{{ old('certificate_number') }}" />
                    @error('certificate_number') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Material Name <span class="text-danger">*</span></label>
                    <input type="text" name="material_name" class="form-input" required value="{{ old('material_name') }}" placeholder="e.g. Concrete Mix C30" />
                    @error('material_name') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Material Type <span class="text-danger">*</span></label>
                    <select name="material_type" class="form-select" required>
                        <option value="">Select type</option>
                        <option value="concrete" {{ old('material_type') == 'concrete' ? 'selected' : '' }}>Concrete</option>
                        <option value="steel" {{ old('material_type') == 'steel' ? 'selected' : '' }}>Steel</option>
                        <option value="soil" {{ old('material_type') == 'soil' ? 'selected' : '' }}>Soil</option>
                        <option value="aggregate" {{ old('material_type') == 'aggregate' ? 'selected' : '' }}>Aggregate</option>
                        <option value="cement" {{ old('material_type') == 'cement' ? 'selected' : '' }}>Cement</option>
                        <option value="other" {{ old('material_type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('material_type') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Supplier</label>
                    <input type="text" name="supplier" class="form-input" value="{{ old('supplier') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Batch Number</label>
                    <input type="text" name="batch_number" class="form-input" value="{{ old('batch_number') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Test Date <span class="text-danger">*</span></label>
                    <input type="date" name="test_date" class="form-input" required value="{{ old('test_date', date('Y-m-d')) }}" />
                    @error('test_date') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Test Result <span class="text-danger">*</span></label>
                    <select name="test_result" class="form-select" required>
                        <option value="pass" {{ old('test_result') == 'pass' ? 'selected' : '' }}>Pass</option>
                        <option value="fail" {{ old('test_result') == 'fail' ? 'selected' : '' }}>Fail</option>
                        <option value="conditional" {{ old('test_result') == 'conditional' ? 'selected' : '' }}>Conditional</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Compliance Status <span class="text-danger">*</span></label>
                    <select name="compliance_status" class="form-select" required>
                        <option value="pending" {{ old('compliance_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="compliant" {{ old('compliance_status') == 'compliant' ? 'selected' : '' }}>Compliant</option>
                        <option value="non_compliant" {{ old('compliance_status') == 'non_compliant' ? 'selected' : '' }}>Non-Compliant</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Valid Until</label>
                    <input type="date" name="valid_until" class="form-input" value="{{ old('valid_until') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Certificate File</label>
                    <input type="file" name="certificate_file" class="form-input" accept=".pdf,.jpg,.jpeg,.png" />
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Test Parameters</label>
                    <textarea name="test_parameters" class="form-textarea" rows="3" placeholder="Describe what was tested, standards used, etc.">{{ old('test_parameters') }}</textarea>
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Notes</label>
                    <textarea name="notes" class="form-textarea" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Create Certificate</button>
        </form>
    </div>
@endsection
