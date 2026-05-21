@extends('admin.layouts.master')

@section('title', 'Purchase Requisitions')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Purchase Requisitions</h2>
        <div class="flex w-full flex-wrap items-center justify-end gap-4 sm:w-auto">
            <a href="{{ route('admin.procurement.requisitions.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                New Requisition
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-5 flex flex-col gap-5 md:flex-row md:items-center">
            <form action="{{ route('admin.procurement.requisitions.index') }}" method="GET" class="flex flex-1 flex-col gap-5 md:flex-row md:items-center w-full">
                <div class="flex gap-2">
                    <select name="project_id" class="form-select w-full md:w-44">
                        <option value="">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="form-select w-full md:w-36">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>PR Number</th>
                            <th>Project</th>
                            <th>Requested By</th>
                            <th>Items</th>
                            <th>Required Date</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requisitions as $pr)
                            <tr>
                                <td><span class="font-mono text-xs font-semibold text-primary">{{ $pr->requisition_number }}</span></td>
                                <td class="text-xs">{{ $pr->project->name ?? 'N/A' }}</td>
                                <td class="text-xs">{{ $pr->requester->name ?? 'N/A' }}</td>
                                <td class="text-xs">{{ $pr->items->count() }} item(s)</td>
                                <td class="text-xs">{{ $pr->required_date?->format('d M Y') ?: '—' }}</td>
                                <td>
                                    @php
                                        $sc = ['draft' => 'badge-outline-secondary', 'submitted' => 'badge-outline-info', 'approved' => 'badge-outline-success', 'rejected' => 'badge-outline-danger'];
                                    @endphp
                                    <span class="badge {{ $sc[$pr->status] ?? 'badge-outline-secondary' }} capitalize">{{ $pr->status }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.procurement.requisitions.show', $pr->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.procurement.requisitions.edit', $pr->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.procurement.requisitions.destroy', $pr->id) }}" method="POST" onsubmit="return confirm('Delete this requisition?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No requisitions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $requisitions->links() }}
            </div>
        </div>
    </div>
@endsection
