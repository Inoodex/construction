@extends('admin.layouts.master')

@section('title', 'Trial Balance')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Trial Balance</h2>
            <p class="text-xs text-white-dark mt-0.5">As of {{ \Carbon\Carbon::parse($asOf)->format('d F Y') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.finance.trial-balance.pdf', ['date' => $asOf]) }}" target="_blank" class="btn btn-outline-success gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                PDF Export
            </a>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="panel mt-6">
        <form method="GET" class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-xs font-semibold text-white-dark whitespace-nowrap">As of Date:</span>
                <input type="date" name="date" class="form-input w-48" value="{{ $asOf }}" />
                <button type="submit" class="btn btn-primary">Run Report</button>
            </div>
            <div class="flex items-center gap-2">
                @if(abs($totalDebit - $totalCredit) < 0.01)
                    <span class="badge badge-outline-success text-sm py-1.5 px-3 flex items-center gap-1.5">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        BALANCED LEDGER
                    </span>
                @else
                    <span class="badge badge-outline-danger text-sm py-1.5 px-3 flex items-center gap-1.5">
                        ⚠ UNBALANCED (Diff: ৳{{ number_format(abs($totalDebit - $totalCredit), 2) }})
                    </span>
                @endif
            </div>
        </form>
    </div>

    {{-- Top Metric Cards --}}
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark uppercase font-semibold">Total Debits</label>
            <p class="text-2xl font-extrabold text-success mt-1">৳{{ number_format($totalDebit, 2) }}</p>
            <span class="text-[11px] text-white-dark mt-0.5">Sum of All Debit Balances</span>
        </div>
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark uppercase font-semibold">Total Credits</label>
            <p class="text-2xl font-extrabold text-danger mt-1">৳{{ number_format($totalCredit, 2) }}</p>
            <span class="text-[11px] text-white-dark mt-0.5">Sum of All Credit Balances</span>
        </div>
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark uppercase font-semibold">Ledger Variance</label>
            @if(abs($totalDebit - $totalCredit) < 0.01)
                <p class="text-2xl font-extrabold text-success mt-1">৳0.00</p>
                <span class="text-[11px] text-success font-semibold mt-0.5">✓ Perfect Double-Entry Match</span>
            @else
                <p class="text-2xl font-extrabold text-danger mt-1">৳{{ number_format(abs($totalDebit - $totalCredit), 2) }}</p>
                <span class="text-[11px] text-danger font-semibold mt-0.5">⚠ Out of Balance</span>
            @endif
        </div>
    </div>

    {{-- Trial Balance Table --}}
    <div class="panel mt-6">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-sm font-bold uppercase text-primary">Trial Balance Ledger Accounts</h5>
            <span class="badge badge-outline-primary text-xs">{{ count($rows) }} Accounts</span>
        </div>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto text-xs">
                <thead>
                    <tr class="border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        <th class="py-3 px-4 text-left">Code</th>
                        <th class="py-3 px-4 text-left">Account Name</th>
                        <th class="py-3 px-4 text-left">Account Type</th>
                        <th class="py-3 px-4 text-right">Debit (৳)</th>
                        <th class="py-3 px-4 text-right">Credit (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        @php
                            $badgeCls = match(strtolower($row['type'])) {
                                'asset', 'expense' => 'badge-outline-success',
                                'liability', 'equity' => 'badge-outline-info',
                                'revenue', 'income' => 'badge-outline-primary',
                                default => 'badge-outline-secondary'
                            };
                        @endphp
                        <tr class="border-t dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="py-3 px-4 font-mono font-bold text-primary">{{ $row['code'] }}</td>
                            <td class="py-3 px-4 font-semibold text-sm">{{ $row['name'] }}</td>
                            <td class="py-3 px-4">
                                <span class="badge {{ $badgeCls }} capitalize">{{ str_replace('_', ' ', $row['type']) }}</span>
                            </td>
                            <td class="py-3 px-4 text-right font-mono text-sm {{ $row['debit'] > 0 ? 'text-success font-bold' : 'text-gray-400' }}">
                                {{ $row['debit'] > 0 ? number_format($row['debit'], 2) : '—' }}
                            </td>
                            <td class="py-3 px-4 text-right font-mono text-sm {{ $row['credit'] > 0 ? 'text-danger font-bold' : 'text-gray-400' }}">
                                {{ $row['credit'] > 0 ? number_format($row['credit'], 2) : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-gray-500 py-6">No transactions posted as of this date.</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="border-t-2 dark:border-gray-600 font-extrabold bg-gray-100 dark:bg-gray-800 text-sm">
                        <td colspan="3" class="py-3.5 px-4 text-right uppercase">Total Trial Balance</td>
                        <td class="py-3.5 px-4 text-right font-mono text-success">৳{{ number_format($totalDebit, 2) }}</td>
                        <td class="py-3.5 px-4 text-right font-mono text-danger">৳{{ number_format($totalCredit, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
