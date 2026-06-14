@extends('admin.layouts.master')

@section('title', 'Edit Inspection Checklist')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Checklist — {{ $checklist->title }}</h2>
        <a href="{{ route('admin.core.inspection-checklists.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.core.inspection-checklists.update', $checklist) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group md:col-span-2">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-input" required value="{{ old('title', $checklist->title) }}" />
                </div>
                <div class="form-group">
                    <label for="site_id">Site <span class="text-danger">*</span></label>
                    <select name="site_id" id="site_id" class="form-select" required>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ old('site_id', $checklist->site_id) == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="inspection_date">Inspection Date <span class="text-danger">*</span></label>
                    <input type="date" name="inspection_date" id="inspection_date" class="form-input" required value="{{ old('inspection_date', $checklist->inspection_date?->format('Y-m-d')) }}" />
                </div>
                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="pending" {{ old('status', $checklist->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="passed" {{ old('status', $checklist->status) == 'passed' ? 'selected' : '' }}>Passed</option>
                        <option value="failed" {{ old('status', $checklist->status) == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="conditional" {{ old('status', $checklist->status) == 'conditional' ? 'selected' : '' }}>Conditional</option>
                    </select>
                </div>
                <div class="form-group md:col-span-2">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-textarea" rows="3">{{ old('description', $checklist->description) }}</textarea>
                </div>
            </div>

            <div class="mt-5">
                <div class="mb-3 flex items-center justify-between">
                    <label class="text-sm font-semibold">Checklist Items</label>
                    <button type="button" onclick="addItem()" class="btn btn-sm btn-outline-primary">+ Add Item</button>
                </div>
                <div id="items-container" class="space-y-2">
                    @forelse($checklist->items as $i => $item)
                        <div class="item-row flex items-start gap-2 rounded-lg border p-3 dark:border-gray-700">
                            <input type="hidden" name="items[{{ $i }}][id]" value="{{ $item->id }}" />
                            <div class="flex-1">
                                <input type="text" name="items[{{ $i }}][item_name]" value="{{ $item->item_name }}" class="form-input text-sm" required />
                            </div>
                            <label class="flex items-center gap-1 text-xs whitespace-nowrap mt-2">
                                <input type="checkbox" name="items[{{ $i }}][is_checked]" value="1" class="form-checkbox h-4 w-4" {{ $item->is_checked ? 'checked' : '' }} />
                                Pass
                            </label>
                            <input type="text" name="items[{{ $i }}][remarks]" value="{{ $item->remarks }}" placeholder="Remarks" class="form-input text-sm w-40" />
                            <button type="button" onclick="this.closest('.item-row').remove()" class="mt-2 text-danger hover:underline text-xs">Remove</button>
                        </div>
                    @empty
                        <div class="item-row flex items-start gap-2 rounded-lg border p-3 dark:border-gray-700">
                            <div class="flex-1">
                                <input type="text" name="items[0][item_name]" placeholder="Item description" class="form-input text-sm" required />
                            </div>
                            <label class="flex items-center gap-1 text-xs whitespace-nowrap mt-2">
                                <input type="checkbox" name="items[0][is_checked]" value="1" class="form-checkbox h-4 w-4" />
                                Pass
                            </label>
                            <input type="text" name="items[0][remarks]" placeholder="Remarks" class="form-input text-sm w-40" />
                            <button type="button" onclick="this.closest('.item-row').remove()" class="mt-2 text-danger hover:underline text-xs">Remove</button>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="form-group mt-5">
                <label for="notes">Notes</label>
                <textarea name="notes" id="notes" class="form-textarea" rows="3">{{ old('notes', $checklist->notes) }}</textarea>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update Checklist</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
let itemIndex = {{ max(count($checklist->items), 1) }};
function addItem() {
    const container = document.getElementById('items-container');
    const div = document.createElement('div');
    div.className = 'item-row flex items-start gap-2 rounded-lg border p-3 dark:border-gray-700';
    div.innerHTML = `
        <div class="flex-1">
            <input type="text" name="items[${itemIndex}][item_name]" placeholder="Item description" class="form-input text-sm" required />
        </div>
        <label class="flex items-center gap-1 text-xs whitespace-nowrap mt-2">
            <input type="checkbox" name="items[${itemIndex}][is_checked]" value="1" class="form-checkbox h-4 w-4" />
            Pass
        </label>
        <input type="text" name="items[${itemIndex}][remarks]" placeholder="Remarks" class="form-input text-sm w-40" />
        <button type="button" onclick="this.closest('.item-row').remove()" class="mt-2 text-danger hover:underline text-xs">Remove</button>
    `;
    container.appendChild(div);
    itemIndex++;
}
</script>
@endpush
