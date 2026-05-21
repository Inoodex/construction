@extends('admin.layouts.master')

@section('title', 'Goods Received Notes')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Goods Received Notes</h2>
        <div class="flex w-full flex-wrap items-center justify-end gap-4 sm:w-auto">
            <a href="{{ route('admin.procurement.goods-received-notes.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                New GRN
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-5 flex flex-col gap-5 md:flex-row md:items-center">
            <form action="{{ route('admin.procurement.goods-received-notes.index') }}" method="GET" class="flex flex-1 flex-col gap-5 md:flex-row md:items-center w-full">
                <div class="flex gap-2">
                    <select name="status" class="form-select w-full md:w-36">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
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
                            <th>GRN Number</th>
                            <th>PO Reference</th>
                            <th>Received Date</th>
                            <th>Received By</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notes as $note)
                            <tr>
                                <td><span class="font-mono text-xs font-semibold text-primary">{{ $note->grn_number }}</span></td>
                                <td class="text-xs">{{ $note->purchaseOrder->po_number ?? 'N/A' }}</td>
                                <td class="text-xs">{{ $note->received_date->format('d M Y') }}</td>
                                <td class="text-xs">{{ $note->receiver->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $note->status == 'verified' ? 'badge-outline-success' : 'badge-outline-warning' }} capitalize">{{ $note->status }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.procurement.goods-received-notes.show', $note->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <form action="{{ route('admin.procurement.goods-received-notes.destroy', $note->id) }}" method="POST" onsubmit="return confirm('Delete this GRN?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No GRNs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $notes->links() }}
            </div>
        </div>
    </div>
@endsection
