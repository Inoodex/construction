@extends('admin.layouts.master')

@section('title', 'Bank Guarantees')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Bank Guarantees</h5>
        <a href="{{ route('admin.finance.bank-guarantees.create') }}" class="btn btn-primary">+ New Guarantee</a>
    </div>

    <form method="GET" class="mb-4 flex items-center gap-2">
        <input type="text" name="search" class="form-input flex-1" placeholder="Search ref, bank, beneficiary..." value="{{ request('search') }}" />
        <select name="type" class="form-select flex-1">
            <option value="">All Types</option>
            <option value="bid" {{ request('type') == 'bid' ? 'selected' : '' }}>Bid Bond</option>
            <option value="performance" {{ request('type') == 'performance' ? 'selected' : '' }}>Performance</option>
            <option value="advance" {{ request('type') == 'advance' ? 'selected' : '' }}>Advance Payment</option>
            <option value="retention" {{ request('type') == 'retention' ? 'selected' : '' }}>Retention</option>
        </select>
        <select name="status" class="form-select flex-1">
            <option value="">All Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
            <option value="encashed" {{ request('status') == 'encashed' ? 'selected' : '' }}>Encashed</option>
            <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->anyFilled(['search', 'type', 'status']))
            <a href="{{ route('admin.finance.bank-guarantees.index') }}" class="btn btn-outline-danger">Reset</a>
        @endif
    </form>

    <div class="overflow-x-auto">
        <table class="table-hover w-full table-auto">
            <thead>
                <tr>
                    <th>Ref #</th>
                    <th>Type</th>
                    <th>Bank</th>
                    <th>Beneficiary</th>
                    <th>Amount</th>
                    <th>Issue</th>
                    <th>Expiry</th>
                    <th>Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($guarantees as $g)
                    <tr>
                        <td class="font-mono text-xs">{{ $g->reference_number }}</td>
                        <td><span class="badge badge-outline-info capitalize">{{ $g->type }}</span></td>
                        <td class="text-xs">{{ $g->issuing_bank }}</td>
                        <td>{{ $g->beneficiary }}</td>
                        <td class="font-mono">৳ {{ number_format($g->amount, 2) }}</td>
                        <td class="text-xs">{{ $g->issue_date->format('d/m/y') }}</td>
                        <td class="text-xs">{{ $g->expiry_date->format('d/m/y') }}</td>
                        <td>
                            @php
                                $cls = match($g->status) {
                                    'active' => 'badge-outline-success',
                                    'expired' => 'badge-outline-secondary',
                                    'encashed' => 'badge-outline-danger',
                                    'returned' => 'badge-outline-primary',
                                    default => 'badge-outline-warning'
                                };
                            @endphp
                            <span class="badge {{ $cls }} capitalize">{{ $g->status }}</span>
                        </td>
                        <td class="text-center">
                            <x-action-dropdown>
                                <x-slot name="items">
                                    <li><a href="{{ route('admin.finance.bank-guarantees.show', $g) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                    <li><a href="{{ route('admin.finance.bank-guarantees.pdf', $g->id) }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> PDF</a></li>
                                    <li><a href="{{ route('admin.finance.bank-guarantees.edit', $g) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/></svg> Edit</a></li>
                                    <li class="divider"></li>
                                    <li class="danger">
                                        <form action="{{ route('admin.finance.bank-guarantees.destroy', $g) }}" method="POST" onsubmit="return confirm('Delete this bank guarantee?');">
                                            @csrf @method('DELETE')
                                            <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 001-1h4a1 1 0 001 1m-6 0h6"/></svg> Delete</button>
                                        </form>
                                    </li>
                                </x-slot>
                            </x-action-dropdown>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-gray-500">No guarantees found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $guarantees->links() }}</div>
</div>
@endsection
