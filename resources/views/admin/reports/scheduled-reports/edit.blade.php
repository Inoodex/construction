@extends('admin.layouts.master')

@section('title', 'Edit Scheduled Report')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Scheduled Report</h2>
        <a href="{{ route('admin.reports.scheduled-reports.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.reports.scheduled-reports.update', $scheduledReport->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div class="form-group">
                    <label for="report_template_id">Report Template <span class="text-danger">*</span></label>
                    <select name="report_template_id" id="report_template_id" class="form-select" required>
                        <option value="">Select Template</option>
                        @foreach($templates as $t)
                            <option value="{{ $t->id }}" {{ old('report_template_id', $scheduledReport->report_template_id) == $t->id ? 'selected' : '' }}>{{ $t->name }} ({{ $t->report_type }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="frequency">Frequency <span class="text-danger">*</span></label>
                    <select name="frequency" id="frequency" class="form-select" required>
                        <option value="daily" {{ old('frequency', $scheduledReport->frequency) == 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ old('frequency', $scheduledReport->frequency) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ old('frequency', $scheduledReport->frequency) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="active" {{ old('status', $scheduledReport->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $scheduledReport->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="next_run_at">Next Run <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="next_run_at" id="next_run_at" class="form-input" required value="{{ old('next_run_at', $scheduledReport->next_run_at->format('Y-m-d\TH:i')) }}" />
                </div>
                <div class="form-group md:col-span-2">
                    <label for="recipients">Recipients (email addresses) <span class="text-danger">*</span></label>
                    <div id="recipients-wrapper">
                        @foreach(old('recipients', $scheduledReport->recipients) as $i => $email)
                            <div class="flex gap-2 mb-2">
                                <input type="email" name="recipients[]" class="form-input flex-1" required value="{{ $email }}" />
                                <button type="button" onclick="this.parentElement.remove()" class="btn btn-sm btn-outline-danger">-</button>
                            </div>
                        @endforeach
                        <button type="button" onclick="addRecipient()" class="btn btn-sm btn-outline-primary">+ Add Another</button>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update Schedule</button>
                <button type="reset" class="btn btn-outline-danger">Reset</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
function addRecipient() {
    const wrapper = document.getElementById('recipients-wrapper');
    const div = document.createElement('div');
    div.className = 'flex gap-2 mb-2';
    div.innerHTML = `
        <input type="email" name="recipients[]" class="form-input flex-1" required placeholder="email@example.com" />
        <button type="button" onclick="this.parentElement.remove()" class="btn btn-sm btn-outline-danger">-</button>
    `;
    wrapper.insertBefore(div, wrapper.lastElementChild);
}
</script>
@endpush
