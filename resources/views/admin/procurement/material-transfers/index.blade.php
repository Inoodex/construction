@extends('admin.layouts.master')

@section('title', 'Material Transfers')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Material Transfers</h2>
        <div class="flex w-full flex-wrap items-center justify-end gap-4 sm:w-auto">
            <a href="{{ route('admin.procurement.material-transfers.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                New Transfer
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-5 flex flex-col gap-5 md:flex-row md:items-center">
            <form action="{{ route('admin.procurement.material-transfers.index') }}" method="GET" class="flex flex-1 flex-col gap-5 md:flex-row md:items-center w-full">
                <div class="flex gap-2">
                    <select name="status" class="form-select w-full md:w-36">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="transit" {{ request('status') == 'transit' ? 'selected' : '' }}>In Transit</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Transfer #</th>
                            <th>From Warehouse</th>
                            <th>To Site</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfers as $t)
                            <tr>
                                <td><span class="font-mono text-xs font-semibold text-primary">{{ $t->transfer_number }}</span></td>
                                <td class="text-xs">{{ $t->fromWarehouse->name ?? 'N/A' }}</td>
                                <td class="text-xs">{{ $t->toSite->name ?? 'N/A' }}</td>
                                <td class="text-xs">{{ $t->transfer_date->format('d M Y') }}</td>
                                <td>
                                    @php
                                        $sc = ['pending' => 'badge-outline-warning', 'transit' => 'badge-outline-info', 'completed' => 'badge-outline-success', 'cancelled' => 'badge-outline-danger'];
                                    @endphp
                                    <span class="badge {{ $sc[$t->status] ?? 'badge-outline-secondary' }} capitalize">{{ $t->status }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.procurement.material-transfers.show', $t->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <form action="{{ route('admin.procurement.material-transfers.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Delete this transfer?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No transfers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $transfers->links() }}
            </div>
        </div>
    </div>
@endsection
