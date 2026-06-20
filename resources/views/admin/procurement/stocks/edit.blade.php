@extends('admin.layouts.master')

@section('title', 'Adjust Stock')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Adjust Stock</h2>
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
        <form action="{{ route('admin.procurement.stocks.update', $stock->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label>Material</label>
                    <p class="font-semibold py-2">{{ $stock->material->name ?? 'Unknown' }}</p>
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <p class="font-semibold py-2">
                        @if($stock->warehouse)
                            Warehouse: {{ $stock->warehouse->name }}
                        @elseif($stock->site)
                            Site: {{ $stock->site->name }}
                        @else
                            —
                        @endif
                    </p>
                </div>
                <div class="form-group">
                    <label for="quantity">Current Quantity</label>
                    <p class="font-semibold py-2">{{ number_format($stock->quantity, 4) }} {{ $stock->material->unit ?? '' }}</p>
                </div>
                <div class="form-group">
                    <label for="quantity">New Quantity <span class="text-danger">*</span></label>
                    <input type="number" step="0.0001" min="0" name="quantity" id="quantity" class="form-input" required
                        value="{{ old('quantity', $stock->quantity) }}" />
                    @error('quantity') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="min_stock">Min Stock (Reorder Alert)</label>
                    <input type="number" step="0.0001" min="0" name="min_stock" id="min_stock" class="form-input"
                        value="{{ old('min_stock', $stock->min_stock) }}" />
                    <p class="text-xs text-white-dark mt-1">Alert when stock falls below this level</p>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update Quantity</button>
                <button type="button" onclick="window.location.href='{{ route('admin.procurement.stocks.index') }}'"
                    class="btn btn-outline-danger">Cancel</button>
            </div>
        </form>
    </div>
@endsection
