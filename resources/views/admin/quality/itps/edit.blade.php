@extends('admin.layouts.master')

@section('title', 'Edit ITP')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit ITP: {{ $itp->itp_number }}</h2>
        <a href="{{ route('admin.quality.itps.show', $itp) }}" class="btn btn-secondary gap-2">&larr; Back</a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.quality.itps.update', $itp) }}" method="POST" x-data="itpForm({{ $itp->items->map(fn($i) => ['id'=>$i->id,'description'=>$i->description,'specification_reference'=>$i->specification_reference,'inspection_type'=>$i->inspection_type,'acceptance_criteria'=>$i->acceptance_criteria,'method'=>$i->method,'frequency'=>$i->frequency,'status'=>$i->status,'result'=>$i->result,'inspected_date'=>$i->inspected_date?->format('Y-m-d')??'','inspector'=>$i->inspector])->toJson() }})">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">Project <span class="text-danger">*</span></label>
                    <select name="project_id" class="form-select" required>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $itp->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-input" required value="{{ old('title', $itp->title) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Phase <span class="text-danger">*</span></label>
                    <select name="phase" class="form-select" required>
                        @foreach(['foundation','superstructure','finishing','mep','other'] as $ph)
                            <option value="{{ $ph }}" {{ old('phase', $itp->phase) == $ph ? 'selected' : '' }}>{{ ucfirst($ph) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        @foreach(['draft','active','completed','archived'] as $st)
                            <option value="{{ $st }}" {{ old('status', $itp->status) == $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Description</label>
                    <textarea name="description" class="form-textarea" rows="2">{{ old('description', $itp->description) }}</textarea>
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
                            <div class="col-span-3">
                                <label class="text-xs font-semibold">Description <span class="text-danger">*</span></label>
                                <input type="text" :name="'items[' + index + '][description]'" class="form-input" x-model="item.description" required />
                                <input type="hidden" :name="'items[' + index + '][id]'" x-model="item.id" />
                            </div>
                            <div class="col-span-2">
                                <label class="text-xs font-semibold">Spec Reference</label>
                                <input type="text" :name="'items[' + index + '][specification_reference]'" class="form-input" x-model="item.specification_reference" />
                            </div>
                            <div class="col-span-1">
                                <label class="text-xs font-semibold">Type</label>
                                <select :name="'items[' + index + '][inspection_type]'" class="form-select" x-model="item.inspection_type" required>
                                    <option value="visual">Visual</option>
                                    <option value="dimensional">Dimensional</option>
                                    <option value="testing">Testing</option>
                                    <option value="documentation">Documentation</option>
                                </select>
                            </div>
                            <div class="col-span-1">
                                <label class="text-xs font-semibold">Method</label>
                                <select :name="'items[' + index + '][method]'" class="form-select" x-model="item.method" required>
                                    <option value="observation">Observation</option>
                                    <option value="measurement">Measurement</option>
                                    <option value="testing">Testing</option>
                                    <option value="review">Review</option>
                                </select>
                            </div>
                            <div class="col-span-1">
                                <label class="text-xs font-semibold">Status</label>
                                <select :name="'items[' + index + '][status]'" class="form-select" x-model="item.status" required>
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="passed">Passed</option>
                                    <option value="failed">Failed</option>
                                    <option value="n_a">N/A</option>
                                </select>
                            </div>
                            <div class="col-span-1">
                                <label class="text-xs font-semibold">Inspected</label>
                                <input type="date" :name="'items[' + index + '][inspected_date]'" class="form-input" x-model="item.inspected_date" />
                            </div>
                            <div class="col-span-2">
                                <label class="text-xs font-semibold">Inspector</label>
                                <input type="text" :name="'items[' + index + '][inspector]'" class="form-input" x-model="item.inspector" />
                            </div>
                            <div class="col-span-1 flex items-end">
                                <button type="button" class="btn btn-sm btn-outline-danger" @click="removeItem(index)">✕</button>
                            </div>
                        </div>
                        <div class="mt-2 grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-xs font-semibold">Acceptance Criteria</label>
                                <input type="text" :name="'items[' + index + '][acceptance_criteria]'" class="form-input" x-model="item.acceptance_criteria" />
                            </div>
                            <div>
                                <label class="text-xs font-semibold">Result</label>
                                <input type="text" :name="'items[' + index + '][result]'" class="form-input" x-model="item.result" />
                            </div>
                        </div>
                    </div>
                </template>

                <div x-show="items.length === 0" class="text-center text-sm text-gray-400 py-4">No items.</div>
            </div>

            <button type="submit" class="btn btn-primary mt-4">Update ITP</button>
        </form>
    </div>
@endsection

@push('scripts')
<script>
function itpForm(existingItems = []) {
    return {
        items: existingItems,
        addItem() {
            this.items.push({ id: null, description: '', specification_reference: '', inspection_type: 'visual', acceptance_criteria: '', method: 'observation', frequency: 'each_occurrence', status: 'pending', result: '', inspected_date: '', inspector: '' });
        },
        removeItem(index) {
            this.items.splice(index, 1);
        }
    };
}
</script>
@endpush
