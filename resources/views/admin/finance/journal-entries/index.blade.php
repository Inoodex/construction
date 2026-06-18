@extends('admin.layouts.master')

@section('title', 'Journal Entries')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Journal Entries</h5>
        <a href="{{ route('admin.finance.journal-entries.create') }}" class="btn btn-primary">+ New Journal Voucher</a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-100 p-3 text-green-700">{{ session('success') }}</div>
    @endif

    <form method="GET" class="mb-4 flex items-center gap-3">
        <input type="text" name="search" class="form-input flex-1" placeholder="Search by number or description..." value="{{ request('search') }}" />
        <select name="type" class="form-select flex-1">
            <option value="">All Types</option>
            <option value="general" {{ request('type') == 'general' ? 'selected' : '' }}>General</option>
            <option value="payment" {{ request('type') == 'payment' ? 'selected' : '' }}>Payment</option>
            <option value="receipt" {{ request('type') == 'receipt' ? 'selected' : '' }}>Receipt</option>
            <option value="contra" {{ request('type') == 'contra' ? 'selected' : '' }}>Contra</option>
            <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
        </select>
        <select name="status" class="form-select flex-1">
            <option value="">All Status</option>
            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="posted" {{ request('status') == 'posted' ? 'selected' : '' }}>Posted</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->anyFilled(['search', 'type', 'status']))
            <a href="{{ route('admin.finance.journal-entries.index') }}" class="btn btn-outline-danger">Reset</a>
        @endif
    </form>

    <div class="datatable">
        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Journal #</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $entry)
                        <tr>
                            <td class="font-mono text-xs">{{ $entry->journal_number }}</td>
                            <td>{{ $entry->date->format('d M Y') }}</td>
                            <td class="max-w-xs truncate">{{ $entry->description ?? '—' }}</td>
                            <td><span class="badge badge-outline-primary capitalize">{{ $entry->type }}</span></td>
                            <td class="text-center">{{ $entry->items->count() }}</td>
                            <td class="font-mono text-xs">{{ number_format($entry->items->sum('debit_amount'), 2) }}</td>
                            <td>
                                @if($entry->status == 'posted')
                                    <span class="badge badge-outline-success">Posted</span>
                                @else
                                    <span class="badge badge-outline-warning">Draft</span>
                                @endif
                            </td>
                            <td class="text-xs">{{ $entry->creator?->name ?? '—' }}</td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.finance.journal-entries.show', $entry) }}" class="btn btn-sm btn-outline-info">View</a>
                                    <form action="{{ route('admin.finance.journal-entries.destroy', $entry) }}" method="POST" onsubmit="return confirm('Delete this journal entry?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-gray-500">No journal entries found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $entries->links() }}</div>
</div>
@endsection
