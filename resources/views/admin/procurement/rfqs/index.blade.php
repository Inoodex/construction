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
                                        'draft' => 'badge-outline-secondary',
                                        'sent' => 'badge-outline-info',
                                        'closed' => 'badge-outline-warning',
                                        'awarded' => 'badge-outline-success',
                                        default => 'badge-outline-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} capitalize">{{ $rfq->status }}</span>
                            </td>
                            <td>{{ $rfq->creator?->name ?? '-' }}</td>
                            <td>
                                <x-action-dropdown>
                                    <x-slot name="items">
                                        <li><a href="{{ route('admin.procurement.rfqs.show', $rfq) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                        <li><a href="{{ route('admin.procurement.rfqs.pdf', $rfq->id) }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> PDF</a></li>
                                        @if($rfq->status === 'draft')
                                        <li><a href="{{ route('admin.procurement.rfqs.edit', $rfq) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/></svg> Edit</a></li>
                                        @endif
                                    </x-slot>
                                </x-action-dropdown>
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
