@extends('admin.layouts.master')

@section('title', 'Contract Claims')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Contract Claims</h2>
        <a href="{{ route('admin.core.contract-claims.create') }}" class="btn btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            New Claim
        </a>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.core.contract-claims.index') }}" method="GET" class="flex items-center gap-3 w-full">
                <div class="relative" style="width: 250px;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search claims..." class="form-input ltr:pr-11 rtl:pl-11 w-full" />
                    <button type="submit" class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5" /><path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" /></svg>
                    </button>
                </div>
                <select name="contract_id" class="form-select" style="width: 150px;">
                    <option value="">All Contracts</option>
                    @foreach($contracts as $contract)
                        <option value="{{ $contract->id }}" {{ request('contract_id') == $contract->id ? 'selected' : '' }}>{{ $contract->contract_number }} - {{ $contract->title }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select" style="width: 150px;">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                    <option value="granted" {{ request('status') == 'granted' ? 'selected' : '' }}>Granted</option>
                    <option value="partially_granted" {{ request('status') == 'partially_granted' ? 'selected' : '' }}>Partially Granted</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                <select name="type" class="form-select" style="width: 150px;">
                    <option value="">All Types</option>
                    <option value="time_extension" {{ request('type') == 'time_extension' ? 'selected' : '' }}>Time Extension</option>
                    <option value="cost_compensation" {{ request('type') == 'cost_compensation' ? 'selected' : '' }}>Cost Compensation</option>
                    <option value="both" {{ request('type') == 'both' ? 'selected' : '' }}>Time & Cost</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['search', 'contract_id', 'status', 'type']))
                    <a href="{{ route('admin.core.contract-claims.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Claim #</th>
                            <th>Title</th>
                            <th>Contract</th>
                            <th>Type</th>
                            <th>Claimed</th>
                            <th>Granted</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($claims as $claim)
                            <tr>
                                <td class="font-mono text-xs font-semibold text-primary">{{ $claim->claim_number }}</td>
                                <td class="font-semibold">{{ $claim->title }}</td>
                                <td class="text-xs">{{ $claim->contract->contract_number ?? '—' }}</td>
                                <td class="text-xs capitalize">{{ str_replace('_', ' ', $claim->type) }}</td>
                                <td class="text-xs">
                                    {{ $claim->claimed_amount ? number_format($claim->claimed_amount, 2) : '—' }}
                                    @if($claim->claimed_days) / {{ $claim->claimed_days }}d @endif
                                </td>
                                <td class="text-xs font-semibold {{ ($claim->granted_amount ?? 0) > 0 ? 'text-success' : '' }}">
                                    {{ $claim->granted_amount ? number_format($claim->granted_amount, 2) : '—' }}
                                    @if($claim->granted_days) / {{ $claim->granted_days }}d @endif
                                </td>
                                <td>
                                    @php $sc = match($claim->status) { 'granted' => 'badge-outline-success', 'partially_granted' => 'badge-outline-info', 'submitted' => 'badge-outline-info', 'under_review' => 'badge-outline-warning', 'rejected' => 'badge-outline-danger', default => 'badge-outline-secondary' }; @endphp
                                    <span class="badge {{ $sc }} capitalize">{{ str_replace('_', ' ', $claim->status) }}</span>
                                </td>
                                <td class="text-center">
                                    <x-action-dropdown>
                                        <x-slot name="items">
                                            <li><a href="{{ route('admin.core.contract-claims.show', $claim) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                            <li><a href="{{ route('admin.core.contract-claims.pdf', $claim->id) }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> PDF</a></li>
                                            <li><a href="{{ route('admin.core.contract-claims.edit', $claim) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/></svg> Edit</a></li>
                                            <li class="divider"></li>
                                            <li class="danger">
                                                <form action="{{ route('admin.core.contract-claims.destroy', $claim) }}" method="POST" onsubmit="return confirm('Delete this claim?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 001-1h4a1 1 0 001 1m-6 0h6"/></svg> Delete</button>
                                                </form>
                                            </li>
                                        </x-slot>
                                    </x-action-dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center">No claims found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $claims->links() }}</div>
        </div>
    </div>
@endsection
