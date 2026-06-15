@extends('admin.layouts.master')

@section('title', 'BOQ Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">BOQ: {{ $boq->boq_number }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.finance.boqs.edit', $boq->id) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit
            </a>
            <a href="{{ route('admin.finance.boqs.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to List
            </a>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-4">
        <div class="panel">
            <label class="text-xs text-white-dark">BOQ Number</label>
            <p class="font-mono font-semibold text-primary">{{ $boq->boq_number }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Project</label>
            <p class="font-semibold">{{ $boq->project->name ?? 'N/A' }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Status</label>
            <p>@php $sc = ['draft' => 'badge-outline-secondary', 'approved' => 'badge-outline-success', 'revised' => 'badge-outline-warning']; @endphp
                <span class="badge {{ $sc[$boq->status] ?? 'badge-outline-secondary' }} capitalize">{{ $boq->status }}</span></p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Total Amount</label>
            <p class="text-lg font-bold text-primary">৳{{ number_format($boq->total_amount) }}</p>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="flex items-center justify-between">
            <h5 class="text-base font-semibold">BOQ Items</h5>
            <button type="button" onclick="document.getElementById('addItemForm').classList.toggle('hidden')" class="btn btn-sm btn-outline-primary">+ Add Item</button>
        </div>

        <div id="addItemForm" class="mb-5 mt-3 hidden rounded-lg border p-4 dark:border-gray-700">
            <form action="{{ route('admin.finance.boqs.items.store', $boq->id) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-3 md:grid-cols-6">
                    <div>
                        <input type="text" name="item_number" placeholder="Item #" class="form-input" required />
                    </div>
                    <div class="md:col-span-2">
                        <input type="text" name="description" placeholder="Description" class="form-input" required />
                    </div>
                    <div>
                        <input type="text" name="unit" placeholder="Unit" class="form-input" required />
                    </div>
                    <div>
                        <input type="number" step="0.0001" name="quantity" placeholder="Qty" class="form-input" required />
                    </div>
                    <div>
                        <input type="number" step="0.01" name="unit_price" placeholder="Unit Price" class="form-input" required />
                    </div>
                </div>
                <div class="mt-2">
                    <textarea name="notes" class="form-input" rows="1" placeholder="Notes (optional)"></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Add Item</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Item #</th>
                        <th class="w-1/3">Description</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($boq->items as $item)
                        <tr>
                            <td class="text-xs font-mono">{{ $item->item_number }}</td>
                            <td class="text-xs">{{ $item->description }}</td>
                            <td class="text-xs">{{ $item->unit }}</td>
                            <td class="text-xs">{{ number_format($item->quantity, 2) }}</td>
                            <td class="text-xs">৳{{ number_format($item->unit_price, 2) }}</td>
                            <td class="font-semibold">৳{{ number_format($item->total_price, 2) }}</td>
                            <td class="text-center">
                                <form action="{{ route('admin.finance.boqs.items.destroy', [$boq->id, $item->id]) }}" method="POST" onsubmit="return confirm('Remove this item?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">No items yet. Click "Add Item" above.</td></tr>
                    @endforelse
                </tbody>
                @if($boq->items->count())
                    <tfoot>
                        <tr class="font-bold">
                            <td colspan="5" class="text-right">Grand Total</td>
                            <td>৳{{ number_format($boq->total_amount) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
@endsection
