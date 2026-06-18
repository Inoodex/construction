@extends('admin.layouts.master')

@section('title', 'Record Labour Entry')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Record Labour Entry</h2>
        <a href="{{ route('admin.finance.labour-entries.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.finance.labour-entries.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="project_id">Project <span class="text-danger">*</span></label>
                    <select name="project_id" id="project_id" class="form-select" required>
                        <option value="">Select</option>
                        @foreach($projects as $id => $name)
                            <option value="{{ $id }}" {{ old('project_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="employee_id">Employee <span class="text-danger">*</span></label>
                    <select name="employee_id" id="employee_id" class="form-select" required>
                        <option value="">Select</option>
                        @foreach($employees as $id => $name)
                            <option value="{{ $id }}" {{ old('employee_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Date <span class="text-danger">*</span></label>
                    <input type="date" name="date" id="date" class="form-input" required value="{{ old('date', date('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="hours">Hours <span class="text-danger">*</span></label>
                    <input type="number" step="0.25" min="0.25" max="24" name="hours" id="hours" class="form-input" required value="{{ old('hours') }}" />
                </div>
                <div class="form-group">
                    <label for="hourly_rate">Hourly Rate (৳) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" name="hourly_rate" id="hourly_rate" class="form-input" required value="{{ old('hourly_rate') }}" />
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" name="description" id="description" class="form-input" value="{{ old('description') }}" />
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-6">Save Entry</button>
        </form>
    </div>
@endsection
