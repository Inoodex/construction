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
                            <a href="{{ route('admin.finance.bank-guarantees.show', $g) }}" class="btn btn-sm btn-outline-info">View</a>
                            <a href="{{ route('admin.finance.bank-guarantees.pdf', $g->id) }}" target="_blank" class="btn btn-sm btn-outline-success" style="margin-left:4px;">PDF</a>
                            <form action="{{ route('admin.finance.bank-guarantees.destroy', $g) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this bank guarantee?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" style="margin-left:4px;">Delete</button>
                            </form>
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
