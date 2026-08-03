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
                                <x-action-dropdown>
                                    <x-slot name="items">
                                        <li><a href="{{ route('admin.finance.payment-accounts.show', $account->id) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                        <li><a href="{{ route('admin.finance.payment-accounts.pdf', $account->id) }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> PDF</a></li>
                                        <li><a href="{{ route('admin.finance.payment-accounts.edit', $account->id) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/></svg> Edit</a></li>
                                        <li class="divider"></li>
                                        <li class="danger">
                                            <form action="{{ route('admin.finance.payment-accounts.destroy', $account->id) }}" method="POST" onsubmit="return confirm('Delete this account?');">
                                                @csrf @method('DELETE')
                                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 001-1h4a1 1 0 001 1m-6 0h6"/></svg> Delete</button>
                                            </form>
                                        </li>
                                    </x-slot>
                                </x-action-dropdown>
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
