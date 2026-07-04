@extends('admin.layouts.master')

@section('title', 'General Ledger')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">General Ledger</h5>
    </div>

    <form method="GET" class="mb-4 flex items-center gap-2">
        <select name="account_id" class="form-select flex-1">
            <option value="">All Accounts</option>
            @foreach($accounts as $acc)
                <option value="{{ $acc->id }}" {{ request('account_id') == $acc->id ? 'selected' : '' }}>
                    {{ $acc->account_code }} - {{ $acc->name }}
                </option>
            @endforeach
        </select>
        <label class="text-xs whitespace-nowrap">From:</label>
        <input type="date" name="from" class="form-input flex-1" value="{{ request('from') }}" />
        <label class="text-xs whitespace-nowrap">To:</label>
        <input type="date" name="to" class="form-input flex-1" value="{{ request('to') }}" />
        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->anyFilled(['account_id', 'from', 'to']))
            <a href="{{ route('admin.finance.general-ledger.index') }}" class="btn btn-outline-danger">Reset</a>
        @endif
    </form>

    @forelse($ledger as $entry)
        <div class="mb-6">
            <div class="flex items-center justify-between rounded-t-md bg-gray-100 px-4 py-2 dark:bg-[#1b2e4b]">
                <div>
                    <span class="font-semibold">{{ $entry['account']->account_code }}</span>
                    <span class="ml-2">{{ $entry['account']->name }}</span>
                    <span class="ml-2 text-xs capitalize badge badge-outline-primary">{{ $entry['account']->type }}</span>
                </div>
                <div class="text-sm">
                    <span class="mr-4">Debit: <span class="font-mono text-success">{{ number_format($entry['total_debit'], 2) }}</span></span>
                    <span class="mr-4">Credit: <span class="font-mono text-danger">{{ number_format($entry['total_credit'], 2) }}</span></span>
                    <span>Balance: <span class="font-mono font-semibold {{ $entry['closing_balance'] >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($entry['closing_balance'], 2) }}</span></span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Journal #</th>
                            <th>Description</th>
                            <th class="text-right">Debit (৳)</th>
                            <th class="text-right">Credit (৳)</th>
                            <th class="text-right">Balance (৳)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($entry['lines']) > 0 && request('from'))
                            <tr class="bg-gray-50 dark:bg-[#0e1a2b]">
                                <td colspan="5" class="text-right text-xs text-white-dark">Opening Balance</td>
                                @php $openingBalance = $entry['closing_balance'] - array_sum(array_column($entry['lines'], 'debit')) + array_sum(array_column($entry['lines'], 'credit')); @endphp
                                <td class="text-right font-mono text-xs {{ $openingBalance >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($openingBalance, 2) }}</td>
                            </tr>
                        @endif
                        @foreach($entry['lines'] as $line)
                            <tr>
                                <td>{{ $line['date']->format('d M Y') }}</td>
                                <td class="font-mono text-xs">{{ $line['journal_number'] }}</td>
                                <td class="text-xs">{{ $line['description'] ?? '—' }}</td>
                                <td class="text-right font-mono {{ $line['debit'] > 0 ? 'text-success' : '' }}">{{ $line['debit'] > 0 ? number_format($line['debit'], 2) : '—' }}</td>
                                <td class="text-right font-mono {{ $line['credit'] > 0 ? 'text-danger' : '' }}">{{ $line['credit'] > 0 ? number_format($line['credit'], 2) : '—' }}</td>
                                <td class="text-right font-mono font-semibold {{ $line['balance'] >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($line['balance'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="py-8 text-center text-gray-500">No journal entries found. Post some journal vouchers first.</div>
    @endforelse
</div>
@endsection
