@extends('admin.layouts.master')

@section('title', 'Change Order Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">{{ $changeOrder->change_order_number }} — {{ $changeOrder->title }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.core.documents.change-orders.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back
            </a>
            @if(!auth()->user()->hasRole('client'))
                <a href="{{ route('admin.core.documents.change-orders.edit', $changeOrder) }}" class="btn btn-primary gap-2">Edit</a>
            @endif
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="panel lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h5 class="text-base font-semibold">Change Order Details</h5>
                <div class="flex gap-2">
                    <span class="badge badge-outline-info capitalize">{{ str_replace('_', ' ', $changeOrder->type) }}</span>
                    @php $sColors = ['draft' => 'badge-outline-secondary', 'submitted' => 'badge-outline-info', 'under_review' => 'badge-outline-warning', 'approved' => 'badge-outline-success', 'rejected' => 'badge-outline-danger', 'implemented' => 'badge-outline-success']; @endphp
                    <span class="badge {{ $sColors[$changeOrder->status] ?? 'badge-outline-secondary' }} capitalize">{{ str_replace('_', ' ', $changeOrder->status) }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div><label class="text-xs text-white-dark">CO Number</label><p class="font-semibold">{{ $changeOrder->change_order_number }}</p></div>
                <div><label class="text-xs text-white-dark">Project</label><p class="font-semibold">{{ $changeOrder->project->name ?? '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Cost Impact</label><p class="font-semibold">{{ $changeOrder->cost_impact ? number_format($changeOrder->cost_impact, 2) : '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Time Impact</label><p class="font-semibold">{{ $changeOrder->time_impact_days ? $changeOrder->time_impact_days . ' days' : '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Related RFI</label><p class="font-semibold">{{ $changeOrder->rfi->rfi_number ?? '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Requested By</label><p class="font-semibold">{{ $changeOrder->requester->name ?? '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Approved By</label><p class="font-semibold">{{ $changeOrder->approver->name ?? '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Approved Date</label><p class="font-semibold">{{ $changeOrder->approved_date?->format('d M Y') ?? '—' }}</p></div>
            </div>

            <hr class="my-4 border-white-light dark:border-gray-700">
            <div><label class="text-xs text-white-dark">Description</label><p class="mt-1 whitespace-pre-wrap">{{ $changeOrder->description }}</p></div>

            @if($changeOrder->notes)
                <hr class="my-4 border-white-light dark:border-gray-700">
                <div><label class="text-xs text-white-dark">Notes</label><p class="mt-1 whitespace-pre-wrap">{{ $changeOrder->notes }}</p></div>
            @endif

            @if($changeOrder->getFirstMedia('attachment'))
                <hr class="my-4 border-white-light dark:border-gray-700">
                <div>
                    <label class="text-xs text-white-dark">Attachment</label>
                    <p class="mt-1">
                        <a href="{{ $changeOrder->getFirstMedia('attachment')->getUrl() }}" target="_blank" class="text-primary hover:underline">
                            {{ $changeOrder->getFirstMedia('attachment')->name }}
                        </a>
                    </p>
                </div>
            @endif
        </div>

        <div class="panel">
            <h5 class="mb-4 text-base font-semibold">Info</h5>
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs">Created</span>
                    <span class="text-xs font-semibold">{{ $changeOrder->created_at->format('d M Y') }}</span>
                </div>
            </div>

            @if(!auth()->user()->hasRole('client') && in_array($changeOrder->status, ['submitted', 'under_review', 'draft']))
                <hr class="my-4 border-white-light dark:border-gray-700">
                <h6 class="mb-3 text-sm font-semibold">Actions</h6>
                <div class="space-y-2">
                    <form action="{{ route('admin.core.documents.change-orders.approve', $changeOrder) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-full" onclick="return confirm('Approve this change order?');">Approve</button>
                    </form>
                    <form action="{{ route('admin.core.documents.change-orders.reject', $changeOrder) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger w-full" onclick="return confirm('Reject this change order?');">Reject</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
