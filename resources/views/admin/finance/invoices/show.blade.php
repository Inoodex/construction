@extends('admin.layouts.master')

@section('title', 'Invoice Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Invoice: {{ $invoice->invoice_number }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.finance.invoices.edit', $invoice->id) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit
            </a>
            <a href="{{ route('admin.finance.invoices.pdf', $invoice->id) }}" class="btn btn-success gap-2" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                Print PDF
            </a>
            <a href="{{ route('admin.finance.invoices.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to List
            </a>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-4">
        <div class="panel">
            <label class="text-xs text-white-dark">Invoice #</label>
            <p class="font-mono font-semibold text-primary">{{ $invoice->invoice_number }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Project</label>
            <p class="font-semibold">{{ $invoice->project->name ?? 'N/A' }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Status</label>
            <p>@php $sc = ['draft' => 'badge-outline-secondary', 'sent' => 'badge-outline-info', 'partially_paid' => 'badge-outline-warning', 'paid' => 'badge-outline-success', 'overdue' => 'badge-outline-danger', 'cancelled' => 'badge-outline-dark']; @endphp
                <span class="badge {{ $sc[$invoice->status] ?? 'badge-outline-secondary' }} capitalize">{{ str_replace('_', ' ', $invoice->status) }}</span></p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Due Amount</label>
            <p class="text-lg font-bold {{ $invoice->due_amount > 0 ? 'text-danger' : 'text-success' }}">৳{{ number_format($invoice->due_amount) }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Issue Date</label>
            <p class="font-semibold">{{ $invoice->issue_date->format('d M Y') }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Due Date</label>
            <p class="font-semibold">{{ $invoice->due_date->format('d M Y') }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Tax Rate</label>
            <p class="font-semibold">{{ $invoice->tax_rate }}%</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Retention Rate</label>
            <p class="font-semibold">{{ $invoice->retention_rate }}%</p>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="flex items-center justify-between">
            <h5 class="text-base font-semibold">Invoice Items</h5>
            <button type="button" onclick="document.getElementById('addItemForm').classList.toggle('hidden')" class="btn btn-sm btn-outline-primary">+ Add Item</button>
        </div>

        <div id="addItemForm" class="mb-5 mt-3 hidden rounded-lg border p-4 dark:border-gray-700">
            <form action="{{ route('admin.finance.invoices.items.store', $invoice->id) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
                    <div class="md:col-span-2">
                        <input type="text" name="description" placeholder="Description" class="form-input" required />
                    </div>
                    <div>
                        <input type="number" step="0.0001" name="quantity" placeholder="Qty" class="form-input" required />
                    </div>
                    <div>
                        <input type="number" step="0.01" name="unit_price" placeholder="Unit Price" class="form-input" required />
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Add Item</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th class="w-1/2">Description</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoice->items as $item)
                        <tr>
                            <td class="text-xs">{{ $item->description }}</td>
                            <td class="text-xs">{{ number_format($item->quantity, 2) }}</td>
                            <td class="text-xs">৳{{ number_format($item->unit_price, 2) }}</td>
                            <td class="font-semibold">৳{{ number_format($item->total_price, 2) }}</td>
                            <td class="text-center">
                                <form action="{{ route('admin.finance.invoices.items.destroy', [$invoice->id, $item->id]) }}" method="POST" onsubmit="return confirm('Remove?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">No items yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-end">
            <div class="w-full max-w-sm space-y-2">
                <div class="flex justify-between text-sm"><span>Subtotal</span><span>৳{{ number_format($invoice->subtotal) }}</span></div>
                <div class="flex justify-between text-sm"><span>Tax ({{ $invoice->tax_rate }}%)</span><span>৳{{ number_format($invoice->tax_amount) }}</span></div>
                <div class="flex justify-between text-sm"><span>Retention ({{ $invoice->retention_rate }}%)</span><span>-৳{{ number_format($invoice->retention_amount) }}</span></div>
                <div class="flex justify-between font-bold text-lg border-t pt-2"><span>Total</span><span>৳{{ number_format($invoice->total_amount) }}</span></div>
                <div class="flex justify-between text-sm"><span>Paid</span><span class="text-success">৳{{ number_format($invoice->paid_amount) }}</span></div>
                <div class="flex justify-between font-semibold text-base {{ $invoice->due_amount > 0 ? 'text-danger' : 'text-success' }}"><span>Due</span><span>৳{{ number_format($invoice->due_amount) }}</span></div>
            </div>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="flex items-center justify-between">
            <h5 class="text-base font-semibold">Payments Received</h5>
            <button type="button" onclick="document.getElementById('addPaymentForm').classList.toggle('hidden')" class="btn btn-sm btn-outline-success">+ Record Payment</button>
        </div>

        <div id="addPaymentForm" class="mb-5 mt-3 hidden rounded-lg border p-4 dark:border-gray-700">
            <form action="{{ route('admin.finance.invoices.payments.store', $invoice->id) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
                    <div>
                        <input type="number" step="0.01" name="amount" placeholder="Amount" class="form-input" required />
                    </div>
                    <div>
                        <input type="date" name="payment_date" class="form-input" required value="{{ date('Y-m-d') }}" />
                    </div>
                    <div>
                        <input type="text" name="payment_method" placeholder="Method (e.g. Bank)" class="form-input" />
                    </div>
                    <div>
                        <input type="text" name="reference" placeholder="Reference #" class="form-input" />
                    </div>
                </div>
                <div class="mt-2">
                    <textarea name="notes" class="form-input" rows="1" placeholder="Notes (optional)"></textarea>
                </div>
                <button type="submit" class="btn btn-success mt-2">Record Payment</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Reference</th>
                        <th>Notes</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoice->payments as $p)
                        <tr>
                            <td class="text-xs">{{ $p->payment_date->format('d M Y') }}</td>
                            <td class="font-semibold text-success">৳{{ number_format($p->amount) }}</td>
                            <td class="text-xs">{{ $p->payment_method ?? '-' }}</td>
                            <td class="text-xs">{{ $p->reference ?? '-' }}</td>
                            <td class="text-xs">{{ $p->notes ?? '-' }}</td>
                            <td class="text-center">
                                <form action="{{ route('admin.finance.invoices.payments.destroy', [$invoice->id, $p->id]) }}" method="POST" onsubmit="return confirm('Remove payment?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No payments recorded.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
