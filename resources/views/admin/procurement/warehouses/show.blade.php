@extends('admin.layouts.master')

@section('title', 'Warehouse Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Warehouse Details</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.procurement.warehouses.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to List
            </a>
            <a href="{{ route('admin.procurement.warehouses.edit', $warehouse->id) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Edit
            </a>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="panel lg:col-span-2">
            <h5 class="mb-4 text-base font-semibold">General Information</h5>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="text-xs text-white-dark">Warehouse Name</label>
                    <p class="font-semibold">{{ $warehouse->name }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Status</label>
                    <div>
                        <span class="badge {{ $warehouse->status == 'active' ? 'badge-outline-success' : 'badge-outline-secondary' }} capitalize">{{ $warehouse->status }}</span>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <label class="text-xs text-white-dark">Location</label>
                <p class="font-semibold">{{ $warehouse->location_address ?: '—' }}</p>
            </div>
        </div>

        <div class="panel">
            <h5 class="mb-4 text-base font-semibold">Stock Summary</h5>
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Total Items</span>
                    <span class="text-sm font-bold dark:text-white">{{ $warehouse->stocks->count() }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Total Quantity</span>
                    <span class="text-sm font-bold dark:text-white">{{ number_format($warehouse->stocks->sum('quantity'), 2) }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Created</span>
                    <span class="text-xs font-semibold dark:text-white">{{ $warehouse->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    @if($warehouse->stocks->count() > 0)
    <div class="panel mt-6">
        <h5 class="mb-4 text-base font-semibold">Stock Levels</h5>
        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Material</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($warehouse->stocks as $stock)
                        <tr>
                            <td class="font-semibold">{{ $stock->material->name ?? 'Unknown' }}</td>
                            <td>{{ number_format($stock->quantity, 2) }} {{ $stock->material->unit ?? '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
@endsection
