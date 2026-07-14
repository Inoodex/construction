@extends('admin.layouts.master')

@section('title', 'Contract Amendments')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Contract Amendments</h2>
        <a href="{{ route('admin.core.contract-amendments.create') }}" class="btn btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            New Amendment
        </a>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.core.contract-amendments.index') }}" method="GET" class="flex items-center gap-3 w-full">
                <div class="relative" style="width: 250px;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search amendments..." class="form-input ltr:pr-11 rtl:pl-11 w-full" />
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
                <select name="status" class="form-select" style="width: 130px;">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                <select name="type" class="form-select" style="width: 140px;">
                    <option value="">All Types</option>
                    <option value="scope_change" {{ request('type') == 'scope_change' ? 'selected' : '' }}>Scope Change</option>
                    <option value="time_extension" {{ request('type') == 'time_extension' ? 'selected' : '' }}>Time Extension</option>
                    <option value="value_change" {{ request('type') == 'value_change' ? 'selected' : '' }}>Value Change</option>
                    <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['search', 'contract_id', 'status', 'type']))
                    <a href="{{ route('admin.core.contract-amendments.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Amendment #</th>
                            <th>Title</th>
                            <th>Contract</th>
                            <th>Type</th>
                            <th>Cost Impact</th>
                            <th>Time Impact</th>
                            <th>Requested By</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($amendments as $amd)
                            <tr>
                                <td class="font-mono text-xs font-semibold text-primary">{{ $amd->amendment_number }}</td>
                                <td class="font-semibold">{{ $amd->title }}</td>
                                <td class="text-xs">{{ $amd->contract->contract_number ?? '—' }}</td>
                                <td class="text-xs capitalize">{{ str_replace('_', ' ', $amd->type) }}</td>
                                <td class="text-xs font-semibold {{ ($amd->cost_impact ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $amd->cost_impact !== null ? number_format($amd->cost_impact, 2) : '—' }}
                                </td>
                                <td class="text-xs">{{ $amd->time_impact_days ? $amd->time_impact_days . ' days' : '—' }}</td>
                                <td class="text-xs">{{ $amd->requester->name ?? '—' }}</td>
                                <td>
                                    @php $sc = match($amd->status) { 'approved' => 'badge-outline-success', 'submitted' => 'badge-outline-info', 'rejected' => 'badge-outline-danger', default => 'badge-outline-secondary' }; @endphp
                                    <span class="badge {{ $sc }} capitalize">{{ $amd->status }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('admin.core.contract-amendments.show', $amd) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.core.contract-amendments.pdf', $amd->id) }}" target="_blank" class="btn btn-sm btn-outline-success">PDF</a>
                                        <a href="{{ route('admin.core.contract-amendments.edit', $amd) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        <form action="{{ route('admin.core.contract-amendments.destroy', $amd) }}" method="POST" onsubmit="return confirm('Delete this amendment?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center">No amendments found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $amendments->links() }}</div>
        </div>
    </div>
@endsection
