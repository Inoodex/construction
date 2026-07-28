@extends('admin.layouts.master')

@section('title', 'New Material Takeoff')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">New Material Takeoff</h2>
        <a href="{{ route('admin.finance.material-takeoffs.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
    </div>

    <div class="panel mt-6 max-w-2xl">
        <form action="{{ route('admin.finance.material-takeoffs.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold">Project <span class="text-danger">*</span></label>
                    <select name="project_id" id="project_id" class="form-select" required>
                        <option value="">Select project</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}" {{ old('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">BOQ Item</label>
                    <select name="boq_item_id" id="boq_item_id" class="form-select">
                        <option value="">None</option>
                        @foreach($boqItems as $item)
                            <option value="{{ $item->id }}" data-project-id="{{ $item->boq?->project_id }}" {{ old('boq_item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->boq?->boq_number ?? 'BOQ' }} - {{ $item->item_number }} ({{ $item->description }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold">Description <span class="text-danger">*</span></label>
                    <input type="text" name="description" class="form-input" required value="{{ old('description') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Unit</label>
                    <input type="text" name="unit" class="form-input" value="{{ old('unit') }}" placeholder="e.g. kg, m3, nos" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Source Drawing</label>
                    <input type="text" name="source_drawing" class="form-input" value="{{ old('source_drawing') }}" placeholder="Drawing ref" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Quantity <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="quantity" class="form-input" required value="{{ old('quantity', 0) }}" min="0" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Unit Price <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="unit_price" class="form-input" required value="{{ old('unit_price', 0) }}" min="0" />
                </div>
            </div>
            <div class="mt-4">
                <label class="text-sm font-semibold">Notes</label>
                <textarea name="notes" class="form-textarea" rows="3">{{ old('notes') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Save Takeoff</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const projectSelect = document.getElementById('project_id');
            const boqSelect = document.getElementById('boq_item_id');

            if (!projectSelect || !boqSelect) return;

            function filterBoqItems() {
                const selectedProjectId = projectSelect.value;
                const options = boqSelect.querySelectorAll('option');

                options.forEach(opt => {
                    const optProjectId = opt.getAttribute('data-project-id');
                    if (!opt.value) {
                        opt.style.display = '';
                    } else if (!selectedProjectId || optProjectId === selectedProjectId) {
                        opt.style.display = '';
                        opt.disabled = false;
                    } else {
                        opt.style.display = 'none';
                        opt.disabled = true;
                        if (opt.selected) {
                            boqSelect.value = '';
                        }
                    }
                });
            }

            projectSelect.addEventListener('change', filterBoqItems);
            filterBoqItems();
        });
    </script>
@endsection
