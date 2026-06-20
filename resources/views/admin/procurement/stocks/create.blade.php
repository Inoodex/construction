@extends('admin.layouts.master')

@section('title', 'Add Stock')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Add Stock</h2>
        <a href="{{ route('admin.procurement.stocks.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.procurement.stocks.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="material_id">Material <span class="text-danger">*</span></label>
                    <select name="material_id" id="material_id" class="form-select" required>
                        <option value="">Select Material</option>
                        @foreach($materials as $material)
                            <option value="{{ $material->id }}" {{ old('material_id') == $material->id ? 'selected' : '' }}>{{ $material->name }} ({{ $material->unit }})</option>
                        @endforeach
                    </select>
                    @error('material_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity <span class="text-danger">*</span></label>
                    <input type="number" step="0.0001" min="0" name="quantity" id="quantity" class="form-input" required
                        value="{{ old('quantity') }}" />
                    @error('quantity') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="min_stock">Min Stock (Reorder Alert)</label>
                    <input type="number" step="0.0001" min="0" name="min_stock" id="min_stock" class="form-input"
                        value="{{ old('min_stock', 0) }}" />
                    <p class="text-xs text-white-dark mt-1">Alert when stock falls below this level</p>
                </div>
                <div class="form-group">
                    <label for="warehouse_id">Warehouse</label>
                    <select name="warehouse_id" id="warehouse_id" class="form-select">
                        <option value="">Select Warehouse</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="site_id">Site</label>
                    <select name="site_id" id="site_id" class="form-select">
                        <option value="">Select Site</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @error('location') <p class="mt-2 text-danger text-sm">{{ $message }}</p> @enderror
            <p class="mt-2 text-xs text-white-dark">Select either a Warehouse or a Site (not both). If a record exists, quantity will be added.</p>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Add Stock</button>
                <button type="reset" class="btn btn-outline-danger">Reset Form</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('warehouse_id').addEventListener('change', function() {
    if (this.value) document.getElementById('site_id').value = '';
});
document.getElementById('site_id').addEventListener('change', function() {
    if (this.value) document.getElementById('warehouse_id').value = '';
});
</script>
@endpush
