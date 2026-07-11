@extends('admin.layouts.master')

@section('title', 'Claim Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Claim: {{ $contractClaim->claim_number }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.core.contract-claims.edit', $contractClaim) }}" class="btn btn-outline-secondary">Edit</a>
            <a href="{{ route('admin.core.contract-claims.index') }}" class="btn btn-outline-secondary">
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
            <h3 class="text-lg font-semibold mb-4">Claim Information</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">Claim Number:</span>
                    <p class="font-semibold font-mono">{{ $contractClaim->claim_number }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Status:</span>
                    <p>
                        @php $sc = match($contractClaim->status) { 'granted' => 'badge-outline-success', 'partially_granted' => 'badge-outline-info', 'submitted' => 'badge-outline-info', 'under_review' => 'badge-outline-warning', 'rejected' => 'badge-outline-danger', default => 'badge-outline-secondary' }; @endphp
                        <span class="badge {{ $sc }} capitalize">{{ str_replace('_', ' ', $contractClaim->status) }}</span>
                    </p>
                </div>
                <div class="col-span-2">
                    <span class="text-gray-500">Title:</span>
                    <p class="font-semibold">{{ $contractClaim->title }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Contract:</span>
                    <p class="font-semibold">{{ $contractClaim->contract->contract_number ?? '—' }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Type:</span>
                    <p class="capitalize">{{ str_replace('_', ' ', $contractClaim->type) }}</p>
                </div>
                <div class="col-span-2">
                    <span class="text-gray-500">Description:</span>
                    <p class="whitespace-pre-wrap">{{ $contractClaim->description }}</p>
                </div>
            </div>
        </div>

        <div class="panel">
            <h3 class="text-lg font-semibold mb-4">Claim Values</h3>
            <div class="space-y-4 text-sm">
                <div>
                    <span class="text-gray-500">Claimed Amount:</span>
                    <p class="text-xl font-bold text-primary">{{ $contractClaim->claimed_amount ? number_format($contractClaim->claimed_amount, 2) : 'N/A' }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Claimed Days:</span>
                    <p class="text-xl font-bold text-primary">{{ $contractClaim->claimed_days ? $contractClaim->claimed_days . ' days' : 'N/A' }}</p>
                </div>
                <div class="border-t pt-4">
                    <span class="text-gray-500">Granted Amount:</span>
                    <p class="text-xl font-bold text-success">{{ $contractClaim->granted_amount ? number_format($contractClaim->granted_amount, 2) : 'Pending' }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Granted Days:</span>
                    <p class="text-xl font-bold text-success">{{ $contractClaim->granted_days ? $contractClaim->granted_days . ' days' : 'Pending' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="panel mt-6">
        <h3 class="text-lg font-semibold mb-4">Workflow</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <span class="text-gray-500">Submitted By:</span>
                <p>{{ $contractClaim->submitter->name ?? '—' }}</p>
            </div>
            <div>
                <span class="text-gray-500">Submitted Date:</span>
                <p>{{ $contractClaim->submitted_date?->format('d M Y') ?? '—' }}</p>
            </div>
            <div>
                <span class="text-gray-500">Reviewed By:</span>
                <p>{{ $contractClaim->reviewer->name ?? '—' }}</p>
            </div>
            <div>
                <span class="text-gray-500">Response Date:</span>
                <p>{{ $contractClaim->response_date?->format('d M Y') ?? '—' }}</p>
            </div>
            <div>
                <span class="text-gray-500">Created By:</span>
                <p>{{ $contractClaim->creator->name ?? '—' }}</p>
            </div>
        </div>
    </div>

    @if($contractClaim->notes)
        <div class="panel mt-6">
            <h3 class="text-lg font-semibold mb-4">Notes</h3>
            <p class="text-sm whitespace-pre-wrap">{{ $contractClaim->notes }}</p>
        </div>
    @endif
@endsection
