@extends('admin.layouts.master')

@section('title', 'Receivable #' . $receivable->receivable_number)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Receivable #{{ $receivable->receivable_number }}</h2>
        <a href="{{ route('admin.finance.receivables.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div><span class="text-xs text-white-dark">Receivable #</span><p class="font-mono font-semibold">{{ $receivable->receivable_number }}</p></div>
            <div><span class="text-xs text-white-dark">Payer</span><p class="font-semibold">{{ $receivable->payer_name }}</p></div>
            <div><span class="text-xs text-white-dark">Total Amount</span><p class="font-mono font-semibold">৳ {{ number_format($receivable->amount, 2) }}</p></div>
            <div><span class="text-xs text-white-dark">Due Date</span><p>{{ $receivable->due_date->format('d M Y') }}</p></div>
            <div><span class="text-xs text-white-dark">Project</span><p>{{ $receivable->project?->name ?? '—' }}</p></div>
            <div><span class="text-xs text-white-dark">Paid</span><p class="font-mono text-success">৳ {{ number_format($receivable->paid_amount, 2) }}</p></div>
            <div><span class="text-xs text-white-dark">Due</span><p class="font-mono font-semibold {{ $receivable->due_amount > 0 ? 'text-danger' : 'text-success' }}">৳ {{ number_format($receivable->due_amount, 2) }}</p></div>
            <div>
                <span class="text-xs text-white-dark">Status</span>
                <p>@php $cls = match($receivable->status) { 'paid' => 'badge-outline-success', 'partial' => 'badge-outline-warning', 'overdue' => 'badge-outline-danger', default => 'badge-outline-secondary' }; @endphp
                <span class="badge {{ $cls }} capitalize">{{ $receivable->status }}</span></p>
            </div>
            @if($receivable->description)
                <div class="md:col-span-4"><span class="text-xs text-white-dark">Description</span><p>{{ $receivable->description }}</p></div>
            @endif
            @if($receivable->notes)
                <div class="md:col-span-4"><span class="text-xs text-white-dark">Notes</span><p>{{ $receivable->notes }}</p></div>
            @endif
        </div>

        @if($receivable->due_amount > 0)
            <div class="mt-6 border-t border-gray-200 pt-4 dark:border-gray-700">
                <h4 class="mb-3 text-base font-semibold">Record Payment</h4>
                <form action="{{ route('admin.finance.receivables.payments.store', $receivable) }}" method="POST">
                    @csrf
                    {{-- Line 1: Essential Payment Fields --}}
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label class="text-xs font-semibold mb-1 block">Amount (৳) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0.01" max="{{ $receivable->due_amount }}" name="amount" class="form-input text-xs" required placeholder="0.00" />
                        </div>
                        <div>
                            <label class="text-xs font-semibold mb-1 block">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" class="form-input text-xs" required value="{{ date('Y-m-d') }}" />
                        </div>
                        <div>
                            <label class="text-xs font-semibold mb-1 block">Payment Method</label>
                            <select name="payment_method" class="form-select text-xs">
                                <option value="">Select Method</option>
                                <option value="cash">Cash</option>
                                <option value="bank">Bank Transfer</option>
                                <option value="cheque">Cheque</option>
                                <option value="online">Online</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-semibold mb-1 block">Payment Account</label>
                            <select name="payment_account_id" class="form-select text-xs">
                                <option value="">Select Account</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }} (৳{{ number_format($account->current_balance, 2) }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Line 2: Reference, Notes & Action Button --}}
                    <div class="mt-3 flex flex-col md:flex-row items-end gap-3">
                        <div class="w-full md:w-1/4">
                            <label class="text-xs font-semibold mb-1 block">Reference #</label>
                            <input type="text" name="reference" class="form-input text-xs" placeholder="Ref / Txn #" />
                        </div>
                        <div class="w-full md:flex-1">
                            <label class="text-xs font-semibold mb-1 block">Notes</label>
                            <input type="text" name="notes" class="form-input text-xs" placeholder="Notes (optional)" />
                        </div>
                        <div class="w-full md:w-auto whitespace-nowrap">
                            <button type="submit" class="btn btn-primary w-full md:w-auto text-xs font-bold py-2.5 px-6">+ Add Payment</button>
                        </div>
                    </div>
                </form>
            </div>
        @endif

        @if($receivable->payments->count() > 0)
            <div class="mt-6">
                <h4 class="mb-3 text-base font-semibold">Payment History</h4>
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr><th>Date</th><th>Amount</th><th>Method</th><th>Reference</th><th>Notes</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach($receivable->payments as $pmt)
                            <tr>
                                <td>{{ $pmt->payment_date->format('d M Y') }}</td>
                                <td class="font-mono">৳ {{ number_format($pmt->amount, 2) }}</td>
                                <td class="capitalize">{{ $pmt->payment_method ?? '—' }}</td>
                                <td>{{ $pmt->reference ?? '—' }}</td>
                                <td class="text-xs">{{ $pmt->notes ?? '—' }}</td>
                                <td>
                                    <form action="{{ route('admin.finance.receivables.payments.destroy', [$receivable, $pmt]) }}" method="POST" onsubmit="return confirm('Remove payment?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">×</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
