@extends('admin.layouts.master')

@section('title', 'Purchase Orders')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Purchase Orders</h2>
        <div class="flex w-full flex-wrap items-center justify-end gap-4 sm:w-auto">
            <a href="{{ route('admin.procurement.purchase-orders.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                New PO
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.procurement.purchase-orders.index') }}" method="GET" class="flex items-center gap-3 w-full">
                <select name="vendor_id" class="form-select flex-1">
                    <option value="">Vendor</option>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select flex-1">
                    <option value="">Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="ordered" {{ request('status') == 'ordered' ? 'selected' : '' }}>Ordered</option>
                    <option value="partially_received" {{ request('status') == 'partially_received' ? 'selected' : '' }}>Partially Received</option>
                    <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Received</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['vendor_id', 'status']))
                    <a href="{{ route('admin.procurement.purchase-orders.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>PO Number</th>
                            <th>Vendor</th>
                            <th>Amount</th>
                            <th>Order Date</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $po)
                            <tr>
                                <td><span class="font-mono text-xs font-semibold text-primary">{{ $po->po_number }}</span></td>
                                <td class="text-xs">{{ $po->vendor->name ?? 'N/A' }}</td>
                                <td class="font-semibold">৳{{ number_format($po->total_amount) }}</td>
                                <td class="text-xs">{{ $po->order_date->format('d M Y') }}</td>
                                <td>
                                    @php
                                        $sc = ['draft' => 'badge-outline-secondary', 'ordered' => 'badge-outline-info', 'partially_received' => 'badge-outline-warning', 'received' => 'badge-outline-success', 'cancelled' => 'badge-outline-danger'];
                                    @endphp
                                    <span class="badge {{ $sc[$po->status] ?? 'badge-outline-secondary' }} capitalize">{{ str_replace('_', ' ', $po->status) }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.procurement.purchase-orders.show', $po->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.procurement.purchase-orders.edit', $po->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.procurement.purchase-orders.destroy', $po->id) }}" method="POST" onsubmit="return confirm('Delete this PO?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No purchase orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection
