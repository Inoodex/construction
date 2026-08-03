@extends('admin.layouts.master')

@section('title', 'Accounts Receivable')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Accounts Receivable</h5>
        <a href="{{ route('admin.finance.receivables.create') }}" class="btn btn-primary">+ New Receivable</a>
    </div>

    <form method="GET" class="mb-4 flex items-center gap-3">
        <input type="text" name="search" class="form-input flex-1" placeholder="Search by payer or number..." value="{{ request('search') }}" />
        <select name="status" class="form-select flex-1">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->anyFilled(['search', 'status']))
            <a href="{{ route('admin.finance.receivables.index') }}" class="btn btn-outline-danger">Reset</a>
        @endif
    </form>

    <div class="datatable">
        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Payer</th>
                        <th>Project</th>
                        <th>Amount</th>
                        <th>Paid</th>
                        <th>Due</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($receivables as $r)
                        <tr>
                            <td class="font-mono">{{ $r->receivable_number }}</td>
                            <td class="font-semibold">{{ $r->payer_name }}</td>
                            <td>{{ $r->project?->name ?? '—' }}</td>
                            <td>{{ number_format($r->amount, 2) }}</td>
                            <td class="font-semibold text-success">{{ number_format($r->paid_amount, 2) }}</td>
                            <td class="font-semibold {{ $r->due_amount > 0 ? 'text-danger' : 'text-success' }}">{{ number_format($r->due_amount, 2) }}</td>
                            <td>{{ $r->due_date->format('d M Y') }}</td>
                            <td>
                                @php
                                    $cls = match($r->status) {
                                        'paid' => 'badge-outline-success',
                                        'partial' => 'badge-outline-warning',
                                        'overdue' => 'badge-outline-danger',
                                        default => 'badge-outline-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $cls }} capitalize">{{ $r->status }}</span>
                            </td>
                            <td class="text-center">
                                <x-action-dropdown>
                                    <x-slot name="items">
                                        <li><a href="{{ route('admin.finance.receivables.show', $r) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                        <li><a href="{{ route('admin.finance.receivables.pdf', $r) }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> PDF</a></li>
                                        <li class="divider"></li>
                                        <li class="danger">
                                            <form action="{{ route('admin.finance.receivables.destroy', $r) }}" method="POST" onsubmit="return confirm('Delete?');">
                                                @csrf @method('DELETE')
                                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 001-1h4a1 1 0 001 1m-6 0h6"/></svg> Delete</button>
                                            </form>
                                        </li>
                                    </x-slot>
                                </x-action-dropdown>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-gray-500">No receivables found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $receivables->links() }}</div>
</div>
@endsection
