@extends('admin.layouts.master')

@section('title', 'Balance Sheet')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Balance Sheet Statement</h2>
            <p class="text-xs text-white-dark mt-0.5">As of {{ \Carbon\Carbon::parse($asOf)->format('d F Y') }} — Financial Position Statement</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.finance.balance-sheet.pdf', ['date' => $asOf]) }}" target="_blank" class="btn btn-outline-success gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                PDF
            </a>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="panel mt-6">
        <form method="GET" class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-xs font-semibold text-white-dark whitespace-nowrap">As of Date:</span>
                <input type="date" name="date" class="form-input w-48" value="{{ $asOf }}" />
                <button type="submit" class="btn btn-primary">Run Statement</button>
            </div>
            <div class="flex items-center gap-2">
                @if(abs($difference) < 0.01)
                    <span class="badge badge-outline-success text-sm py-1.5 px-3 flex items-center gap-1.5">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        BALANCED STATEMENT (Assets = Liabilities + Equity)
                    </span>
                @else
                    <span class="badge badge-outline-danger text-sm py-1.5 px-3 flex items-center gap-1.5">
                        ⚠ UNBALANCED STATEMENT (Diff: ৳{{ number_format(abs($difference), 2) }})
                    </span>
                @endif
            </div>
        </form>
    </div>

    {{-- Top Metric Cards --}}
    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark uppercase font-semibold">Total Assets</label>
            <p class="text-2xl font-extrabold text-primary mt-1">৳{{ number_format($totalAssets, 2) }}</p>
            <span class="text-[11px] text-white-dark mt-0.5">Current & Fixed Assets</span>
        </div>
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark uppercase font-semibold">Total Liabilities</label>
            <p class="text-2xl font-extrabold text-danger mt-1">৳{{ number_format($totals['liability'], 2) }}</p>
            <span class="text-[11px] text-white-dark mt-0.5">Payables & Obligations</span>
        </div>
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark uppercase font-semibold">Owner's Equity</label>
            <p class="text-2xl font-extrabold text-info mt-1">৳{{ number_format($totals['equity'], 2) }}</p>
            <span class="text-[11px] text-white-dark mt-0.5">Capital & Retained Earnings</span>
        </div>
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark uppercase font-semibold">Equation Status</label>
            @if(abs($difference) < 0.01)
                <p class="text-2xl font-extrabold text-success mt-1">৳0.00</p>
                <span class="text-[11px] text-success font-semibold mt-0.5">✓ Assets = Liabilities + Equity</span>
            @else
                <p class="text-2xl font-extrabold text-danger mt-1">৳{{ number_format(abs($difference), 2) }}</p>
                <span class="text-[11px] text-danger font-semibold mt-0.5">⚠ Out of Balance</span>
            @endif
        </div>
    </div>

    {{-- Dual Column Balance Sheet Grid --}}
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Left Column: ASSETS --}}
        <div class="panel flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b pb-3 mb-4 dark:border-gray-700">
                    <h3 class="text-base font-bold uppercase text-primary flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-primary inline-block"></span>
                        Assets
                    </h3>
                    <span class="badge badge-outline-primary text-xs">{{ count($sections['asset']) }} Accounts</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="border-b dark:border-gray-700 text-white-dark">
                                <th class="py-2 text-left">Code</th>
                                <th class="py-2 text-left">Account Name</th>
                                <th class="py-2 text-right">Balance (৳)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sections['asset'] as $row)
                                <tr class="border-t dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="py-2.5 font-mono font-bold text-primary">{{ $row['code'] }}</td>
                                    <td class="py-2.5 font-medium">{{ $row['name'] }}</td>
                                    <td class="py-2.5 text-right font-mono font-semibold {{ $row['balance'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        ৳{{ number_format($row['balance'], 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="py-4 text-center text-gray-400">No asset accounts recorded.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6 border-t-2 border-primary pt-3 flex items-center justify-between font-extrabold text-sm bg-primary/5 dark:bg-primary/10 p-3 rounded-md">
                <span class="uppercase text-primary">Total Assets</span>
                <span class="font-mono text-primary text-base">৳{{ number_format($totalAssets, 2) }}</span>
            </div>
        </div>

        {{-- Right Column: LIABILITIES & OWNER'S EQUITY --}}
        <div class="panel flex flex-col justify-between">
            <div>
                {{-- Section A: Liabilities --}}
                <div class="mb-6">
                    <div class="flex items-center justify-between border-b pb-3 mb-4 dark:border-gray-700">
                        <h3 class="text-base font-bold uppercase text-danger flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-danger inline-block"></span>
                            Liabilities
                        </h3>
                        <span class="badge badge-outline-danger text-xs">{{ count($sections['liability']) }} Accounts</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="border-b dark:border-gray-700 text-white-dark">
                                    <th class="py-2 text-left">Code</th>
                                    <th class="py-2 text-left">Account Name</th>
                                    <th class="py-2 text-right">Balance (৳)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sections['liability'] as $row)
                                    <tr class="border-t dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                        <td class="py-2.5 font-mono font-bold text-primary">{{ $row['code'] }}</td>
                                        <td class="py-2.5 font-medium">{{ $row['name'] }}</td>
                                        <td class="py-2.5 text-right font-mono font-semibold {{ $row['balance'] >= 0 ? 'text-danger' : 'text-success' }}">
                                            ৳{{ number_format($row['balance'], 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="py-4 text-center text-gray-400">No liability accounts recorded.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 flex items-center justify-between text-xs font-bold py-1.5 px-2 bg-gray-50 dark:bg-gray-800 rounded">
                        <span>Subtotal Liabilities</span>
                        <span class="font-mono text-danger">৳{{ number_format($totals['liability'], 2) }}</span>
                    </div>
                </div>

                {{-- Section B: Owner's Equity --}}
                <div>
                    <div class="flex items-center justify-between border-b pb-3 mb-4 dark:border-gray-700">
                        <h3 class="text-base font-bold uppercase text-info flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-info inline-block"></span>
                            Owner's Equity
                        </h3>
                        <span class="badge badge-outline-info text-xs">{{ count($sections['equity']) }} Accounts</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="border-b dark:border-gray-700 text-white-dark">
                                    <th class="py-2 text-left">Code</th>
                                    <th class="py-2 text-left">Account Name</th>
                                    <th class="py-2 text-right">Balance (৳)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sections['equity'] as $row)
                                    <tr class="border-t dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                        <td class="py-2.5 font-mono font-bold text-primary">{{ $row['code'] }}</td>
                                        <td class="py-2.5 font-medium">{{ $row['name'] }}</td>
                                        <td class="py-2.5 text-right font-mono font-semibold {{ $row['balance'] >= 0 ? 'text-info' : 'text-danger' }}">
                                            ৳{{ number_format($row['balance'], 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="py-4 text-center text-gray-400">No equity accounts recorded.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 flex items-center justify-between text-xs font-bold py-1.5 px-2 bg-gray-50 dark:bg-gray-800 rounded">
                        <span>Subtotal Equity</span>
                        <span class="font-mono text-info">৳{{ number_format($totals['equity'], 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 border-t-2 border-info pt-3 flex items-center justify-between font-extrabold text-sm bg-info/5 dark:bg-info/10 p-3 rounded-md">
                <span class="uppercase text-info">Total Liabilities &amp; Equity</span>
                <span class="font-mono text-info text-base">৳{{ number_format($totalLiabilitiesEquity, 2) }}</span>
            </div>
        </div>
    </div>
@endsection
