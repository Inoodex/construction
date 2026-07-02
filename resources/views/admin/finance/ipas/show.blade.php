@extends('admin.layouts.master')

@section('title', 'IPA Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">IPA: {{ $ipa->ipa_number }}</h2>
        <div class="flex gap-2">
            @if($ipa->status === 'draft')
                <a href="{{ route('admin.finance.ipas.edit', $ipa->id) }}" class="btn btn-primary gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Edit
                </a>
            @endif
            <a href="{{ route('admin.finance.ipas.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to List
            </a>
        </div>
    </div>

    @php
        $badgeClass = ['draft' => 'badge-outline-secondary', 'submitted' => 'badge-outline-info', 'certified' => 'badge-outline-primary', 'approved' => 'badge-outline-success', 'rejected' => 'badge-outline-danger', 'paid' => 'badge-outline-dark'];
    @endphp

    <div class="mt-6 grid gap-6 sm:grid-cols-3 lg:grid-cols-6">
        <div class="panel">
            <label class="text-xs text-white-dark">IPA Number</label>
            <p class="font-mono font-semibold text-primary">{{ $ipa->ipa_number }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Project</label>
            <p class="font-semibold">{{ $ipa->project->name ?? 'N/A' }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Status</label>
            <p><span class="badge {{ $badgeClass[$ipa->status] }} capitalize">{{ $ipa->status }}</span></p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Period</label>
            <p class="text-xs">{{ $ipa->period_start->format('d M Y') }} - {{ $ipa->period_end->format('d M Y') }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Application Date</label>
            <p class="text-xs">{{ $ipa->application_date->format('d M Y') }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Retention Rate</label>
            <p class="font-semibold">{{ $ipa->retention_rate }}%</p>
        </div>
    </div>

    <div class="mt-4 grid gap-4 sm:grid-cols-3 lg:grid-cols-5">
        <div class="panel bg-info/10 dark:bg-info/20">
            <label class="text-xs text-white-dark">Previous Cumulative</label>
            <p class="text-lg font-bold text-info">{{ number_format($ipa->previous_cumulative_amount) }}</p>
        </div>
        <div class="panel bg-primary/10 dark:bg-primary/20">
            <label class="text-xs text-white-dark">Applied Amount</label>
            <p class="text-lg font-bold text-primary">{{ number_format($ipa->applied_amount) }}</p>
        </div>
        <div class="panel bg-success/10 dark:bg-success/20">
            <label class="text-xs text-white-dark">Certified Amount</label>
            <p class="text-lg font-bold text-success">{{ number_format($ipa->certified_amount) }}</p>
        </div>
        <div class="panel bg-warning/10 dark:bg-warning/20">
            <label class="text-xs text-white-dark">Retention ({{ $ipa->retention_rate }}%)</label>
            <p class="text-lg font-bold text-warning">{{ number_format($ipa->retention_amount) }}</p>
        </div>
        <div class="panel bg-dark/10 dark:bg-dark/20">
            <label class="text-xs text-white-dark">Net Amount</label>
            <p class="text-lg font-bold">{{ number_format($ipa->net_amount) }}</p>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="flex items-center justify-between">
            <h5 class="text-base font-semibold">Progress Items</h5>
            <div class="flex gap-2">
                @if($canSubmit)
                    <form action="{{ route('admin.finance.ipas.submit', $ipa->id) }}" method="POST" class="inline" onsubmit="return confirm('Submit this IPA for certification?');">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">Submit for Certification</button>
                    </form>
                @endif
                @if($ipa->status === 'submitted')
                    <button type="button" onclick="document.getElementById('certifyForm').classList.toggle('hidden')" class="btn btn-sm btn-primary">Certify</button>
                    <form action="{{ route('admin.finance.ipas.reject', $ipa->id) }}" method="POST" class="inline" onsubmit="return confirm('Reject this IPA?');">
                        @csrf
                        <input type="hidden" name="notes" value="Rejected by engineer" />
                        <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                    </form>
                @endif
                @if($ipa->status === 'certified')
                    <form action="{{ route('admin.finance.ipas.approve', $ipa->id) }}" method="POST" class="inline" onsubmit="return confirm('Approve this IPA for payment?');">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                    </form>
                    <form action="{{ route('admin.finance.ipas.reject', $ipa->id) }}" method="POST" class="inline" onsubmit="return confirm('Reject this IPA?');">
                        @csrf
                        <input type="hidden" name="notes" value="Rejected" />
                        <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                    </form>
                    <form action="{{ route('admin.finance.ipas.generate-invoice', $ipa->id) }}" method="POST" class="inline" onsubmit="return confirm('Generate Invoice from this IPA?');">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-info">Generate Invoice</button>
                    </form>
                @endif
            </div>
        </div>

        <div id="certifyForm" class="mb-5 mt-3 hidden rounded-lg border p-4 dark:border-gray-700">
            <form action="{{ route('admin.finance.ipas.certify', $ipa->id) }}" method="POST">
                @csrf
                <div class="flex items-end gap-4">
                    <div class="flex-1">
                        <label class="text-xs text-white-dark">Certified Amount (max: {{ number_format($ipa->applied_amount) }})</label>
                        <input type="number" step="0.01" min="0" name="certified_amount" class="form-input" required placeholder="Enter certified amount" />
                    </div>
                    <button type="submit" class="btn btn-primary">Certify</button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Item / Description / Unit</th>
                        <th>Prev Qty</th>
                        <th>This Period</th>
                        <th>Cumulative</th>
                        <th>Unit Price</th>
                        <th>Prev Amount</th>
                        <th>This Period</th>
                        <th>Cumulative</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ipa->items as $item)
                        <tr>
                            <td class="text-xs">
                                <span class="font-mono font-semibold">{{ $item->item_number }}</span>
                                <span class="text-white-dark">|</span>
                                {{ $item->description }}
                                <span class="text-white-dark">|</span>
                                <span class="italic">{{ $item->unit }}</span>
                            </td>
                            <td class="text-xs">{{ number_format($item->previous_quantity, 2) }}</td>
                            <td class="font-semibold text-primary">{{ number_format($item->current_quantity, 2) }}</td>
                            <td class="text-xs">{{ number_format($item->cumulative_quantity, 2) }}</td>
                            <td class="text-xs">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-xs">{{ number_format($item->previous_amount, 2) }}</td>
                            <td class="font-semibold">{{ number_format($item->current_amount, 2) }}</td>
                            <td class="font-semibold">{{ number_format($item->cumulative_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center">No items yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($ipa->notes)
        <div class="panel mt-4">
            <h5 class="mb-2 text-base font-semibold">Notes</h5>
            <p class="text-xs text-white-dark">{{ $ipa->notes }}</p>
        </div>
    @endif

    @if($ipa->invoice)
        <div class="panel mt-4">
            <div class="flex items-center justify-between">
                <h5 class="text-base font-semibold">Generated Invoice</h5>
                <a href="{{ route('admin.finance.invoices.show', $ipa->invoice->id) }}" class="btn btn-sm btn-primary">View Invoice</a>
            </div>
            <p class="mt-2 text-xs">Invoice {{ $ipa->invoice->invoice_number }} — {{ number_format($ipa->invoice->total_amount) }} — <span class="badge badge-outline-{{ $ipa->invoice->status === 'paid' ? 'success' : 'warning' }} capitalize">{{ $ipa->invoice->status }}</span></p>
        </div>
    @endif

    @if(in_array($ipa->status, ['submitted', 'certified', 'approved', 'paid']))
        <div class="mt-4 grid gap-4 sm:grid-cols-3">
            @if($ipa->submitted_by)
                <div class="panel">
                    <label class="text-xs text-white-dark">Submitted By</label>
                    <p class="font-semibold">{{ $ipa->submittedBy?->name ?? 'N/A' }}</p>
                    <p class="text-xs text-white-dark">{{ $ipa->submitted_at?->format('d M Y') }}</p>
                </div>
            @endif
            @if($ipa->certified_by)
                <div class="panel">
                    <label class="text-xs text-white-dark">Certified By</label>
                    <p class="font-semibold">{{ $ipa->certifiedBy?->name ?? 'N/A' }}</p>
                    <p class="text-xs text-white-dark">{{ $ipa->certified_at?->format('d M Y') }}</p>
                </div>
            @endif
            @if($ipa->approved_by)
                <div class="panel">
                    <label class="text-xs text-white-dark">Approved By</label>
                    <p class="font-semibold">{{ $ipa->approvedBy?->name ?? 'N/A' }}</p>
                    <p class="text-xs text-white-dark">{{ $ipa->approved_at?->format('d M Y') }}</p>
                </div>
            @endif
        </div>
    @endif
@endsection
