@extends('admin.layouts.master')

@section('title', 'Requests for Quotation')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Requests for Quotation</h2>
        <div class="flex w-full flex-wrap items-center justify-end gap-4 sm:w-auto">
            <a href="{{ route('admin.procurement.rfqs.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                New RFQ
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.procurement.rfqs.index') }}" method="GET" class="flex items-center gap-3 w-full">
                <select name="project_id" class="form-select flex-1">
                    <option value="">Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select flex-1">
                    <option value="">Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    <option value="awarded" {{ request('status') == 'awarded' ? 'selected' : '' }}>Awarded</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['project_id', 'status']))
                    <a href="{{ route('admin.procurement.rfqs.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>RFQ #</th>
                        <th>Title</th>
                        <th>Project</th>
                        <th>Issue Date</th>
                        <th>Closing Date</th>
                        <th>Vendors</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rfqs as $rfq)
                        <tr>
                            <td class="font-semibold">{{ $rfq->rfq_number }}</td>
                            <td>{{ $rfq->title }}</td>
                            <td>{{ $rfq->project?->name ?? '-' }}</td>
                            <td>{{ $rfq->issue_date->format('d/m/Y') }}</td>
                            <td>{{ $rfq->closing_date->format('d/m/Y') }}</td>
                            <td>{{ $rfq->vendors->count() }}</td>
                            <td>
                                @php
                                    $statusClass = match($rfq->status) {
                                        'draft' => 'bg-gray-500',
                                        'sent' => 'bg-blue-500',
                                        'closed' => 'bg-yellow-500',
                                        'awarded' => 'bg-green-500',
                                        default => 'bg-gray-500',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} text-white px-2 py-0.5 rounded text-xs">{{ ucfirst($rfq->status) }}</span>
                            </td>
                            <td>{{ $rfq->creator?->name ?? '-' }}</td>
                            <td>
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.procurement.rfqs.show', $rfq) }}" class="btn btn-sm btn-outline-info">View</a>
                                    @if($rfq->status === 'draft')
                                        <a href="{{ route('admin.procurement.rfqs.edit', $rfq) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-6 text-white-dark">No RFQs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $rfqs->links() }}
        </div>
    </div>
@endsection
