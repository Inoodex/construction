@extends('admin.layouts.master')

@section('title', 'Account Ledger - ' . $paymentAccount->name)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">{{ $paymentAccount->name }}</h2>
            <p class="text-xs text-white-dark mt-1">{{ $paymentAccount->type_label }} {{ $paymentAccount->account_number ? '— ' . $paymentAccount->account_number : '' }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.finance.payment-accounts.pdf', $paymentAccount->id) }}" target="_blank" class="btn btn-sm btn-outline-success">PDF Ledger</a>
            <a href="{{ route('admin.finance.payment-accounts.edit', $paymentAccount->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
            <a href="{{ route('admin.finance.payment-accounts.index') }}" class="btn btn-sm btn-outline-secondary">Back to List</a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 mt-6 md:grid-cols-3">
        <div class="panel">
            <p class="text-xs text-white-dark">Opening Balance</p>
            <p class="text-lg font-bold mt-1">{{ number_format($paymentAccount->opening_balance, 2) }}</p>
        </div>
        <div class="panel">
            <p class="text-xs text-white-dark">Current Balance</p>
            <p class="text-lg font-bold mt-1 {{ $paymentAccount->current_balance >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($paymentAccount->current_balance, 2) }}</p>
        </div>
        <div class="panel">
            <p class="text-xs text-white-dark">Total Transactions</p>
            <p class="text-lg font-bold mt-1">{{ $paymentAccount->transactions()->count() }}</p>
        </div>
    </div>

    <div class="panel mt-6">
        <h3 class="mb-4 text-base font-semibold">Transaction History</h3>
        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Reference</th>
                        <th class="text-right">Credit (In)</th>
                        <th class="text-right">Debit (Out)</th>
                        <th class="text-right">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                        <tr>
                            <td class="text-xs">{{ $t->transaction_date->format('d M Y H:i') }}</td>
                            <td class="text-xs">{{ $t->description }}</td>
                            <td class="text-xs font-mono">{{ $t->reference ?? '—' }}</td>
                            <td class="text-xs text-right text-success font-semibold">
                                {{ $t->type === 'credit' ? number_format($t->amount, 2) : '' }}
                            </td>
                            <td class="text-xs text-right text-danger font-semibold">
                                {{ $t->type === 'debit' ? number_format($t->amount, 2) : '' }}
                            </td>
                            <td class="text-xs text-right font-bold">{{ number_format($t->balance_after, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No transactions yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
@endsection
