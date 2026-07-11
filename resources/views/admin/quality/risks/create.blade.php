@extends('admin.layouts.master')

@section('title', 'Register New Risk')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Register New Risk</h2>
        <a href="{{ route('admin.quality.risks.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.quality.risks.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="project_id">Project <span class="text-danger">*</span></label>
                    <select name="project_id" id="project_id" class="form-select" required>
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                    @error('project_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="category">Category <span class="text-danger">*</span></label>
                    <select name="category" id="category" class="form-select" required>
                        @foreach(['technical','safety','financial','environmental','schedule','other'] as $cat)
                            <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group md:col-span-2">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-input" required value="{{ old('title') }}" />
                    @error('title') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group md:col-span-2">
                    <label for="description">Description <span class="text-danger">*</span></label>
                    <textarea name="description" id="description" class="form-textarea" rows="3" required>{{ old('description') }}</textarea>
                    @error('description') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="probability">Probability <span class="text-danger">*</span></label>
                    <select name="probability" id="probability" class="form-select" required>
                        @foreach(['very_low','low','medium','high','very_high'] as $p)
                            <option value="{{ $p }}" {{ old('probability', 'medium') == $p ? 'selected' : '' }}>{{ str_replace('_', ' ', ucfirst($p)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="impact">Impact <span class="text-danger">*</span></label>
                    <select name="impact" id="impact" class="form-select" required>
                        @foreach(['very_low','low','medium','high','very_high'] as $i)
                            <option value="{{ $i }}" {{ old('impact', 'medium') == $i ? 'selected' : '' }}>{{ str_replace('_', ' ', ucfirst($i)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="risk_owner_id">Risk Owner</label>
                    <select name="risk_owner_id" id="risk_owner_id" class="form-select">
                        <option value="">Unassigned</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('risk_owner_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="identified_date">Identified Date <span class="text-danger">*</span></label>
                    <input type="date" name="identified_date" id="identified_date" class="form-input" required value="{{ old('identified_date', date('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="due_date">Mitigation Due Date</label>
                    <input type="date" name="due_date" id="due_date" class="form-input" value="{{ old('due_date') }}" />
                </div>
                <div class="form-group">
                    <label for="review_date">Next Review Date</label>
                    <input type="date" name="review_date" id="review_date" class="form-input" value="{{ old('review_date') }}" />
                </div>
                <div class="form-group md:col-span-2">
                    <label for="mitigation_plan">Mitigation Plan</label>
                    <textarea name="mitigation_plan" id="mitigation_plan" class="form-textarea" rows="3">{{ old('mitigation_plan') }}</textarea>
                </div>
                <div class="form-group md:col-span-2">
                    <label for="contingency_plan">Contingency Plan</label>
                    <textarea name="contingency_plan" id="contingency_plan" class="form-textarea" rows="3">{{ old('contingency_plan') }}</textarea>
                </div>
                <div class="form-group md:col-span-2">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-textarea" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Register Risk</button>
                <button type="reset" class="btn btn-outline-danger">Reset</button>
            </div>
        </form>
    </div>
@endsection
