@extends('admin.layouts.master')

@section('title', 'Edit Punch List')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Punch List: {{ $punchList->punch_list_number }}</h2>
        <a href="{{ route('admin.quality.punch-lists.show', $punchList) }}" class="btn btn-secondary gap-2">&larr; Back</a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.quality.punch-lists.update', $punchList) }}" method="POST" x-data="punchListForm({{ $punchList->items->map(fn($i) => ['id'=>$i->id,'description'=>$i->description,'location'=>$i->location,'trade'=>$i->trade,'priority'=>$i->priority,'status'=>$i->status,'assigned_to'=>$i->assigned_to,'completed_date'=>$i->completed_date?->format('Y-m-d')??'','verified_date'=>$i->verified_date?->format('Y-m-d')??'','notes'=>$i->notes])->toJson() }})">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">Project <span class="text-danger">*</span></label>
                    <select name="project_id" class="form-select" required>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $punchList->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-input" required value="{{ old('title', $punchList->title) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        @foreach(['open','in_progress','completed','closed'] as $st)
                            <option value="{{ $st }}" {{ old('status', $punchList->status) == $st ? 'selected' : '' }}>{{ str_replace('_', ' ', ucfirst($st)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Inspection Date <span class="text-danger">*</span></label>
                    <input type="date" name="inspection_date" class="form-input" required value="{{ old('inspection_date', $punchList->inspection_date->format('Y-m-d')) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Due Date</label>
                    <input type="date" name="due_date" class="form-input" value="{{ old('due_date', $punchList->due_date?->format('Y-m-d')) }}" />
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Description</label>
                    <textarea name="description" class="form-textarea" rows="2">{{ old('description', $punchList->description) }}</textarea>
                </div>
            </div>

            <div class="mt-6">
                <div class="mb-3 flex items-center justify-between">
                    <h4 class="font-semibold">Punch List Items</h4>
                    <button type="button" class="btn btn-sm btn-outline-primary" @click="addItem()">+ Add Item</button>
                </div>

                <template x-for="(item, index) in items" :key="index">
                    <div class="mb-3 rounded border p-3">
                        <div class="grid grid-cols-12 gap-2">
                            <div class="col-span-4">
                                <label class="text-xs font-semibold">Description <span class="text-danger">*</span></label>
                                <input type="text" :name="'items[' + index + '][description]'" class="form-input" x-model="item.description" required />
                                <input type="hidden" :name="'items[' + index + '][id]'" x-model="item.id" />
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
                            <div class="col-span-1">
                                <label class="text-xs font-semibold">Status</label>
                                <select :name="'items[' + index + '][status]'" class="form-select" x-model="item.status" required>
                                    <option value="open">Open</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                    <option value="verified">Verified</option>
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
                        <div class="mt-2 grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-xs font-semibold">Completed Date</label>
                                <input type="date" :name="'items[' + index + '][completed_date]'" class="form-input" x-model="item.completed_date" />
                            </div>
                            <div>
                                <label class="text-xs font-semibold">Notes</label>
                                <input type="text" :name="'items[' + index + '][notes]'" class="form-input" x-model="item.notes" />
                            </div>
                        </div>
                    </div>
                </template>

                <div x-show="items.length === 0" class="text-center text-sm text-gray-400 py-4">
                    No items. Click "+ Add Item" to begin.
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-4">Update Punch List</button>
        </form>
    </div>
@endsection

@push('scripts')
<script>
function punchListForm(existingItems = []) {
    return {
        items: existingItems,
        addItem() {
            this.items.push({ id: null, description: '', location: '', trade: 'civil', priority: 'medium', status: 'open', assigned_to: '', completed_date: '', verified_date: '', notes: '' });
        },
        removeItem(index) {
            this.items.splice(index, 1);
        }
    };
}
</script>
@endpush
