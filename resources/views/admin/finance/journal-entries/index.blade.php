@extends('admin.layouts.master')

@section('title', 'Journal Entries')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Journal Entries</h5>
        <a href="{{ route('admin.finance.journal-entries.create') }}" class="btn btn-primary">+ New Journal Voucher</a>
    </div>

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
                            <td class="font-mono text-xs text-success">{{ number_format($entry->items->sum('debit_amount'), 2) }}</td>
                            <td>
                                @if($entry->status == 'posted')
                                    <span class="badge badge-outline-success">Posted</span>
                                @else
                                    <span class="badge badge-outline-warning">Draft</span>
                                @endif
                            </td>
                            <td class="text-xs">{{ $entry->creator?->name ?? '—' }}</td>
                            <td class="text-center">
                                <x-action-dropdown>
                                    <x-slot name="items">
                                        <li><a href="{{ route('admin.finance.journal-entries.show', $entry) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                        <li class="divider"></li>
                                        <li class="danger">
                                            <form action="{{ route('admin.finance.journal-entries.destroy', $entry) }}" method="POST" onsubmit="return confirm('Delete this journal entry?');">
                                                @csrf @method('DELETE')
                                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 001-1h4a1 1 0 001 1m-6 0h6"/></svg> Delete</button>
                                            </form>
                                        </li>
                                    </x-slot>
                                </x-action-dropdown>
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
