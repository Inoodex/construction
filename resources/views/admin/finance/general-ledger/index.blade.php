@extends('admin.layouts.master')

@section('title', 'General Ledger')

@section('content')
<div class="panel">
    <div class="mb-5 flex flex-wrap items-center justify-between gap-4">
        <h5 class="text-lg font-semibold dark:text-white-light">General Ledger</h5>
        <div class="flex items-center gap-2">
            <button type="button" onclick="expandAllAccounts()" class="btn btn-sm btn-outline-primary">Expand All</button>
            <button type="button" onclick="collapseAllAccounts()" class="btn btn-sm btn-outline-secondary">Collapse All</button>
        </div>
    </div>

    <form method="GET" class="mb-5 flex flex-nowrap items-center gap-3 w-full overflow-x-auto pb-1">
        <div class="w-64">
            <select name="account_id" class="form-select w-full">
                <option value="">All Accounts</option>
                @foreach($accounts as $acc)
                    <option value="{{ $acc->id }}" {{ request('account_id') == $acc->id ? 'selected' : '' }}>
                        {{ $acc->account_code }} - {{ $acc->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center gap-2 whitespace-nowrap">
            <span class="text-xs font-semibold text-white-dark">From:</span>
            <input type="date" name="from" class="form-input w-40" value="{{ request('from') }}" />
        </div>
        <div class="flex items-center gap-2 whitespace-nowrap">
            <span class="text-xs font-semibold text-white-dark">To:</span>
            <input type="date" name="to" class="form-input w-40" value="{{ request('to') }}" />
        </div>
        <div class="flex items-center gap-2 whitespace-nowrap">
            <button type="submit" class="btn btn-primary">Filter</button>
            @if(request()->anyFilled(['account_id', 'from', 'to']))
                <a href="{{ route('admin.finance.general-ledger.index') }}" class="btn btn-outline-danger">Reset</a>
            @endif
        </div>
    </form>

    @forelse($ledger as $index => $entry)
        <div class="mb-4 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
            {{-- Clickable Header --}}
            <div onclick="toggleLedgerAccount({{ $index }})" class="flex flex-wrap items-center justify-between bg-gray-100 dark:bg-[#1b2e4b] px-4 py-3 cursor-pointer select-none hover:bg-gray-200 dark:hover:bg-[#253b5c] transition-colors">
                <div class="flex items-center gap-3">
                    <svg id="chevron-{{ $index }}" class="h-4 w-4 transform transition-transform duration-200 {{ $index === 0 ? 'rotate-180' : '' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    <span class="font-mono font-bold text-primary">{{ $entry['account']->account_code }}</span>
                    <span class="font-semibold text-sm">{{ $entry['account']->name }}</span>
                    <span class="text-[11px] capitalize badge badge-outline-primary">{{ $entry['account']->type }}</span>
                    <span class="text-xs text-white-dark">({{ count($entry['lines']) }} transactions)</span>
                </div>
                <div class="flex items-center gap-4 text-xs font-medium">
                    <span>Debit: <span class="font-mono text-success font-semibold">{{ number_format($entry['total_debit'], 2) }}</span></span>
                    <span>Credit: <span class="font-mono text-danger font-semibold">{{ number_format($entry['total_credit'], 2) }}</span></span>
                    <span>Balance: <span class="font-mono font-bold {{ $entry['closing_balance'] >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($entry['closing_balance'], 2) }}</span></span>
                </div>
            </div>

            {{-- Collapsible Table Content --}}
            <div id="content-{{ $index }}" class="overflow-x-auto {{ $index === 0 ? '' : 'hidden' }}">
                <table class="table-hover w-full table-auto text-xs">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2.5 px-4">Date</th>
                            <th class="py-2.5 px-4">Journal #</th>
                            <th class="py-2.5 px-4">Description</th>
                            <th class="py-2.5 px-4 text-right">Debit (৳)</th>
                            <th class="py-2.5 px-4 text-right">Credit (৳)</th>
                            <th class="py-2.5 px-4 text-right">Balance (৳)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($entry['lines']) > 0 && request('from'))
                            <tr class="bg-gray-50 dark:bg-[#0e1a2b] font-medium">
                                <td colspan="5" class="py-2 px-4 text-right text-xs text-white-dark">Opening Balance</td>
                                @php $openingBalance = $entry['closing_balance'] - array_sum(array_column($entry['lines'], 'debit')) + array_sum(array_column($entry['lines'], 'credit')); @endphp
                                <td class="py-2 px-4 text-right font-mono text-xs font-bold {{ $openingBalance >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($openingBalance, 2) }}</td>
                            </tr>
                        @endif
                        @foreach($entry['lines'] as $line)
                            <tr class="border-t border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="py-2.5 px-4">{{ $line['date']->format('d M Y') }}</td>
                                <td class="py-2.5 px-4 font-mono font-semibold text-primary">{{ $line['journal_number'] }}</td>
                                <td class="py-2.5 px-4 text-xs">{{ $line['description'] ?? '—' }}</td>
                                <td class="py-2.5 px-4 text-right font-mono {{ $line['debit'] > 0 ? 'text-success font-semibold' : '' }}">{{ $line['debit'] > 0 ? number_format($line['debit'], 2) : '—' }}</td>
                                <td class="py-2.5 px-4 text-right font-mono {{ $line['credit'] > 0 ? 'text-danger font-semibold' : '' }}">{{ $line['credit'] > 0 ? number_format($line['credit'], 2) : '—' }}</td>
                                <td class="py-2.5 px-4 text-right font-mono font-bold {{ $line['balance'] >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($line['balance'], 2) }}</td>
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

<script>
    function toggleLedgerAccount(index) {
        const content = document.getElementById(`content-${index}`);
        const chevron = document.getElementById(`chevron-${index}`);
        if (!content || !chevron) return;

        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            chevron.classList.add('rotate-180');
        } else {
            content.classList.add('hidden');
            chevron.classList.remove('rotate-180');
        }
    }

    function expandAllAccounts() {
        document.querySelectorAll('[id^="content-"]').forEach(el => el.classList.remove('hidden'));
        document.querySelectorAll('[id^="chevron-"]').forEach(el => el.classList.add('rotate-180'));
    }

    function collapseAllAccounts() {
        document.querySelectorAll('[id^="content-"]').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('[id^="chevron-"]').forEach(el => el.classList.remove('rotate-180'));
    }
</script>
@endsection
