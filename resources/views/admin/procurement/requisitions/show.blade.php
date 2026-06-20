@extends('admin.layouts.master')

@section('title', 'Requisition Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Requisition Details</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.procurement.requisitions.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to List
            </a>
            @if($requisition->status === 'draft')
                <a href="{{ route('admin.procurement.requisitions.edit', $requisition) }}" class="btn btn-primary gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Edit
                </a>
            @endif
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="panel lg:col-span-2">
            <h5 class="mb-4 text-base font-semibold">Requisition Information</h5>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="text-xs text-white-dark">PR Number</label>
                    <p class="font-mono font-semibold text-primary">{{ $requisition->requisition_number }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Status</label>
                    <div>
                        @php $sc = ['draft' => 'badge-outline-secondary', 'submitted' => 'badge-outline-info', 'approved' => 'badge-outline-success', 'rejected' => 'badge-outline-danger']; @endphp
                        <span class="badge {{ $sc[$requisition->status] ?? 'badge-outline-secondary' }} capitalize">{{ $requisition->status }}</span>
                    </div>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Project</label>
                    <p class="font-semibold">{{ $requisition->project->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Requested By</label>
                    <p class="font-semibold">{{ $requisition->requester->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Required Date</label>
                    <p class="font-semibold">{{ $requisition->required_date?->format('d M Y') ?: '—' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Created</label>
                    <p class="text-xs font-semibold">{{ $requisition->created_at->format('d M Y h:i A') }}</p>
                </div>
            </div>
        </div>

        <div class="panel">
            <h5 class="mb-4 text-base font-semibold">Summary</h5>
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Total Items</span>
                    <span class="text-sm font-bold dark:text-white">{{ $requisition->items->count() }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Est. Total</span>
                    <span class="text-sm font-bold dark:text-white">৳{{ number_format($requisition->items->sum(fn($i) => ($i->quantity * ($i->estimated_unit_price ?? 0)))) }}</span>
                </div>
                @if($requisition->status === 'draft')
                    <form action="{{ route('admin.procurement.requisitions.submit', $requisition) }}" method="POST" onsubmit="return confirm('Submit this requisition for approval?');">
                        @csrf
                        <button type="submit" class="btn btn-primary w-full mt-3">Submit for Approval</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    @php $approvalRecord = $requisition->approvals->sortByDesc('id')->first(); @endphp
    @if($approvalRecord)
    <div class="panel mt-6 border-l-4 border-info">
        <h5 class="mb-3 text-base font-semibold">Approval Progress</h5>
        <div class="flex items-center gap-2 mb-3">
            @php $aStatus = $approvalRecord->status; @endphp
            <span class="badge {{ $aStatus === 'approved' ? 'badge-outline-success' : ($aStatus === 'rejected' ? 'badge-outline-danger' : 'badge-outline-warning') }} capitalize">{{ $aStatus }}</span>
            <span class="text-xs text-white-dark">Level {{ $approvalRecord->current_level }}</span>
            @if($approvalRecord->submitted_by === auth()->id() && $approvalRecord->status === 'pending')
                <form action="{{ route('admin.approvals.withdraw', $approvalRecord) }}" method="POST" class="inline" onsubmit="return confirm('Withdraw this approval request?');">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">Withdraw</button>
                </form>
            @endif
        </div>
        @if($approvalRecord->history->count() > 0)
            <div class="space-y-2">
                @foreach($approvalRecord->history as $h)
                    <div class="flex items-center gap-3 rounded-lg bg-gray-50 p-2 text-xs dark:bg-gray-800">
                        <span class="badge {{ $h->status === 'approved' ? 'badge-outline-success' : 'badge-outline-danger' }}">{{ $h->status }}</span>
                        <span class="font-semibold">{{ $h->approver->name ?? 'Unknown' }}</span>
                        <span class="text-white-dark">Level {{ $h->approval_level }}</span>
                        @if($h->comment)<span class="text-gray-500 italic">— {{ $h->comment }}</span>@endif
                        <span class="ml-auto text-white-dark">{{ $h->approved_at?->format('d M Y h:i A') }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-white-dark">Awaiting approval from Level {{ $approvalRecord->current_level }} approvers.</p>
        @endif
    </div>
    @endif

    <div class="panel mt-6">
        <h5 class="mb-4 text-base font-semibold">Items</h5>
        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Material</th>
                        <th>Quantity</th>
                        <th>Est. Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requisition->items as $idx => $item)
                        <tr>
                            <td class="text-xs">{{ $idx + 1 }}</td>
                            <td class="font-semibold">{{ $item->material->name ?? 'Unknown' }}</td>
                            <td>{{ number_format($item->quantity, 2) }} {{ $item->material->unit ?? '' }}</td>
                            <td>৳{{ number_format($item->estimated_unit_price ?? 0, 2) }}</td>
                            <td class="font-semibold">৳{{ number_format(($item->quantity * ($item->estimated_unit_price ?? 0)), 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
