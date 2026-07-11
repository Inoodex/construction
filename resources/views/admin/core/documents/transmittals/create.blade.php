@extends('admin.layouts.master')

@section('title', 'New Transmittal')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">New Drawing Transmittal</h2>
        <a href="{{ route('admin.core.documents.transmittals.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.core.documents.transmittals.store') }}" method="POST" id="transmittalForm">
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
                    <label for="sent_date">Sent Date <span class="text-danger">*</span></label>
                    <input type="date" name="sent_date" id="sent_date" class="form-input" required value="{{ old('sent_date', date('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="to_party">To Party <span class="text-danger">*</span></label>
                    <input type="text" name="to_party" id="to_party" class="form-input" required value="{{ old('to_party') }}" />
                </div>
                <div class="form-group">
                    <label for="purpose">Purpose <span class="text-danger">*</span></label>
                    <select name="purpose" id="purpose" class="form-select" required>
                        <option value="for_approval" {{ old('purpose') == 'for_approval' ? 'selected' : '' }}>For Approval</option>
                        <option value="for_information" {{ old('purpose') == 'for_information' ? 'selected' : '' }}>For Information</option>
                        <option value="for_construction" {{ old('purpose') == 'for_construction' ? 'selected' : '' }}>For Construction</option>
                        <option value="as_built" {{ old('purpose') == 'as_built' ? 'selected' : '' }}>As-Built</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="sent" {{ old('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                        <option value="acknowledged" {{ old('status') == 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                    </select>
                </div>
                <div class="form-group md:col-span-2">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-textarea" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>

            <hr class="my-6 border-white-light dark:border-gray-700">

            <div class="mb-3 flex items-center justify-between">
                <h5 class="text-base font-semibold">Drawing Items</h5>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addItem()">+ Add Drawing</button>
            </div>

            <div id="items-container" class="space-y-3">
                <div class="item-row flex items-end gap-3 rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <div class="flex-1">
                        <label class="text-xs">Drawing <span class="text-danger">*</span></label>
                        <select name="drawings[0][drawing_id]" class="form-select" required>
                            <option value="">Select Drawing</option>
                            @foreach($drawings as $drawing)
                                <option value="{{ $drawing->id }}">{{ $drawing->drawing_number }} — {{ $drawing->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="text-xs">Revision ID (optional)</label>
                        <input type="number" name="drawings[0][revision_id]" class="form-input" placeholder="Revision ID" />
                    </div>
                    <div class="w-24">
                        <label class="text-xs">Copies</label>
                        <input type="number" name="drawings[0][copies]" class="form-input" value="1" min="1" />
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger mb-0.5" onclick="removeItem(this)">Remove</button>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Create Transmittal</button>
                <button type="reset" class="btn btn-outline-danger">Reset</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
let itemIndex = 1;

function addItem() {
    const container = document.getElementById('items-container');
    const drawings = @json($drawings);
    let options = '<option value="">Select Drawing</option>';
    drawings.forEach(d => {
        options += `<option value="${d.id}">${d.drawing_number} — ${d.title}</option>`;
    });
    const html = `
        <div class="item-row flex items-end gap-3 rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
            <div class="flex-1">
                <label class="text-xs">Drawing <span class="text-danger">*</span></label>
                <select name="drawings[${itemIndex}][drawing_id]" class="form-select" required>${options}</select>
            </div>
            <div class="flex-1">
                <label class="text-xs">Revision ID (optional)</label>
                <input type="number" name="drawings[${itemIndex}][revision_id]" class="form-input" placeholder="Revision ID" />
            </div>
            <div class="w-24">
                <label class="text-xs">Copies</label>
                <input type="number" name="drawings[${itemIndex}][copies]" class="form-input" value="1" min="1" />
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger mb-0.5" onclick="removeItem(this)">Remove</button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    itemIndex++;
}

function removeItem(btn) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length > 1) {
        btn.closest('.item-row').remove();
    } else {
        alert('At least one drawing item is required.');
    }
}
</script>
@endpush
