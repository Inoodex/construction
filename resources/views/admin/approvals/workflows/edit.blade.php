@extends('admin.layouts.master')

@section('title', 'Edit Approval Workflow')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Approval Workflow</h2>
        <a href="{{ route('admin.approvals.workflows.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form method="POST" action="{{ route('admin.approvals.workflows.update', $workflow) }}">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div class="form-group">
                    <label for="name">Workflow Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $workflow->name) }}" required>
                    @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="document_type">Document Type</label>
                    <input type="text" class="form-input" value="{{ $workflow->document_type }}" disabled>
                    <input type="hidden" name="document_type" value="{{ $workflow->document_type }}">
                    <small class="text-xs text-gray-500">Document type cannot be changed</small>
                </div>
                <div class="form-group">
                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" class="form-checkbox h-5 w-5" value="1" {{ old('is_active', $workflow->is_active) ? 'checked' : '' }}>
                        <span class="font-medium">Active</span>
                    </label>
                </div>
                <div class="form-group md:col-span-3">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-textarea" rows="3">{{ old('description', $workflow->description) }}</textarea>
                </div>
            </div>

            <div class="mt-6">
                <div class="flex items-center justify-between">
                    <h5 class="text-base font-semibold">Approval Levels</h5>
                    <button type="button" onclick="addLevel()" class="btn btn-sm btn-outline-primary">+ Add Level</button>
                </div>
                <div class="mt-3 overflow-x-auto">
                    <table class="table-hover w-full table-auto">
                        <thead>
                            <tr>
                                <th>Level</th>
                                <th>Role</th>
                                <th>Min Amount</th>
                                <th>Max Amount</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="levels-body"></tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                <div class="card bg-gray-50 p-4 rounded">
                    <h6 class="text-sm font-semibold mb-2">Information</h6>
                    <div class="grid grid-cols-2 gap-4 text-xs">
                        <div>Pending Approvals: <strong>{{ $workflow->approvals()->where('status', 'pending')->count() }}</strong></div>
                        <div>Total Approvals: <strong>{{ $workflow->approvals()->count() }}</strong></div>
                        <div>Created By: <strong>{{ $workflow->creator->name ?? 'N/A' }}</strong></div>
                        <div>Created: <strong>{{ $workflow->created_at->format('d M Y H:i') }}</strong></div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update Workflow</button>
                <button type="button" onclick="window.location.href='{{ route('admin.approvals.workflows.index') }}'"
                    class="btn btn-outline-danger">Cancel</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
const roles = @json($roles);
const existingMatrices = @json($workflow->matrices);
let levelCount = existingMatrices.length;

function addLevel(data = {}) {
    const i = levelCount++;
    const row = document.createElement('tr');
    row.id = 'level-' + i;
    row.innerHTML = `
        <td class="text-center font-semibold">${i + 1}
            <input type="hidden" name="matrices[${i}][approval_level]" value="${i + 1}">
        </td>
        <td>
            <select name="matrices[${i}][role_id]" class="form-select" required>
                <option value="">Select Role</option>
                ${roles.map(r => `<option value="${r.id}" ${data.role_id == r.id ? 'selected' : ''}>${r.name}</option>`).join('')}
            </select>
        </td>
        <td><input type="number" step="0.01" min="0" name="matrices[${i}][min_amount]" class="form-input" value="${data.min_amount || ''}" required></td>
        <td><input type="number" step="0.01" min="0" name="matrices[${i}][max_amount]" class="form-input" value="${data.max_amount || ''}" required></td>
        <td class="text-center">
            <button type="button" onclick="document.getElementById('level-${i}').remove()" class="btn btn-sm btn-outline-danger">Remove</button>
        </td>
    `;
    document.getElementById('levels-body').appendChild(row);
}

existingMatrices.forEach(m => addLevel(m));
</script>
@endpush
