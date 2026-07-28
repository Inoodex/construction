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

        <div id="addItemForm" class="mb-5 mt-3 hidden rounded-lg border p-4 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/40">
            <form action="{{ route('admin.finance.invoices.items.store', $invoice->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <input type="text" name="description" placeholder="Item Description / Service Details *" class="form-input w-full" required />
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex-1 min-w-[140px]">
                        <input type="number" step="0.0001" name="quantity" placeholder="Quantity *" class="form-input w-full" required />
                    </div>
                    <div class="flex-1 min-w-[160px]">
                        <input type="number" step="0.01" name="unit_price" placeholder="Unit Price (৳) *" class="form-input w-full" required />
                    </div>
                    <button type="submit" class="btn btn-primary px-6">+ Add Item</button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto text-xs">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800 border-b dark:border-gray-700">
                        <th class="w-1/2 py-2.5 px-4 text-left">Description</th>
                        <th class="py-2.5 px-4 text-left">Qty</th>
                        <th class="py-2.5 px-4 text-left">Unit Price</th>
                        <th class="py-2.5 px-4 text-left">Total</th>
                        <th class="text-center py-2.5 px-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoice->items as $item)
                        <tr class="border-t dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="text-xs py-2.5 px-4 font-semibold">{{ $item->description }}</td>
                            <td class="text-xs py-2.5 px-4">{{ number_format($item->quantity, 2) }}</td>
                            <td class="text-xs py-2.5 px-4">৳{{ number_format($item->unit_price, 2) }}</td>
                            <td class="font-semibold py-2.5 px-4 text-primary">৳{{ number_format($item->total_price, 2) }}</td>
                            <td class="text-center py-2.5 px-4">
                                <form action="{{ route('admin.finance.invoices.items.destroy', [$invoice->id, $item->id]) }}" method="POST" onsubmit="return confirm('Remove this item?');" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 py-1 font-bold" title="Remove item">X</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4 text-gray-400">No invoice items yet. Click "+ Add Item" above.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-end">
            <div class="w-full max-w-sm space-y-2 rounded-lg border border-gray-200 dark:border-gray-700 p-4 bg-gray-50 dark:bg-gray-800/40">
                <div class="flex justify-between text-xs"><span>Subtotal</span><span class="font-mono font-semibold">৳{{ number_format($invoice->subtotal, 2) }}</span></div>
                <div class="flex justify-between text-xs"><span>Tax ({{ $invoice->tax_rate }}%)</span><span class="font-mono font-semibold">৳{{ number_format($invoice->tax_amount, 2) }}</span></div>
                <div class="flex justify-between text-xs"><span>Retention ({{ $invoice->retention_rate }}%)</span><span class="font-mono font-semibold text-warning">-৳{{ number_format($invoice->retention_amount, 2) }}</span></div>
                <div class="flex justify-between font-bold text-sm border-t pt-2 dark:border-gray-700"><span>Total Payable</span><span class="font-mono text-primary">৳{{ number_format($invoice->total_amount, 2) }}</span></div>
                <div class="flex justify-between text-xs font-semibold"><span>Paid Amount</span><span class="font-mono text-success">৳{{ number_format($invoice->paid_amount, 2) }}</span></div>
                <div class="flex justify-between font-bold text-sm border-t pt-1 dark:border-gray-700 {{ $invoice->due_amount > 0 ? 'text-danger' : 'text-success' }}"><span>Remaining Due</span><span class="font-mono">৳{{ number_format($invoice->due_amount, 2) }}</span></div>
            </div>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="flex items-center justify-between">
            <h5 class="text-base font-semibold">Payments Received</h5>
            <button type="button" onclick="document.getElementById('addPaymentForm').classList.toggle('hidden')" class="btn btn-sm btn-outline-success">+ Record Payment</button>
        </div>

        <div id="addPaymentForm" class="mb-5 mt-3 hidden rounded-lg border p-4 dark:border-gray-700">
            <form action="{{ route('admin.finance.invoices.payments.store', $invoice->id) }}" method="POST" class="flex flex-nowrap items-center gap-2 overflow-x-auto pb-1">
                @csrf
                <input type="number" step="0.01" name="amount" placeholder="Amount (৳) *" class="form-input w-36" required />
                <input type="date" name="payment_date" class="form-input w-36" required value="{{ date('Y-m-d') }}" />
                <input type="text" name="payment_method" placeholder="Method (e.g. Bank)" class="form-input w-36" />
                <select name="payment_account_id" class="form-select min-w-[180px]">
                    <option value="">Select Account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }} ({{ ucfirst($account->type) }})</option>
                    @endforeach
                </select>
                <input type="text" name="reference" placeholder="Reference #" class="form-input w-36" />
                <input type="text" name="notes" placeholder="Notes (optional)" class="form-input flex-1 min-w-[300px]" />
                <button type="submit" class="btn btn-success whitespace-nowrap">+ Record Payment</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto text-xs">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800 border-b dark:border-gray-700">
                        <th class="py-2.5 px-4 text-left">Date</th>
                        <th class="py-2.5 px-4 text-left">Amount</th>
                        <th class="py-2.5 px-4 text-left">Method</th>
                        <th class="py-2.5 px-4 text-left">Reference</th>
                        <th class="py-2.5 px-4 text-left">Notes</th>
                        <th class="text-center py-2.5 px-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoice->payments as $p)
                        <tr class="border-t dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="text-xs py-2.5 px-4">{{ $p->payment_date->format('d M Y') }}</td>
                            <td class="font-semibold text-success py-2.5 px-4 font-mono">৳{{ number_format($p->amount, 2) }}</td>
                            <td class="text-xs py-2.5 px-4">{{ $p->payment_method ?? '-' }}</td>
                            <td class="text-xs py-2.5 px-4 font-mono">{{ $p->reference ?? '-' }}</td>
                            <td class="text-xs py-2.5 px-4 text-white-dark">{{ $p->notes ?? '-' }}</td>
                            <td class="text-center py-2.5 px-4">
                                <form action="{{ route('admin.finance.invoices.payments.destroy', [$invoice->id, $p->id]) }}" method="POST" onsubmit="return confirm('Remove payment?');" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 py-1 font-bold" title="Remove payment">X</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4 text-gray-400">No payments recorded yet. Click "+ Record Payment" above.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
