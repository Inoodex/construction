@extends('admin.layouts.master')

@section('title', 'Resubmit: ' . $materialSubmittal->submittal_number)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Resubmit: {{ $materialSubmittal->submittal_number }}</h2>
        <a href="{{ route('admin.procurement.material-submittals.show', $materialSubmittal) }}" class="btn btn-secondary gap-2">Back</a>
    </div>

    <div class="panel mt-6">
        <div class="mb-4 p-3 bg-red-50 dark:bg-red-950 border border-red-200 dark:border-red-800 rounded text-sm">
            <span class="font-semibold text-danger">Rejection reason:</span>
            {{ $materialSubmittal->review_comments ?? 'No comments provided.' }}
            @if($materialSubmittal->resubmission_deadline)
                <br /><span class="font-semibold text-danger mt-1 inline-block">Resubmission deadline: {{ $materialSubmittal->resubmission_deadline->format('d/m/Y') }}</span>
            @endif
        </div>

        <form action="{{ route('admin.procurement.material-submittals.resubmit', $materialSubmittal) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-5">
                <div class="form-group">
                    <label for="description">Updated Description</label>
                    <textarea name="description" id="description" class="form-textarea" rows="3">{{ old('description', $materialSubmittal->description) }}</textarea>
                </div>
                <div class="form-group">
                    <label for="specification_details">Updated Specification Details</label>
                    <textarea name="specification_details" id="specification_details" class="form-textarea" rows="5" placeholder="Address the reviewer's comments and provide updated specifications...">{{ old('specification_details', $materialSubmittal->specification_details) }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Resubmit</button>
                <a href="{{ route('admin.procurement.material-submittals.show', $materialSubmittal) }}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </form>
    </div>
@endsection
