@extends('admin.layouts.master')

@section('title', 'Proposals')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Proposals</h5>
        <a href="{{ route('admin.crm.proposals.create') }}" class="btn btn-primary">+ New Proposal</a>
    </div>

    <form method="GET" class="mb-4 flex items-center gap-3">
        <input type="text" name="search" class="form-input flex-1" placeholder="Search by number or title..." value="{{ request('search') }}" />
        <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
            <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->anyFilled(['search', 'status']))
            <a href="{{ route('admin.crm.proposals.index') }}" class="btn btn-outline-danger">Reset</a>
        @endif
    </form>

    <div class="table-responsive">
        <table class="table-hover table">
            <thead>
                <tr>
                    <th>Proposal #</th>
                    <th>Title</th>
                    <th>Client / Lead</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Valid Until</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($proposals as $p)
                    @php $statusColors = ['draft' => 'badge-outline-secondary', 'sent' => 'badge-outline-info', 'accepted' => 'badge-outline-success', 'rejected' => 'badge-outline-danger', 'expired' => 'badge-outline-dark']; @endphp
                    <tr>
                        <td class="font-mono text-xs font-semibold">{{ $p->proposal_number }}</td>
                        <td class="font-semibold">{{ $p->title }}</td>
                        <td class="text-xs">{{ $p->client?->company_name ?? $p->lead?->company_name ?? '—' }}</td>
                        <td class="font-mono">৳{{ number_format($p->total_amount, 2) }}</td>
                        <td><span class="badge {{ $statusColors[$p->status] ?? 'badge-outline-secondary' }} text-xs capitalize">{{ $p->status }}</span></td>
                        <td class="text-xs">{{ $p->valid_until?->format('d M Y') ?? '—' }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.crm.proposals.show', $p) }}" class="btn btn-sm btn-outline-info">View</a>
                            <a href="{{ route('admin.crm.proposals.edit', $p) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-gray-500">No proposals found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $proposals->links() }}</div>
</div>
@endsection
