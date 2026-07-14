@extends('admin.layouts.master')

@section('title', 'Contracts')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Contracts</h2>
        <a href="{{ route('admin.core.contracts.create') }}" class="btn btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            New Contract
        </a>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.core.contracts.index') }}" method="GET" class="flex items-center gap-3 w-full">
                <div class="relative" style="width: 250px;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search contracts..." class="form-input ltr:pr-11 rtl:pl-11 w-full" />
                    <button type="submit" class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5" /><path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" /></svg>
                    </button>
                </div>
                <select name="project_id" class="form-select" style="width: 150px;">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select" style="width: 130px;">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                </select>
                <select name="contract_type" class="form-select" style="width: 140px;">
                    <option value="">All Types</option>
                    <option value="main" {{ request('contract_type') == 'main' ? 'selected' : '' }}>Main</option>
                    <option value="subcontract" {{ request('contract_type') == 'subcontract' ? 'selected' : '' }}>Subcontract</option>
                    <option value="supply" {{ request('contract_type') == 'supply' ? 'selected' : '' }}>Supply</option>
                    <option value="consultancy" {{ request('contract_type') == 'consultancy' ? 'selected' : '' }}>Consultancy</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['search', 'project_id', 'status', 'contract_type']))
                    <a href="{{ route('admin.core.contracts.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Contract #</th>
                            <th>Title</th>
                            <th>Client</th>
                            <th>Project</th>
                            <th>Type</th>
                            <th>Contract Value</th>
                            <th>Commencement</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contracts as $contract)
                            <tr>
                                <td class="font-mono text-xs font-semibold text-primary">{{ $contract->contract_number }}</td>
                                <td class="font-semibold">{{ $contract->title }}</td>
                                <td class="text-xs">{{ $contract->client_name }}</td>
                                <td class="text-xs">{{ $contract->project->name ?? '—' }}</td>
                                <td class="text-xs capitalize">{{ str_replace('_', ' ', $contract->contract_type) }}</td>
                                <td class="text-xs font-semibold">{{ number_format($contract->contract_value, 2) }}</td>
                                <td class="text-xs">{{ $contract->commencement_date?->format('d M Y') ?? '—' }}</td>
                                <td>
                                    @php
                                        $stCls = match($contract->status) {
                                            'active' => 'badge-outline-success',
                                            'completed' => 'badge-outline-info',
                                            'suspended' => 'badge-outline-warning',
                                            'terminated' => 'badge-outline-danger',
                                            default => 'badge-outline-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $stCls }} capitalize">{{ $contract->status }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('admin.core.contracts.show', $contract) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.core.contracts.pdf', $contract) }}" class="btn btn-sm btn-outline-success" target="_blank">PDF</a>
                                        <a href="{{ route('admin.core.contracts.edit', $contract) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        <form action="{{ route('admin.core.contracts.destroy', $contract) }}" method="POST" onsubmit="return confirm('Delete this contract and all related data?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center">No contracts found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $contracts->links() }}</div>
        </div>
    </div>
@endsection
