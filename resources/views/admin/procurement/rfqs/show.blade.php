@extends('admin.layouts.master')

@section('title', 'RFQ: ' . $rfq->rfq_number)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">RFQ: {{ $rfq->rfq_number }}</h2>
        <div class="flex items-center gap-2">
            @if($rfq->status === 'draft')
                <a href="{{ route('admin.procurement.rfqs.edit', $rfq) }}" class="btn btn-primary gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Edit
                </a>
                <form action="{{ route('admin.procurement.rfqs.send', $rfq) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success gap-2">Send to Vendors</button>
                </form>
            @endif
            @if(in_array($rfq->status, ['sent', 'awarded']))
                <form action="{{ route('admin.procurement.rfqs.close', $rfq) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-warning gap-2">Close</button>
                </form>
            @endif
            <a href="{{ route('admin.procurement.rfqs.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <div class="panel lg:col-span-2">
            <div class="mb-4">
                <h3 class="text-lg font-semibold">{{ $rfq->title }}</h3>
                <p class="text-white-dark text-sm">{{ $rfq->description }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-white-dark">Project:</span> {{ $rfq->project?->name ?? '-' }}</div>
                <div>
                    <span class="text-white-dark">Status:</span>
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
                </div>
                <div><span class="text-white-dark">Issue Date:</span> {{ $rfq->issue_date->format('d/m/Y') }}</div>
                <div><span class="text-white-dark">Closing Date:</span> {{ $rfq->closing_date->format('d/m/Y') }}</div>
                <div><span class="text-white-dark">Created By:</span> {{ $rfq->creator?->name ?? '-' }}</div>
            </div>
        </div>

        <div class="panel">
            <h3 class="text-lg font-semibold mb-3">Invited Vendors</h3>
            <ul class="space-y-2">
                @foreach($rfq->vendors as $rv)
                    <li class="flex items-center justify-between">
                        <span>{{ $rv->vendor->name }}</span>
                        @php
                            $vStatusClass = match($rv->status) {
                                'invited' => 'badge-outline-info',
                                'submitted' => 'badge-outline-success',
                                'declined' => 'badge-outline-danger',
                                default => 'badge-outline-secondary',
                            };
                        @endphp
                        <span class="badge {{ $vStatusClass }} capitalize">{{ $rv->status }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    @if($rfq->quotations->count() > 0)
        <div class="panel mt-6">
            <h3 class="text-lg font-semibold mb-4">Quotation Comparison Matrix</h3>
            <div class="table-responsive overflow-x-auto">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="bg-gray-100 dark:bg-gray-800">Item</th>
                            <th class="bg-gray-100 dark:bg-gray-800">Unit</th>
                            <th class="bg-gray-100 dark:bg-gray-800">Qty</th>
                            @foreach($rfq->quotations as $q)
                                <th class="text-center {{ $q->is_winner ? 'bg-green-100 dark:bg-green-900' : '' }}">
                                    {{ $q->vendor->name }}
                                    @if($q->is_winner)
                                        <span class="badge bg-green-600 text-white text-xs ml-1">Won</span>
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rfq->items as $item)
                            <tr>
                                <td>{{ $item->material->name }}</td>
                                <td>{{ $item->unit }}</td>
                                <td>{{ $item->quantity }}</td>
                                @foreach($rfq->quotations as $q)
                                    @php
                                        $qi = $q->items->firstWhere('rfq_item_id', $item->id);
                                        $bestPrices = [];
                                        foreach ($rfq->quotations as $q2) {
                                            $p = $q2->items->firstWhere('rfq_item_id', $item->id);
                                            if ($p) $bestPrices[] = $p->unit_price;
                                        }
                                        $isBest = $qi && $qi->unit_price == min($bestPrices);
                                    @endphp
                                    <td class="text-center {{ $q->is_winner ? 'bg-green-50 dark:bg-green-950' : '' }} {{ $isBest && !$q->is_winner ? 'bg-blue-50 dark:bg-blue-950' : '' }}">
                                        @if($qi)
                                            <span class="font-semibold {{ $isBest ? 'text-green-600 dark:text-green-400' : '' }}">{{ number_format($qi->unit_price, 2) }}</span>
                                            <span class="text-xs text-white-dark block">{{ number_format($qi->unit_price * $item->quantity, 2) }}</span>
                                        @else
                                            <span class="text-white-dark">-</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-bold">
                            <td colspan="3" class="text-right">Total</td>
                            @foreach($rfq->quotations as $q)
                                <td class="text-center {{ $q->is_winner ? 'bg-green-100 dark:bg-green-900' : '' }}">
                                    {{ number_format($q->items->sum(fn($i) => $i->unit_price * $i->rfqItem->quantity), 2) }}
                                </td>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if($rfq->status !== 'awarded' && $rfq->status !== 'closed')
                <div class="mt-4">
                    <h4 class="font-semibold mb-2">Award Quotation</h4>
                    <form action="{{ route('admin.procurement.rfqs.award', $rfq) }}" method="POST" class="flex items-center gap-3">
                        @csrf
                        <select name="quotation_id" class="form-select max-w-xs" required>
                            <option value="">Select winning quotation</option>
                            @foreach($rfq->quotations as $q)
                                <option value="{{ $q->id }}">{{ $q->vendor->name }} ({{ number_format($q->items->sum(fn($i) => $i->unit_price * $i->rfqItem->quantity), 2) }})</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-success">Award & Create PO</button>
                    </form>
                </div>
            @endif
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <div class="panel">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">RFQ Items</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Material</th>
                            <th>Qty</th>
                            <th>Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rfq->items as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->material->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->unit }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Quotations Received</h3>
                @if($rfq->status === 'sent' && $rfq->vendors->where('status', 'invited')->count() > 0)
                    <div>
                        @foreach($rfq->vendors->where('status', 'invited') as $rv)
                            <a href="{{ route('admin.procurement.rfqs.quotations.create', [$rfq, 'vendor_id' => $rv->vendor_id]) }}" class="btn btn-sm btn-outline-primary">
                                Add Quote: {{ $rv->vendor->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
            @if($rfq->quotations->count() > 0)
                <div class="space-y-3">
                    @foreach($rfq->quotations as $q)
                        <div class="border rounded p-3 {{ $q->is_winner ? 'border-green-500 bg-green-50 dark:bg-green-950' : '' }}">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="font-semibold">{{ $q->vendor->name }}</span>
                                    @if($q->is_winner)
                                        <span class="badge bg-green-600 text-white text-xs ml-1">Awarded</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold">{{ number_format($q->items->sum(fn($i) => $i->unit_price * $i->rfqItem->quantity), 2) }}</span>
                                    <a href="{{ route('admin.procurement.rfqs.quotations.edit', [$rfq, $q]) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                </div>
                            </div>
                            @if($q->quotation_number)
                                <div class="text-xs text-white-dark mt-1">Ref: {{ $q->quotation_number }} | {{ $q->submitted_date->format('d/m/Y') }}</div>
                            @endif
                            @if($q->notes)
                                <div class="text-xs mt-1">{{ $q->notes }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-white-dark text-center py-4">No quotations received yet.</p>
            @endif
        </div>
    </div>
@endsection
