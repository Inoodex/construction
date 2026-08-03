@extends('admin.layouts.master')

@section('title', 'Proposal - ' . $proposal->proposal_number)

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-semibold uppercase flex items-center gap-2">
            <span>Proposal #{{ $proposal->proposal_number }}</span>
            @php $statusColors = ['draft' => 'badge-outline-secondary', 'sent' => 'badge-outline-info', 'accepted' => 'badge-outline-success', 'rejected' => 'badge-outline-danger', 'expired' => 'badge-outline-dark']; @endphp
            <span class="badge {{ $statusColors[$proposal->status] ?? 'badge-outline-secondary' }} capitalize text-xs px-2.5 py-1">{{ $proposal->status }}</span>
        </h2>
        <p class="text-xs text-white-dark mt-1">{{ $proposal->title }}</p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
        <form action="{{ route('admin.crm.proposals.status', $proposal) }}" method="POST" class="flex items-center gap-1">
            @csrf
            <select name="status" class="form-select text-xs" onchange="this.form.submit()">
                <option value="draft" {{ $proposal->status == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="sent" {{ $proposal->status == 'sent' ? 'selected' : '' }}>Sent</option>
                <option value="accepted" {{ $proposal->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                <option value="rejected" {{ $proposal->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="expired" {{ $proposal->status == 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
        </form>

        {{-- Convert to Project Button --}}
        <form action="{{ route('admin.crm.proposals.convert-to-project', $proposal) }}" method="POST" onsubmit="return confirm('Convert this proposal into an active Construction Project?')">
            @csrf
            <button type="submit" class="btn btn-success gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                Convert to Active Project
            </button>
        </form>

        <a href="{{ route('admin.crm.proposals.edit', $proposal) }}" class="btn btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            Edit
        </a>
        <a href="{{ route('admin.crm.proposals.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back
        </a>
    </div>
</div>

<div class="panel mt-6">
    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <div><span class="text-xs text-white-dark">Proposal #</span><p class="font-mono font-semibold">{{ $proposal->proposal_number }}</p></div>
        <div><span class="text-xs text-white-dark">Title</span><p class="font-semibold">{{ $proposal->title }}</p></div>
        <div><span class="text-xs text-white-dark">Client</span><p class="font-semibold text-primary">{{ $proposal->client?->company_name ?? '—' }}</p></div>
        <div><span class="text-xs text-white-dark">Lead</span><p>{{ $proposal->lead?->company_name ?? '—' }}</p></div>
        <div><span class="text-xs text-white-dark">Status</span><p><span class="badge {{ $statusColors[$proposal->status] ?? 'badge-outline-secondary' }} capitalize">{{ $proposal->status }}</span></p></div>
        <div><span class="text-xs text-white-dark">Total Amount</span><p class="font-mono font-bold text-lg text-primary">৳{{ number_format($proposal->total_amount, 2) }}</p></div>
        <div><span class="text-xs text-white-dark">Valid Until</span><p>{{ $proposal->valid_until?->format('d M Y') ?? '—' }}</p></div>
        <div><span class="text-xs text-white-dark">Created By</span><p>{{ $proposal->creator?->name ?? '—' }}</p></div>
        @if($proposal->notes)
            <div class="md:col-span-4"><span class="text-xs text-white-dark">Notes & Terms</span><p class="text-xs leading-relaxed bg-gray-50 dark:bg-gray-800 p-3 rounded border">{{ $proposal->notes }}</p></div>
        @endif
    </div>
</div>

{{-- Uploaded Custom Proposal Document --}}
@if($proposal->document_path)
    <div class="panel mt-6">
        <h5 class="mb-3 text-sm font-semibold text-white-dark uppercase tracking-wide">Attached Document</h5>
        <div class="flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-danger flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
            <div>
                <a href="{{ asset($proposal->document_path) }}" target="_blank" class="font-semibold text-primary hover:underline text-sm">
                    {{ $proposal->original_document_name ?? basename($proposal->document_path) }}
                </a>
                <p class="text-xs text-white-dark mt-0.5">Click to open document</p>
            </div>
        </div>
    </div>
@endif

<div class="panel mt-6">
    <h5 class="mb-4 text-base font-semibold">Financial Line Items Breakdown</h5>
    <div class="table-responsive">
        <table class="table-hover table text-xs">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800">
                    <th>#</th>
                    <th>Description</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Unit</th>
                    <th class="text-right">Unit Price (৳)</th>
                    <th class="text-right">Total (৳)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proposal->items as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item->description }}</td>
                        <td class="text-right font-mono">{{ number_format($item->quantity, 2) }}</td>
                        <td class="text-right">{{ $item->unit ?? '—' }}</td>
                        <td class="text-right font-mono">৳{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right font-mono font-semibold">৳{{ number_format($item->total_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="font-semibold"><td colspan="5" class="text-right">Subtotal</td><td class="text-right font-mono">৳{{ number_format($proposal->subtotal, 2) }}</td></tr>
                <tr class="font-semibold"><td colspan="5" class="text-right">Tax ({{ $proposal->tax_rate }}%)</td><td class="text-right font-mono">৳{{ number_format($proposal->tax_amount, 2) }}</td></tr>
                <tr class="font-bold text-lg"><td colspan="5" class="text-right">Total Amount</td><td class="text-right font-mono text-primary">৳{{ number_format($proposal->total_amount, 2) }}</td></tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
