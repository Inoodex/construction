@extends('admin.layouts.master')

@section('title', 'Stock Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Stock Details</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.procurement.stocks.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to List
            </a>
            <a href="{{ route('admin.procurement.stocks.edit', $stock->id) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Adjust
            </a>
        </div>
    </div>

    <div class="panel mt-6 max-w-2xl">
        <h5 class="mb-4 text-base font-semibold">Stock Information</h5>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="text-xs text-white-dark">Material</label>
                <p class="font-semibold">{{ $stock->material->name ?? 'Unknown' }}</p>
            </div>
            <div>
                <label class="text-xs text-white-dark">Unit</label>
                <p class="font-semibold">{{ $stock->material->unit ?? '—' }}</p>
            </div>
            <div>
                <label class="text-xs text-white-dark">Quantity</label>
                <p class="text-lg font-bold {{ $stock->quantity <= 0 ? 'text-danger' : 'text-success' }}">{{ number_format($stock->quantity, 4) }}</p>
            </div>
            <div>
                <label class="text-xs text-white-dark">Location</label>
                <p class="font-semibold">
                    @if($stock->warehouse)
                        Warehouse: {{ $stock->warehouse->name }}
                    @elseif($stock->site)
                        Site: {{ $stock->site->name }}
                    @else
                        —
                    @endif
                </p>
            </div>
        </div>
    </div>
@endsection
