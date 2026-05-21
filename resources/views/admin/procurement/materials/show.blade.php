@extends('admin.layouts.master')

@section('title', 'Material Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Material Details</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.procurement.materials.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to List
            </a>
            <a href="{{ route('admin.procurement.materials.edit', $material->id) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Edit
            </a>
        </div>
    </div>

    <div class="panel mt-6 max-w-2xl">
        <h5 class="mb-4 text-base font-semibold">General Information</h5>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="text-xs text-white-dark">Material Name</label>
                <p class="font-semibold">{{ $material->name }}</p>
            </div>
            <div>
                <label class="text-xs text-white-dark">SKU</label>
                <p class="font-semibold font-mono">{{ $material->sku ?: '—' }}</p>
            </div>
            <div>
                <label class="text-xs text-white-dark">Unit</label>
                <p><span class="badge badge-outline-info">{{ $material->unit }}</span></p>
            </div>
            <div>
                <label class="text-xs text-white-dark">Created</label>
                <p class="text-xs font-semibold">{{ $material->created_at->format('d M Y') }}</p>
            </div>
        </div>
        <div class="mt-4">
            <label class="text-xs text-white-dark">Description</label>
            <p class="font-semibold">{{ $material->description ?: '—' }}</p>
        </div>
    </div>
@endsection
