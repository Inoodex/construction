@extends('admin.layouts.master')

@section('title', 'Proposals')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Proposals</h5>
        <a href="{{ route('admin.crm.proposals.create') }}" class="btn btn-primary">+ New Proposal</a>
    </div>

    <form method="GET" class="mb-4 flex items-center gap-3">
        <input type="text" name="search" class="form-input w-auto" placeholder="Search by number or title..." value="{{ request('search') }}" />
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
                    <td class="text-center whitespace-nowrap">
                        <x-action-dropdown>
                            <x-slot name="items">
                                <li><a href="{{ route('admin.crm.proposals.show', $p) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                <li><a href="{{ route('admin.crm.proposals.pdf', $p) }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> PDF</a></li>
                                <li><a href="{{ route('admin.crm.proposals.edit', $p) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/></svg> Edit</a></li>
                            </x-slot>
                        </x-action-dropdown>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-gray-500">No proposals found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $proposals->links() }}</div>
</div>
@endsection