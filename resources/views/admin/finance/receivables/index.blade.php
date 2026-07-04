@extends('admin.layouts.master')

@section('title', 'Accounts Receivable')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Accounts Receivable</h5>
        <a href="{{ route('admin.finance.receivables.create') }}" class="btn btn-primary">+ New Receivable</a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-100 p-3 text-green-700">{{ session('success') }}</div>
    @endif

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
                            <td class="font-mono text-xs">{{ $r->receivable_number }}</td>
                            <td class="font-semibold">{{ $r->payer_name }}</td>
                            <td class="text-xs">{{ $r->project?->name ?? '—' }}</td>
                            <td class="font-mono text-xs">{{ number_format($r->amount, 2) }}</td>
                            <td class="font-mono text-xs text-success">{{ number_format($r->paid_amount, 2) }}</td>
                            <td class="font-mono text-xs font-semibold {{ $r->due_amount > 0 ? 'text-danger' : 'text-success' }}">{{ number_format($r->due_amount, 2) }}</td>
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
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.finance.receivables.show', $r) }}" class="btn btn-sm btn-outline-info">View</a>
                                    <form action="{{ route('admin.finance.receivables.destroy', $r) }}" method="POST" onsubmit="return confirm('Delete?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
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
