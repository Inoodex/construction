@extends('admin.layouts.master')

@section('title', 'Bill Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Bill: {{ $bill->bill_number }}</h2>
        <div class="flex gap-2">
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

    <div class="mt-6 grid gap-6 sm:grid-cols-3 lg:grid-cols-5">
        <div class="panel">
            <label class="text-xs text-white-dark">Bill Number</label>
            <p class="font-mono font-semibold text-primary">{{ $bill->bill_number }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Vendor</label>
            <p class="font-semibold">{{ $bill->vendor->name ?? 'N/A' }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Project</label>
            <p class="font-semibold">{{ $bill->project->name ?? 'N/A' }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Reference</label>
            <p class="font-mono text-xs">{{ $bill->reference ?? '-' }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Status</label>
            <p><span class="badge {{ $sc[$bill->status] }} capitalize">{{ $bill->status }}</span></p>
        </div>
    </div>

    <div class="mt-4 grid gap-4 sm:grid-cols-3 lg:grid-cols-4">
        <div class="panel"><label class="text-xs text-white-dark">Bill Date</label><p>{{ $bill->bill_date->format('d M Y') }}</p></div>
        <div class="panel"><label class="text-xs text-white-dark">Due Date</label><p class="{{ $bill->due_amount > 0 && $bill->due_date->isPast() ? 'text-danger' : '' }}">{{ $bill->due_date->format('d M Y') }}</p></div>
        <div class="panel"><label class="text-xs text-white-dark">Total Amount</label><p class="text-lg font-bold text-primary">{{ number_format($bill->total_amount) }}</p></div>
        <div class="panel"><label class="text-xs text-white-dark">Due Amount</label><p class="text-lg font-bold {{ $bill->due_amount > 0 ? 'text-danger' : 'text-success' }}">{{ number_format($bill->due_amount) }}</p></div>
    </div>

    <div class="panel mt-6">
        <div class="flex items-center justify-between">
            <h5 class="text-base font-semibold">Bill Items</h5>
            <button type="button" onclick="document.getElementById('addItemForm').classList.toggle('hidden')" class="btn btn-sm btn-outline-primary">+ Add Item</button>
        </div>

        <div id="addItemForm" class="mb-5 mt-3 hidden rounded-lg border p-4 dark:border-gray-700">
            <form action="{{ route('admin.finance.bills.items.store', $bill->id) }}" method="POST">
                @csrf
                <table class="w-full" style="table-layout: fixed;">
                    <tr>
                        <td style="width:40%"><input type="text" name="description" placeholder="Description" class="form-input" required /></td>
                        <td style="width:17%"><input type="number" step="0.0001" name="quantity" placeholder="Quantity" class="form-input" required /></td>
                        <td style="width:17%"><input type="number" step="0.01" name="unit_price" placeholder="Unit Price" class="form-input" required /></td>
                        <td style="width:13%"><button type="submit" class="btn btn-primary w-full">Add</button></td>
                    </tr>
                </table>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead><tr><th>Description</th><th>Qty</th><th>Unit Price</th><th>Total</th><th>Action</th></tr></thead>
                <tbody>
                    @forelse($bill->items as $item)
                        <tr>
                            <td class="text-xs">{{ $item->description }}</td>
                            <td class="text-xs">{{ number_format($item->quantity, 2) }}</td>
                            <td class="text-xs">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="font-semibold">{{ number_format($item->total_price, 2) }}</td>
                            <td class="text-center">
                                <form action="{{ route('admin.finance.bills.items.destroy', [$bill->id, $item->id]) }}" method="POST" onsubmit="return confirm('Remove this item?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">No items yet.</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr><td colspan="5" class="pb-3"></td></tr>
                    <tr class="text-sm">
                        <td colspan="2"></td>
                        <td class="text-right text-white-dark pr-20">Subtotal:</td>
                        <td class="font-semibold">{{ number_format($bill->subtotal) }}</td><td></td>
                    </tr>
                    <tr class="text-sm">
                        <td colspan="2"></td>
                        <td class="text-right text-white-dark pr-20">Tax({{ $bill->tax_rate }}%):</td>
                        <td class="font-semibold">{{ number_format($bill->tax_amount) }}</td><td></td>
                    </tr>
                    <tr class="border-t border-gray-300 dark:border-gray-600">
                        <td colspan="2"></td>
                        <td class="pt-2 text-right text-base font-bold pr-20">Total:</td>
                        <td class="pt-2 text-base font-bold text-primary">{{ number_format($bill->total_amount) }}</td><td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="panel mt-4">
        <div class="flex items-center justify-between">
            <h5 class="text-base font-semibold">Payments</h5>
            <button type="button" onclick="document.getElementById('addPaymentForm').classList.toggle('hidden')" class="btn btn-sm btn-outline-success">+ Record Payment</button>
        </div>

        <div id="addPaymentForm" class="mb-5 mt-3 hidden rounded-lg border p-4 dark:border-gray-700">
            <form action="{{ route('admin.finance.bills.payments.store', $bill->id) }}" method="POST">
                @csrf
                <table class="w-full" style="table-layout: fixed;">
                    <tr>
                        <td style="width:14%"><input type="number" step="0.01" name="amount" placeholder="Amount" class="form-input" required /></td>
                        <td style="width:14%"><input type="date" name="payment_date" class="form-input" required value="{{ now()->format('Y-m-d') }}" /></td>
                        <td style="width:14%"><input type="text" name="payment_method" placeholder="Method" class="form-input" /></td>
                        <td style="width:18%"><select name="payment_account_id" class="form-select"><option value="">Account</option>@foreach($accounts as $acc)<option value="{{ $acc->id }}">{{ $acc->name }}</option>@endforeach</select></td>
                        <td style="width:14%"><input type="text" name="reference" placeholder="Ref #" class="form-input" /></td>
                        <td style="width:12%"><button type="submit" class="btn btn-success w-full">Record</button></td>
                    </tr>
                </table>
                <div class="mt-2"><textarea name="notes" class="form-input" rows="2" placeholder="Notes (optional)"></textarea></div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead><tr><th>Date</th><th>Amount</th><th>Method</th><th>Reference</th><th>Notes</th><th class="text-center">Action</th></tr></thead>
                <tbody>
                    @forelse($bill->payments as $payment)
                        <tr>
                            <td class="text-xs">{{ $payment->payment_date->format('d/m/Y') }}</td>
                            <td class="font-semibold text-success">{{ number_format($payment->amount) }}</td>
                            <td class="text-xs">{{ $payment->payment_method ?? '-' }}</td>
                            <td class="text-xs font-mono">{{ $payment->reference ?? '-' }}</td>
                            <td class="text-xs">{{ $payment->notes ?? '-' }}</td>
                            <td class="text-center">
                                <form action="{{ route('admin.finance.bills.payments.destroy', [$bill->id, $payment->id]) }}" method="POST" onsubmit="return confirm('Remove this payment?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No payments recorded.</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="font-bold"><td class="text-right">Total Paid</td><td class="text-success">{{ number_format($bill->paid_amount) }}</td><td colspan="4"></td></tr>
                </tfoot>
            </table>
        </div>
    </div>

    @if($bill->notes)
        <div class="panel mt-4">
            <h5 class="mb-2 text-base font-semibold">Notes</h5>
            <p class="text-xs text-white-dark">{{ $bill->notes }}</p>
        </div>
    @endif
@endsection
