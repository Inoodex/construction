@extends('admin.layouts.master')

@section('title', 'Amendment Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Amendment: {{ $contractAmendment->amendment_number }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.core.contract-amendments.edit', $contractAmendment) }}" class="btn btn-outline-secondary">Edit</a>
            <a href="{{ route('admin.core.contract-amendments.index') }}" class="btn btn-outline-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List</a>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="panel md:col-span-2">
            <h3 class="text-lg font-semibold mb-4">Amendment Information</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">Amendment Number:</span>
                    <p class="font-semibold font-mono">{{ $contractAmendment->amendment_number }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Status:</span>
                    <p>
                        @php $sc = match($contractAmendment->status) { 'approved' => 'badge-outline-success', 'submitted' => 'badge-outline-info', 'rejected' => 'badge-outline-danger', default => 'badge-outline-secondary' }; @endphp
                        <span class="badge {{ $sc }} capitalize">{{ $contractAmendment->status }}</span>
                    </p>
                </div>
                <div class="col-span-2">
                    <span class="text-gray-500">Title:</span>
                    <p class="font-semibold">{{ $contractAmendment->title }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Contract:</span>
                    <p class="font-semibold">{{ $contractAmendment->contract->contract_number ?? '—' }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Type:</span>
                    <p class="capitalize">{{ str_replace('_', ' ', $contractAmendment->type) }}</p>
                </div>
                <div class="col-span-2">
                    <span class="text-gray-500">Description:</span>
                    <p class="whitespace-pre-wrap">{{ $contractAmendment->description }}</p>
                </div>
            </div>
        </div>

        <div class="panel">
            <h3 class="text-lg font-semibold mb-4">Impact</h3>
            <div class="space-y-4 text-sm">
                <div>
                    <span class="text-gray-500">Cost Impact:</span>
                    <p class="text-xl font-bold {{ ($contractAmendment->cost_impact ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ $contractAmendment->cost_impact !== null ? number_format($contractAmendment->cost_impact, 2) : 'N/A' }}
                    </p>
                </div>
                <div>
                    <span class="text-gray-500">Time Impact:</span>
                    <p class="text-xl font-bold">{{ $contractAmendment->time_impact_days ? $contractAmendment->time_impact_days . ' days' : 'N/A' }}</p>
                </div>
                <div class="border-t pt-4">
                    <span class="text-gray-500">Requested By:</span>
                    <p>{{ $contractAmendment->requester->name ?? '—' }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Approved By:</span>
                    <p>{{ $contractAmendment->approver->name ?? '—' }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Approved Date:</span>
                    <p>{{ $contractAmendment->approved_date?->format('d M Y') ?? '—' }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Created By:</span>
                    <p>{{ $contractAmendment->creator->name ?? '—' }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($contractAmendment->notes)
        <div class="panel mt-6">
            <h3 class="text-lg font-semibold mb-4">Notes</h3>
            <p class="text-sm whitespace-pre-wrap">{{ $contractAmendment->notes }}</p>
        </div>
    @endif
@endsection
