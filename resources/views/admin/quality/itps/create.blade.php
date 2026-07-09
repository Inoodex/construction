@extends('admin.layouts.master')

@section('title', 'Create ITP')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Create Inspection & Test Plan</h2>
        <a href="{{ route('admin.quality.itps.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.quality.itps.store') }}" method="POST" x-data="itpForm()">
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
                    <label class="text-sm font-semibold">Phase <span class="text-danger">*</span></label>
                    <select name="phase" class="form-select" required>
                        <option value="">Select phase</option>
                        <option value="foundation" {{ old('phase') == 'foundation' ? 'selected' : '' }}>Foundation</option>
                        <option value="superstructure" {{ old('phase') == 'superstructure' ? 'selected' : '' }}>Superstructure</option>
                        <option value="finishing" {{ old('phase') == 'finishing' ? 'selected' : '' }}>Finishing</option>
                        <option value="mep" {{ old('phase') == 'mep' ? 'selected' : '' }}>MEP</option>
                        <option value="other" {{ old('phase') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('phase') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Description</label>
                    <textarea name="description" class="form-textarea" rows="2">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="mt-6">
                <div class="mb-3 flex items-center justify-between">
                    <h4 class="font-semibold">ITP Items</h4>
                    <button type="button" class="btn btn-sm btn-outline-primary" @click="addItem()">+ Add Item</button>
                </div>

                <template x-for="(item, index) in items" :key="index">
                    <div class="mb-3 rounded border p-3">
                        <div class="grid grid-cols-12 gap-2">
                            <div class="col-span-4">
                                <label class="text-xs font-semibold">Description <span class="text-danger">*</span></label>
                                <input type="text" :name="'items[' + index + '][description]'" class="form-input" x-model="item.description" required />
                            </div>
                            <div class="col-span-2">
                                <label class="text-xs font-semibold">Spec Reference</label>
                                <input type="text" :name="'items[' + index + '][specification_reference]'" class="form-input" x-model="item.specification_reference" />
                            </div>
                            <div class="col-span-2">
                                <label class="text-xs font-semibold">Inspection Type</label>
                                <select :name="'items[' + index + '][inspection_type]'" class="form-select" x-model="item.inspection_type" required>
                                    <option value="visual">Visual</option>
                                    <option value="dimensional">Dimensional</option>
                                    <option value="testing">Testing</option>
                                    <option value="documentation">Documentation</option>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="text-xs font-semibold">Method</label>
                                <select :name="'items[' + index + '][method]'" class="form-select" x-model="item.method" required>
                                    <option value="observation">Observation</option>
                                    <option value="measurement">Measurement</option>
                                    <option value="testing">Testing</option>
                                    <option value="review">Review</option>
                                </select>
                            </div>
                            <div class="col-span-1">
                                <label class="text-xs font-semibold">Frequency</label>
                                <select :name="'items[' + index + '][frequency]'" class="form-select" x-model="item.frequency" required>
                                    <option value="each_occurrence">Each</option>
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                            </div>
                            <div class="col-span-1 flex items-end">
                                <button type="button" class="btn btn-sm btn-outline-danger" @click="removeItem(index)">✕</button>
                            </div>
                        </div>
                        <div class="mt-2">
                            <label class="text-xs font-semibold">Acceptance Criteria</label>
                            <input type="text" :name="'items[' + index + '][acceptance_criteria]'" class="form-input" x-model="item.acceptance_criteria" />
                        </div>
                    </div>
                </template>

                <div x-show="items.length === 0" class="text-center text-sm text-gray-400 py-4">
                    No items added yet. Click "+ Add Item" to begin.
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-4">Create ITP</button>
        </form>
    </div>
@endsection

@push('scripts')
<script>
function itpForm() {
    return {
        items: [],
        addItem() {
            this.items.push({ description: '', specification_reference: '', inspection_type: 'visual', acceptance_criteria: '', method: 'observation', frequency: 'each_occurrence' });
        },
        removeItem(index) {
            this.items.splice(index, 1);
        }
    };
}
</script>
@endpush
