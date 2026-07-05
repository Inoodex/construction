@extends('admin.layouts.master')

@section('title', 'Proposal - ' . $proposal->proposal_number)

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4">
    <h2 class="text-xl font-semibold uppercase">Proposal #{{ $proposal->proposal_number }}</h2>
    <div class="flex gap-2">
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
        <a href="{{ route('admin.crm.proposals.index') }}" class="btn btn-secondary">Back</a>
        <a href="{{ route('admin.crm.proposals.edit', $proposal) }}" class="btn btn-primary">Edit</a>
    </div>
</div>

@php $statusColors = ['draft' => 'badge-outline-secondary', 'sent' => 'badge-outline-info', 'accepted' => 'badge-outline-success', 'rejected' => 'badge-outline-danger', 'expired' => 'badge-outline-dark']; @endphp

<div class="panel mt-6">
    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <div><span class="text-xs text-white-dark">Proposal #</span><p class="font-mono font-semibold">{{ $proposal->proposal_number }}</p></div>
        <div><span class="text-xs text-white-dark">Title</span><p class="font-semibold">{{ $proposal->title }}</p></div>
        <div><span class="text-xs text-white-dark">Client</span><p>{{ $proposal->client?->company_name ?? '—' }}</p></div>
        <div><span class="text-xs text-white-dark">Lead</span><p>{{ $proposal->lead?->company_name ?? '—' }}</p></div>
        <div><span class="text-xs text-white-dark">Status</span><p><span class="badge {{ $statusColors[$proposal->status] ?? 'badge-outline-secondary' }} capitalize">{{ $proposal->status }}</span></p></div>
        <div><span class="text-xs text-white-dark">Total Amount</span><p class="font-mono font-bold text-lg">৳{{ number_format($proposal->total_amount, 2) }}</p></div>
        <div><span class="text-xs text-white-dark">Valid Until</span><p>{{ $proposal->valid_until?->format('d M Y') ?? '—' }}</p></div>
        <div><span class="text-xs text-white-dark">Created By</span><p>{{ $proposal->creator?->name ?? '—' }}</p></div>
        @if($proposal->notes)
            <div class="md:col-span-4"><span class="text-xs text-white-dark">Notes</span><p>{{ $proposal->notes }}</p></div>
        @endif
    </div>
</div>

<div class="panel mt-6">
    <h5 class="mb-4 text-base font-semibold">Proposal Items</h5>
    <div class="table-responsive">
        <table class="table-hover table">
            <thead>
                <tr>
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
                        <td class="text-right font-mono">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right font-mono font-semibold">{{ number_format($item->total_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="font-semibold"><td colspan="5" class="text-right">Subtotal</td><td class="text-right font-mono">৳{{ number_format($proposal->subtotal, 2) }}</td></tr>
                <tr class="font-semibold"><td colspan="5" class="text-right">Tax ({{ $proposal->tax_rate }}%)</td><td class="text-right font-mono">৳{{ number_format($proposal->tax_amount, 2) }}</td></tr>
                <tr class="font-bold text-lg"><td colspan="5" class="text-right">Total</td><td class="text-right font-mono">৳{{ number_format($proposal->total_amount, 2) }}</td></tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
