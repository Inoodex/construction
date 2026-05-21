@extends('admin.layouts.master')

@section('title', 'Transfer Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Transfer Details</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.procurement.material-transfers.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to List
            </a>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="panel lg:col-span-2">
            <h5 class="mb-4 text-base font-semibold">Transfer Information</h5>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="text-xs text-white-dark">Transfer Number</label>
                    <p class="font-mono font-semibold text-primary">{{ $materialTransfer->transfer_number }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Status</label>
                    <div>
                        @php $sc = ['pending' => 'badge-outline-warning', 'transit' => 'badge-outline-info', 'completed' => 'badge-outline-success', 'cancelled' => 'badge-outline-danger']; @endphp
                        <span class="badge {{ $sc[$materialTransfer->status] ?? 'badge-outline-secondary' }} capitalize">{{ $materialTransfer->status }}</span>
                    </div>
                </div>
                <div>
                    <label class="text-xs text-white-dark">From Warehouse</label>
                    <p class="font-semibold">{{ $materialTransfer->fromWarehouse->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">To Site</label>
                    <p class="font-semibold">{{ $materialTransfer->toSite->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Transfer Date</label>
                    <p class="font-semibold">{{ $materialTransfer->transfer_date->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <div class="panel">
            <h5 class="mb-4 text-base font-semibold">Summary</h5>
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Total Items</span>
                    <span class="text-sm font-bold dark:text-white">{{ $materialTransfer->items->count() }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Created</span>
                    <span class="text-xs font-semibold dark:text-white">{{ $materialTransfer->created_at->format('d M Y h:i A') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="panel mt-6">
        <h5 class="mb-4 text-base font-semibold">Transferred Items</h5>
        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Material</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materialTransfer->items as $idx => $item)
                        <tr>
                            <td class="text-xs">{{ $idx + 1 }}</td>
                            <td class="font-semibold">{{ $item->material->name ?? 'Unknown' }}</td>
                            <td>{{ number_format($item->quantity, 2) }} {{ $item->material->unit ?? '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
