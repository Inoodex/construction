@extends('admin.layouts.master')

@section('title', 'Purchase Order Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Purchase Order Details</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.procurement.purchase-orders.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to List
            </a>
            <a href="{{ route('admin.procurement.purchase-orders.edit', $purchaseOrder->id) }}" class="btn btn-primary gap-2">
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
            <h5 class="mb-4 text-base font-semibold">PO Information</h5>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="text-xs text-white-dark">PO Number</label>
                    <p class="font-mono font-semibold text-primary">{{ $purchaseOrder->po_number }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Status</label>
                    <div>
                        @php $sc = ['draft' => 'badge-outline-secondary', 'ordered' => 'badge-outline-info', 'partially_received' => 'badge-outline-warning', 'received' => 'badge-outline-success', 'cancelled' => 'badge-outline-danger']; @endphp
                        <span class="badge {{ $sc[$purchaseOrder->status] ?? 'badge-outline-secondary' }} capitalize">{{ str_replace('_', ' ', $purchaseOrder->status) }}</span>
                    </div>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Vendor</label>
                    <p class="font-semibold">{{ $purchaseOrder->vendor->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Requisition</label>
                    <p class="font-semibold">{{ $purchaseOrder->requisition->requisition_number ?? 'Direct Order' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Order Date</label>
                    <p class="font-semibold">{{ $purchaseOrder->order_date->format('d M Y') }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Total Amount</label>
                    <p class="text-lg font-bold text-success">৳{{ number_format($purchaseOrder->total_amount) }}</p>
                </div>
            </div>
        </div>

        <div class="panel">
            <h5 class="mb-4 text-base font-semibold">Summary</h5>
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Total Items</span>
                    <span class="text-sm font-bold dark:text-white">{{ $purchaseOrder->items->count() }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Created</span>
                    <span class="text-xs font-semibold dark:text-white">{{ $purchaseOrder->created_at->format('d M Y h:i A') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="panel mt-6">
        <h5 class="mb-4 text-base font-semibold">Items</h5>
        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Material</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchaseOrder->items as $idx => $item)
                        <tr>
                            <td class="text-xs">{{ $idx + 1 }}</td>
                            <td class="font-semibold">{{ $item->material->name ?? 'Unknown' }}</td>
                            <td>{{ number_format($item->quantity, 2) }} {{ $item->material->unit ?? '' }}</td>
                            <td>৳{{ number_format($item->unit_price, 2) }}</td>
                            <td class="font-semibold">৳{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
