@extends('admin.layouts.master')

@section('title', 'Bill Details - ' . $bill->bill_number)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Vendor Bill: {{ $bill->bill_number }}</h2>
            <p class="text-xs text-white-dark mt-0.5">Vendor: {{ $bill->vendor->name ?? 'N/A' }} — {{ $bill->project->name ?? 'N/A' }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.finance.bills.edit', $bill->id) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit
            </a>
            <a href="{{ route('admin.finance.bills.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to List
            </a>
        </div>
    </div>

    @php $sc = ['draft' => 'badge-outline-secondary', 'approved' => 'badge-outline-primary', 'paid' => 'badge-outline-success', 'overdue' => 'badge-outline-danger', 'cancelled' => 'badge-outline-dark']; @endphp

    {{-- Top Metadata Panels --}}
    <div class="mt-6 grid gap-6 sm:grid-cols-3 lg:grid-cols-5">
        <div class="panel">
            <label class="text-xs text-white-dark uppercase font-semibold">Bill Number</label>
            <p class="font-mono font-bold text-primary text-base">{{ $bill->bill_number }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark uppercase font-semibold">Vendor</label>
            <p class="font-semibold text-sm">{{ $bill->vendor->name ?? 'N/A' }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark uppercase font-semibold">Project</label>
            <p class="font-semibold text-sm">{{ $bill->project->name ?? 'N/A' }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark uppercase font-semibold">Reference</label>
            <p class="font-mono text-xs font-medium">{{ $bill->reference ?? '—' }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark uppercase font-semibold">Status</label>
            <p><span class="badge {{ $sc[$bill->status] ?? 'badge-outline-secondary' }} capitalize text-xs px-2.5 py-1">{{ $bill->status }}</span></p>
        </div>
    </div>

    {{-- Financial Summary Grid --}}
    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark uppercase font-semibold">Bill Date</label>
            <p class="text-base font-bold mt-1">{{ $bill->bill_date->format('d M Y') }}</p>
        </div>
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark uppercase font-semibold">Due Date</label>
            <p class="text-base font-bold mt-1 {{ $bill->due_amount > 0 && $bill->due_date->isPast() ? 'text-danger' : '' }}">
                {{ $bill->due_date->format('d M Y') }}
            </p>
        </div>
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark uppercase font-semibold">Total Amount</label>
            <p class="text-2xl font-extrabold text-primary mt-1">৳{{ number_format($bill->total_amount, 2) }}</p>
        </div>
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark uppercase font-semibold">Remaining Due</label>
            <p class="text-2xl font-extrabold {{ $bill->due_amount > 0 ? 'text-danger' : 'text-success' }} mt-1">৳{{ number_format($bill->due_amount, 2) }}</p>
        </div>
    </div>

    {{-- Bill Items Section --}}
    <div class="panel mt-6">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-base font-semibold uppercase">Bill Items Breakdown</h5>
            <button type="button" onclick="document.getElementById('addItemForm').classList.toggle('hidden')" class="btn btn-sm btn-outline-primary">+ Add Item</button>
        </div>

        <div id="addItemForm" class="mb-5 mt-3 hidden rounded-lg border p-4 bg-gray-50 dark:bg-gray-800/40 dark:border-gray-700">
            <form action="{{ route('admin.finance.bills.items.store', $bill->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <input type="text" name="description" placeholder="Item Description / Expense Category *" class="form-input w-full" required />
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
                    @forelse($bill->items as $item)
                        <tr class="border-t dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="text-xs py-2.5 px-4 font-semibold">{{ $item->description }}</td>
                            <td class="text-xs py-2.5 px-4 font-mono">{{ number_format($item->quantity, 2) }}</td>
                            <td class="text-xs py-2.5 px-4 font-mono">৳{{ number_format($item->unit_price, 2) }}</td>
                            <td class="font-semibold py-2.5 px-4 text-primary font-mono">৳{{ number_format($item->total_price, 2) }}</td>
                            <td class="text-center py-2.5 px-4">
                                <form action="{{ route('admin.finance.bills.items.destroy', [$bill->id, $item->id]) }}" method="POST" onsubmit="return confirm('Remove this item?');" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 py-1 font-bold" title="Remove item">X</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4 text-gray-400">No items added to this bill yet. Click "+ Add Item" above.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-end">
            <div class="w-full max-w-sm space-y-2 rounded-lg border border-gray-200 dark:border-gray-700 p-4 bg-gray-50 dark:bg-gray-800/40">
                <div class="flex justify-between text-xs"><span>Subtotal</span><span class="font-mono font-semibold">৳{{ number_format($bill->subtotal, 2) }}</span></div>
                <div class="flex justify-between text-xs"><span>Tax ({{ $bill->tax_rate }}%)</span><span class="font-mono font-semibold">৳{{ number_format($bill->tax_amount, 2) }}</span></div>
                <div class="flex justify-between font-bold text-sm border-t pt-2 dark:border-gray-700"><span>Total Payable</span><span class="font-mono text-primary">৳{{ number_format($bill->total_amount, 2) }}</span></div>
                <div class="flex justify-between text-xs font-semibold"><span>Paid Amount</span><span class="font-mono text-success">৳{{ number_format($bill->paid_amount, 2) }}</span></div>
                <div class="flex justify-between font-bold text-sm border-t pt-1 dark:border-gray-700 {{ $bill->due_amount > 0 ? 'text-danger' : 'text-success' }}"><span>Remaining Due</span><span class="font-mono">৳{{ number_format($bill->due_amount, 2) }}</span></div>
            </div>
        </div>
    </div>

    {{-- Payments Section --}}
    <div class="panel mt-6">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-base font-semibold uppercase">Payments Made</h5>
            <button type="button" onclick="document.getElementById('addPaymentForm').classList.toggle('hidden')" class="btn btn-sm btn-outline-success">+ Record Payment</button>
        </div>

        <div id="addPaymentForm" class="mb-5 mt-3 hidden rounded-lg border p-4 bg-gray-50 dark:bg-gray-800/40 dark:border-gray-700">
            <form action="{{ route('admin.finance.bills.payments.store', $bill->id) }}" method="POST" class="flex flex-nowrap items-center gap-2 overflow-x-auto pb-1">
                @csrf
                <input type="number" step="0.01" name="amount" placeholder="Amount (৳) *" class="form-input w-36" required />
                <input type="date" name="payment_date" class="form-input w-36" required value="{{ now()->format('Y-m-d') }}" />
                <input type="text" name="payment_method" placeholder="Method (e.g. Bank)" class="form-input w-36" />
                <select name="payment_account_id" class="form-select min-w-[180px]">
                    <option value="">Select Account</option>
                    @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                    @endforeach
                </select>
                <input type="text" name="reference" placeholder="Reference #" class="form-input w-36" />
                <input type="text" name="notes" placeholder="Notes (optional)" class="form-input flex-1 min-w-[280px]" />
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
                    @forelse($bill->payments as $payment)
                        <tr class="border-t dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="text-xs py-2.5 px-4">{{ $payment->payment_date->format('d M Y') }}</td>
                            <td class="font-semibold text-success py-2.5 px-4 font-mono">৳{{ number_format($payment->amount, 2) }}</td>
                            <td class="text-xs py-2.5 px-4">{{ $payment->payment_method ?? '-' }}</td>
                            <td class="text-xs py-2.5 px-4 font-mono">{{ $payment->reference ?? '-' }}</td>
                            <td class="text-xs py-2.5 px-4 text-white-dark">{{ $payment->notes ?? '-' }}</td>
                            <td class="text-center py-2.5 px-4">
                                <form action="{{ route('admin.finance.bills.payments.destroy', [$bill->id, $payment->id]) }}" method="POST" onsubmit="return confirm('Remove this payment?');" class="inline">
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

    @if($bill->notes)
        <div class="panel mt-4">
            <h5 class="mb-2 text-sm font-semibold uppercase text-white-dark">Bill Notes</h5>
            <p class="text-xs text-white-dark leading-relaxed">{{ $bill->notes }}</p>
        </div>
    @endif
@endsection
