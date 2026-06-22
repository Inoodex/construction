@extends('admin.layouts.master')

@section('title', 'Goods Received Note Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Goods Received Note Details</h2>
        <div class="flex gap-2">
            @if($goodsReceivedNote->status === 'pending')
                <form action="{{ route('admin.procurement.goods-received-notes.verify', $goodsReceivedNote) }}" method="POST" onsubmit="return confirm('Verify this GRN and update stock?');">
                    @csrf
                    <button type="submit" class="btn btn-success gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        Verify
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.procurement.goods-received-notes.index') }}" class="btn btn-secondary gap-2">
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
            <h5 class="mb-4 text-base font-semibold">GRN Information</h5>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="text-xs text-white-dark">GRN Number</label>
                    <p class="font-mono font-semibold text-primary">{{ $goodsReceivedNote->grn_number }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Status</label>
                    <div>
                        <span class="badge {{ $goodsReceivedNote->status == 'verified' ? 'badge-outline-success' : 'badge-outline-warning' }} capitalize">{{ $goodsReceivedNote->status }}</span>
                    </div>
                </div>
                <div>
                    <label class="text-xs text-white-dark">PO Reference</label>
                    <p class="font-semibold">{{ $goodsReceivedNote->purchaseOrder->po_number ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Vendor</label>
                    <p class="font-semibold">{{ $goodsReceivedNote->purchaseOrder->vendor->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Received Date</label>
                    <p class="font-semibold">{{ $goodsReceivedNote->received_date->format('d M Y') }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Received By</label>
                    <p class="font-semibold">{{ $goodsReceivedNote->receiver->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Delivery Site</label>
                    <p class="font-semibold">{{ $goodsReceivedNote->site->name ?? '—' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Vehicle Number</label>
                    <p class="font-semibold">{{ $goodsReceivedNote->vehicle_number ?: '—' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Delivery Note</label>
                    <p class="font-semibold">{{ $goodsReceivedNote->delivery_note ?: '—' }}</p>
                </div>
            </div>
        </div>

        <div class="panel">
            <h5 class="mb-4 text-base font-semibold">Summary</h5>
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Total Items</span>
                    <span class="text-sm font-bold dark:text-white">{{ $goodsReceivedNote->items->count() }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Total Accepted</span>
                    <span class="text-sm font-bold dark:text-white">{{ number_format($goodsReceivedNote->items->sum('quantity_accepted'), 2) }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Total Rejected</span>
                    <span class="text-sm font-bold text-danger">{{ number_format($goodsReceivedNote->items->sum('quantity_rejected'), 2) }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Created</span>
                    <span class="text-xs font-semibold dark:text-white">{{ $goodsReceivedNote->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="panel mt-6">
        <h5 class="mb-4 text-base font-semibold">Received Items</h5>
        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Material</th>
                        <th>Qty Received</th>
                        <th>Qty Accepted</th>
                        <th>Qty Rejected</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($goodsReceivedNote->items as $idx => $item)
                        <tr>
                            <td class="text-xs">{{ $idx + 1 }}</td>
                            <td class="font-semibold">{{ $item->material->name ?? 'Unknown' }}</td>
                            <td>{{ number_format($item->quantity_received, 2) }}</td>
                            <td class="text-success font-semibold">{{ number_format($item->quantity_accepted, 2) }}</td>
                            <td class="text-danger font-semibold">{{ number_format($item->quantity_rejected, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
