@extends('admin.layouts.master')

@section('title', 'Payment Accounts')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Payment Accounts</h2>
        <div class="flex w-full flex-wrap items-center justify-end gap-4 sm:w-auto">
            <a href="{{ route('admin.finance.payment-accounts.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                New Account
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-5 flex items-center justify-between">
            <div>
                <span class="text-sm text-white-dark">Total Balance:</span>
                <span class="ml-2 text-lg font-bold text-success">{{ number_format($totalBalance, 2) }}</span>
            </div>
            <div class="flex gap-2 min-w-[200px]">
                <select onchange="window.location.href='?type='+this.value" class="form-select text-xs">
                    <option value="">All Types</option>
                    <option value="bank" {{ request('type') == 'bank' ? 'selected' : '' }}>Bank</option>
                    <option value="mfs" {{ request('type') == 'mfs' ? 'selected' : '' }}>MFS</option>
                    <option value="cash" {{ request('type') == 'cash' ? 'selected' : '' }}>Cash</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Account Name</th>
                        <th>Type</th>
                        <th>Account No.</th>
                        <th>Bank / Provider</th>
                        <th class="text-right">Opening Balance</th>
                        <th class="text-right">Current Balance</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accounts as $account)
                        <tr>
                            <td class="text-xs font-semibold">{{ $account->name }}</td>
                            <td><span class="badge badge-outline-info text-xs">{{ $account->type_label }}</span></td>
                            <td class="text-xs">{{ $account->account_number ?? '—' }}</td>
                            <td class="text-xs">{{ $account->bank_name ?? '—' }}</td>
                            <td class="text-xs text-right">{{ number_format($account->opening_balance, 2) }}</td>
                            <td class="text-right">
                                <span class="font-bold {{ $account->current_balance >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($account->current_balance, 2) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $account->status == 'active' ? 'badge-outline-success' : 'badge-outline-secondary' }} capitalize">{{ $account->status }}</span>
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('admin.finance.payment-accounts.show', $account->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                    <a href="{{ route('admin.finance.payment-accounts.pdf', $account->id) }}" target="_blank" class="btn btn-sm btn-outline-success">PDF</a>
                                    <a href="{{ route('admin.finance.payment-accounts.edit', $account->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('admin.finance.payment-accounts.destroy', $account->id) }}" method="POST" onsubmit="return confirm('Delete this account?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No payment accounts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
