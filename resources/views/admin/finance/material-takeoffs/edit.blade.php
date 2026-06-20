@extends('admin.layouts.master')

@section('title', 'Edit Material Takeoff')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Material Takeoff</h2>
        <a href="{{ route('admin.finance.material-takeoffs.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
    </div>

    <div class="panel mt-6 max-w-2xl">
        <form action="{{ route('admin.finance.material-takeoffs.update', $materialTakeoff) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">Project <span class="text-danger">*</span></label>
                    <select name="project_id" class="form-select" required>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}" {{ old('project_id', $materialTakeoff->project_id) == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">BOQ Item</label>
                    <select name="boq_item_id" class="form-select">
                        <option value="">None</option>
                        @foreach($boqItems as $item)
                            <option value="{{ $item->id }}" {{ old('boq_item_id', $materialTakeoff->boq_item_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->boq?->boq_number ?? 'BOQ' }} - {{ $item->item_number }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Description <span class="text-danger">*</span></label>
                    <input type="text" name="description" class="form-input" required value="{{ old('description', $materialTakeoff->description) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Unit</label>
                    <input type="text" name="unit" class="form-input" value="{{ old('unit', $materialTakeoff->unit) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Source Drawing</label>
                    <input type="text" name="source_drawing" class="form-input" value="{{ old('source_drawing', $materialTakeoff->source_drawing) }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Quantity <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="quantity" class="form-input" required value="{{ old('quantity', $materialTakeoff->quantity) }}" min="0" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Unit Price <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="unit_price" class="form-input" required value="{{ old('unit_price', $materialTakeoff->unit_price) }}" min="0" />
                </div>
            </div>
            <div class="mt-4">
                <label class="text-sm font-semibold">Notes</label>
                <textarea name="notes" class="form-textarea" rows="3">{{ old('notes', $materialTakeoff->notes) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Update Takeoff</button>
        </form>
    </div>
@endsection
