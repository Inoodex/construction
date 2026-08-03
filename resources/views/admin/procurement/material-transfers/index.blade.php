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
        <div class="mb-5">
            <form action="{{ route('admin.procurement.material-transfers.index') }}" method="GET" class="flex items-center gap-3 w-full">
                <select name="transfer_type" class="form-select flex-1">
                    <option value="">All Types</option>
                    <option value="warehouse_to_site" {{ request('transfer_type') == 'warehouse_to_site' ? 'selected' : '' }}>Warehouse → Site</option>
                    <option value="site_to_warehouse" {{ request('transfer_type') == 'site_to_warehouse' ? 'selected' : '' }}>Site → Warehouse</option>
                    <option value="site_to_site" {{ request('transfer_type') == 'site_to_site' ? 'selected' : '' }}>Site → Site</option>
                    <option value="warehouse_to_warehouse" {{ request('transfer_type') == 'warehouse_to_warehouse' ? 'selected' : '' }}>Warehouse → Warehouse</option>
                </select>
                <select name="status" class="form-select flex-1">
                    <option value="">Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="transit" {{ request('status') == 'transit' ? 'selected' : '' }}>In Transit</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['status', 'transfer_type']))
                    <a href="{{ route('admin.procurement.material-transfers.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Transfer #</th>
                            <th>Type</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfers as $t)
                            <tr>
                                <td><span class="font-mono text-xs font-semibold text-primary">{{ $t->transfer_number }}</span></td>
                                <td><span class="badge badge-outline-info text-xs">{{ $t->transfer_type_label }}</span></td>
                                <td>{{ $t->from_location_label }}</td>
                                <td class="text-xs">{{ $t->to_location_label }}</td>
                                <td class="text-xs">{{ $t->transfer_date->format('d M Y') }}</td>
                                <td>
                                    @php
                                        $sc = ['pending' => 'badge-outline-warning', 'transit' => 'badge-outline-info', 'completed' => 'badge-outline-success', 'cancelled' => 'badge-outline-danger'];
                                    @endphp
                                    <span class="badge {{ $sc[$t->status] ?? 'badge-outline-secondary' }} capitalize">{{ $t->status }}</span>
                                </td>
                                <td class="text-center">
                                    <x-action-dropdown>
                                        <x-slot name="items">
                                            <li><a href="{{ route('admin.procurement.material-transfers.show', $t->id) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                            <li><a href="{{ route('admin.procurement.material-transfers.pdf', $t->id) }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> PDF</a></li>
                                            <li class="divider"></li>
                                            <li class="danger">
                                                <form action="{{ route('admin.procurement.material-transfers.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Delete this transfer?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 001-1h4a1 1 0 001 1m-6 0h6"/></svg> Delete</button>
                                                </form>
                                            </li>
                                        </x-slot>
                                    </x-action-dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No transfers found.</td>
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
