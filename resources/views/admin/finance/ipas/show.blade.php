@extends('admin.layouts.master')

@section('title', 'IPA Details - ' . $ipa->ipa_number)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Interim Payment Application: {{ $ipa->ipa_number }}</h2>
            <p class="text-xs text-white-dark mt-0.5">{{ $ipa->title }} — {{ $ipa->project->name ?? 'N/A' }}</p>
        </div>
        <div class="flex items-center gap-2">
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

    {{-- Metadata Summary Panel --}}
    <div class="mt-6 grid gap-6 sm:grid-cols-3 lg:grid-cols-6">
        <div class="panel">
            <label class="text-xs text-white-dark uppercase font-semibold">IPA Number</label>
            <p class="font-mono font-bold text-primary text-base">{{ $ipa->ipa_number }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark uppercase font-semibold">Project</label>
            <p class="font-semibold text-sm">{{ $ipa->project->name ?? 'N/A' }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark uppercase font-semibold">Status</label>
            <p><span class="badge {{ $badgeClass[$ipa->status] ?? 'badge-outline-secondary' }} capitalize text-xs px-2.5 py-1">{{ $ipa->status }}</span></p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark uppercase font-semibold">Billing Period</label>
            <p class="text-xs font-medium">{{ $ipa->period_start->format('d M Y') }} — {{ $ipa->period_end->format('d M Y') }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark uppercase font-semibold">Application Date</label>
            <p class="text-xs font-medium">{{ $ipa->application_date->format('d M Y') }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark uppercase font-semibold">Retention Rate</label>
            <p class="font-semibold text-warning text-sm">{{ $ipa->retention_rate }}%</p>
        </div>
    </div>

    {{-- Top Metric Cards --}}
    <div class="mt-6 grid gap-4 sm:grid-cols-3 lg:grid-cols-5">
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark uppercase font-semibold">Previous Cumulative</label>
            <p class="text-xl font-extrabold text-info mt-1">৳{{ number_format($ipa->previous_cumulative_amount, 2) }}</p>
            <span class="text-[11px] text-white-dark mt-0.5">Prior Certified Billed</span>
        </div>
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark uppercase font-semibold">Applied Amount</label>
            <p class="text-xl font-extrabold text-primary mt-1">৳{{ number_format($ipa->applied_amount, 2) }}</p>
            <span class="text-[11px] text-white-dark mt-0.5">Claimed This Period</span>
        </div>
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark uppercase font-semibold">Certified Amount</label>
            <p class="text-xl font-extrabold text-success mt-1">৳{{ number_format($ipa->certified_amount, 2) }}</p>
            <span class="text-[11px] text-white-dark mt-0.5">Engineer Certified</span>
        </div>
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark uppercase font-semibold">Retention ({{ $ipa->retention_rate }}%)</label>
            <p class="text-xl font-extrabold text-warning mt-1">-৳{{ number_format($ipa->retention_amount, 2) }}</p>
            <span class="text-[11px] text-white-dark mt-0.5">Deducted Guarantee</span>
        </div>
        <div class="panel text-center flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-800">
            <label class="text-xs text-white-dark uppercase font-semibold">Net Payable</label>
            <p class="text-xl font-extrabold text-primary dark:text-white mt-1">৳{{ number_format($ipa->net_amount, 2) }}</p>
            <span class="text-[11px] text-white-dark mt-0.5">Net Billed Invoice</span>
        </div>
    </div>

    {{-- Progress Items Section --}}
    <div class="panel mt-6">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
            <h5 class="text-base font-semibold uppercase">Progress Items Breakdown</h5>
            <div class="flex flex-wrap items-center gap-2">
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

        {{-- Certify Form --}}
        <div id="certifyForm" class="mb-5 mt-3 hidden rounded-lg border p-4 bg-gray-50 dark:bg-gray-800/40 dark:border-gray-700">
            <form action="{{ route('admin.finance.ipas.certify', $ipa->id) }}" method="POST" class="flex flex-nowrap items-center gap-3 overflow-x-auto pb-1">
                @csrf
                <span class="text-xs font-semibold text-white-dark whitespace-nowrap">Certified Amount (max: ৳{{ number_format($ipa->applied_amount, 2) }}):</span>
                <input type="number" step="0.01" min="0" name="certified_amount" class="form-input w-56" required placeholder="Enter certified amount (৳)" />
                <button type="submit" class="btn btn-primary whitespace-nowrap">Confirm Certification</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto text-xs">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800 border-b dark:border-gray-700">
                        <th class="py-2.5 px-4 text-left">Item #</th>
                        <th class="py-2.5 px-4 text-left w-1/3">Description</th>
                        <th class="py-2.5 px-4 text-center">Unit</th>
                        <th class="py-2.5 px-4 text-right">Prev Qty</th>
                        <th class="py-2.5 px-4 text-right">Period Qty</th>
                        <th class="py-2.5 px-4 text-right">Cumul Qty</th>
                        <th class="py-2.5 px-4 text-right">Rate (৳)</th>
                        <th class="py-2.5 px-4 text-right">Prev (৳)</th>
                        <th class="py-2.5 px-4 text-right">Period (৳)</th>
                        <th class="py-2.5 px-4 text-right">Cumul (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ipa->items as $item)
                        <tr class="border-t dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="py-2.5 px-4 font-mono font-bold text-primary">{{ $item->item_number }}</td>
                            <td class="py-2.5 px-4 font-medium">{{ $item->description }}</td>
                            <td class="py-2.5 px-4 text-center italic text-white-dark">{{ $item->unit }}</td>
                            <td class="py-2.5 px-4 text-right font-mono">{{ number_format($item->previous_quantity, 2) }}</td>
                            <td class="py-2.5 px-4 text-right font-mono font-bold text-primary">{{ number_format($item->current_quantity, 2) }}</td>
                            <td class="py-2.5 px-4 text-right font-mono font-semibold">{{ number_format($item->cumulative_quantity, 2) }}</td>
                            <td class="py-2.5 px-4 text-right font-mono">৳{{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-2.5 px-4 text-right font-mono text-white-dark">৳{{ number_format($item->previous_amount, 2) }}</td>
                            <td class="py-2.5 px-4 text-right font-mono font-bold text-primary">৳{{ number_format($item->current_amount, 2) }}</td>
                            <td class="py-2.5 px-4 text-right font-mono font-bold text-success">৳{{ number_format($item->cumulative_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="py-4 text-center text-gray-400">No progress items attached to this IPA.</td></tr>
                    @endforelse
                </tbody>
                @if($ipa->items->count())
                    <tfoot>
                        <tr class="border-t-2 border-gray-300 dark:border-gray-700 font-extrabold bg-gray-100 dark:bg-gray-800 text-xs">
                            <td colspan="7" class="py-3 px-4 text-right uppercase">Grand Totals</td>
                            <td class="py-3 px-4 text-right font-mono text-white-dark">৳{{ number_format($ipa->items->sum('previous_amount'), 2) }}</td>
                            <td class="py-3 px-4 text-right font-mono text-primary">৳{{ number_format($ipa->items->sum('current_amount'), 2) }}</td>
                            <td class="py-3 px-4 text-right font-mono text-success">৳{{ number_format($ipa->items->sum('cumulative_amount'), 2) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    @if($ipa->notes)
        <div class="panel mt-4">
            <h5 class="mb-2 text-sm font-semibold uppercase text-white-dark">Application Notes</h5>
            <p class="text-xs text-white-dark leading-relaxed">{{ $ipa->notes }}</p>
        </div>
    @endif

    @if($ipa->invoice)
        <div class="panel mt-4 border-l-4 border-success">
            <div class="flex items-center justify-between">
                <div>
                    <h5 class="text-sm font-bold uppercase text-success">Generated Sales Invoice</h5>
                    <p class="mt-1 text-xs font-mono font-semibold">
                        Invoice {{ $ipa->invoice->invoice_number }} &mdash; Total ৳{{ number_format($ipa->invoice->total_amount, 2) }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="badge badge-outline-{{ $ipa->invoice->status === 'paid' ? 'success' : 'warning' }} capitalize text-xs">
                        {{ $ipa->invoice->status }}
                    </span>
                    <a href="{{ route('admin.finance.invoices.show', $ipa->invoice->id) }}" class="btn btn-sm btn-primary">View Invoice</a>
                </div>
            </div>
        </div>
    @endif

    @if(in_array($ipa->status, ['submitted', 'certified', 'approved', 'paid']))
        <div class="mt-4 grid gap-4 sm:grid-cols-3">
            @if($ipa->submitted_by)
                <div class="panel">
                    <label class="text-xs text-white-dark uppercase font-semibold">Submitted By</label>
                    <p class="font-semibold text-sm">{{ $ipa->submittedBy?->name ?? 'N/A' }}</p>
                    <p class="text-xs text-white-dark mt-0.5">{{ $ipa->submitted_at?->format('d M Y, h:i A') }}</p>
                </div>
            @endif
            @if($ipa->certified_by)
                <div class="panel">
                    <label class="text-xs text-white-dark uppercase font-semibold">Certified By</label>
                    <p class="font-semibold text-sm text-primary">{{ $ipa->certifiedBy?->name ?? 'N/A' }}</p>
                    <p class="text-xs text-white-dark mt-0.5">{{ $ipa->certified_at?->format('d M Y, h:i A') }}</p>
                </div>
            @endif
            @if($ipa->approved_by)
                <div class="panel">
                    <label class="text-xs text-white-dark uppercase font-semibold">Approved By</label>
                    <p class="font-semibold text-sm text-success">{{ $ipa->approvedBy?->name ?? 'N/A' }}</p>
                    <p class="text-xs text-white-dark mt-0.5">{{ $ipa->approved_at?->format('d M Y, h:i A') }}</p>
                </div>
            @endif
        </div>
    @endif
@endsection
