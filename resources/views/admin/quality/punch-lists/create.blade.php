@extends('admin.layouts.master')

@section('title', 'Create Punch List')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Create Punch List</h2>
        <a href="{{ route('admin.quality.punch-lists.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.quality.punch-lists.store') }}" method="POST" x-data="punchListForm()">
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
                    <label class="text-sm font-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-input" required value="{{ old('title') }}" />
                    @error('title') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Inspection Date <span class="text-danger">*</span></label>
                    <input type="date" name="inspection_date" class="form-input" required value="{{ old('inspection_date', date('Y-m-d')) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Due Date</label>
                    <input type="date" name="due_date" class="form-input" value="{{ old('due_date') }}" />
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Description</label>
                    <textarea name="description" class="form-textarea" rows="2">{{ old('description') }}</textarea>
                </div>
            </div>

            {{-- Items --}}
            <div class="mt-6">
                <div class="mb-3 flex items-center justify-between">
                    <h4 class="font-semibold">Punch List Items</h4>
                    <button type="button" class="btn btn-sm btn-outline-primary" @click="addItem()">+ Add Item</button>
                </div>

                <template x-for="(item, index) in items" :key="index">
                    <div class="mb-3 rounded border p-3">
                        <div class="grid grid-cols-12 gap-2">
                            <div class="col-span-5">
                                <label class="text-xs font-semibold">Description <span class="text-danger">*</span></label>
                                <input type="text" :name="'items[' + index + '][description]'" class="form-input" x-model="item.description" required />
                            </div>
                            <div class="col-span-2">
                                <label class="text-xs font-semibold">Location</label>
                                <input type="text" :name="'items[' + index + '][location]'" class="form-input" x-model="item.location" />
                            </div>
                            <div class="col-span-1">
                                <label class="text-xs font-semibold">Trade</label>
                                <select :name="'items[' + index + '][trade]'" class="form-select" x-model="item.trade" required>
                                    <option value="civil">Civil</option>
                                    <option value="electrical">Electrical</option>
                                    <option value="mechanical">Mechanical</option>
                                    <option value="plumbing">Plumbing</option>
                                    <option value="painting">Painting</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-span-1">
                                <label class="text-xs font-semibold">Priority</label>
                                <select :name="'items[' + index + '][priority]'" class="form-select" x-model="item.priority" required>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="critical">Critical</option>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="text-xs font-semibold">Assigned To</label>
                                <input type="text" :name="'items[' + index + '][assigned_to]'" class="form-input" x-model="item.assigned_to" />
                            </div>
                            <div class="col-span-1 flex items-end">
                                <button type="button" class="btn btn-sm btn-outline-danger" @click="removeItem(index)">✕</button>
                            </div>
                        </div>
                        <div class="mt-2">
                            <label class="text-xs font-semibold">Notes</label>
                            <input type="text" :name="'items[' + index + '][notes]'" class="form-input" x-model="item.notes" />
                        </div>
                    </div>
                </template>

                <div x-show="items.length === 0" class="text-center text-sm text-gray-400 py-4">
                    No items added yet. Click "+ Add Item" to begin.
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-4">Create Punch List</button>
        </form>
    </div>
@endsection

@push('scripts')
<script>
function punchListForm() {
    return {
        items: [],
        addItem() {
            this.items.push({ description: '', location: '', trade: 'civil', priority: 'medium', assigned_to: '', notes: '' });
        },
        removeItem(index) {
            this.items.splice(index, 1);
        }
    };
}
</script>
@endpush
