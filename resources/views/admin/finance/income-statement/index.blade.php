@extends('admin.layouts.master')

@section('title', 'Income Statement')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Income Statement (Profit &amp; Loss)</h2>
            <p class="text-xs text-white-dark mt-0.5">Period: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }} — Operational Financial Performance</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.finance.income-statement.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="btn btn-outline-success gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                PDF
            </a>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="panel mt-6">
        <form method="GET" class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-xs font-semibold text-white-dark whitespace-nowrap">From:</span>
                <input type="date" name="start_date" class="form-input w-40" value="{{ $startDate }}" />
                <span class="text-xs font-semibold text-white-dark whitespace-nowrap">To:</span>
                <input type="date" name="end_date" class="form-input w-40" value="{{ $endDate }}" />
                <button type="submit" class="btn btn-primary">Apply</button>
            </div>
            <div class="flex items-center gap-2">
                @if($netIncome >= 0)
                    <span class="badge badge-outline-success text-sm py-1.5 px-3 flex items-center gap-1.5">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        NET PROFIT: ৳{{ number_format($netIncome, 2) }}
                    </span>
                @else
                    <span class="badge badge-outline-danger text-sm py-1.5 px-3 flex items-center gap-1.5">
                        ⚠ NET LOSS: ৳{{ number_format(abs($netIncome), 2) }}
                    </span>
                @endif
            </div>
        </form>
    </div>

    {{-- Top Metric Cards --}}
    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark uppercase font-semibold">Total Revenue</label>
            <p class="text-2xl font-extrabold text-primary mt-1">৳{{ number_format($totalIncome, 2) }}</p>
            <span class="text-[11px] text-white-dark mt-0.5">Project Revenue &amp; Billings</span>
        </div>
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark uppercase font-semibold">Operating Expenses</label>
            <p class="text-2xl font-extrabold text-danger mt-1">৳{{ number_format($totalExpenses, 2) }}</p>
            <span class="text-[11px] text-white-dark mt-0.5">Materials, Overhead &amp; Labour</span>
        </div>
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark uppercase font-semibold">Profit Margin</label>
            <p class="text-2xl font-extrabold text-info mt-1">
                {{ $totalIncome > 0 ? number_format(($netIncome / $totalIncome) * 100, 1) : 0 }}%
            </p>
            <span class="text-[11px] text-white-dark mt-0.5">Net Profit Margin Ratio</span>
        </div>
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark uppercase font-semibold">Net Bottom Line</label>
            @if($netIncome >= 0)
                <p class="text-2xl font-extrabold text-success mt-1">৳{{ number_format($netIncome, 2) }}</p>
                <span class="text-[11px] text-success font-semibold mt-0.5">✓ Profitable Period</span>
            @else
                <p class="text-2xl font-extrabold text-danger mt-1">৳{{ number_format(abs($netIncome), 2) }}</p>
                <span class="text-[11px] text-danger font-semibold mt-0.5">⚠ Net Operating Loss</span>
            @endif
        </div>
    </div>

    {{-- Dual Column Income & Expense Grid --}}
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Left Column: REVENUE / INCOME --}}
        <div class="panel flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b pb-3 mb-4 dark:border-gray-700">
                    <h3 class="text-base font-bold uppercase text-primary flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-primary inline-block"></span>
                        Revenue &amp; Income
                    </h3>
                    <span class="badge badge-outline-primary text-xs">{{ count($sections['income']) }} Accounts</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="border-b dark:border-gray-700 text-white-dark">
                                <th class="py-2 text-left">Code</th>
                                <th class="py-2 text-left">Account Name</th>
                                <th class="py-2 text-right">Amount (৳)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sections['income'] as $row)
                                <tr class="border-t dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="py-2.5 font-mono font-bold text-primary">{{ $row['code'] }}</td>
                                    <td class="py-2.5 font-medium">{{ $row['name'] }}</td>
                                    <td class="py-2.5 text-right font-mono font-semibold text-success">
                                        ৳{{ number_format($row['balance'], 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="py-4 text-center text-gray-400">No revenue recorded in this period.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6 border-t-2 border-primary pt-3 flex items-center justify-between font-extrabold text-sm bg-primary/5 dark:bg-primary/10 p-3 rounded-md">
                <span class="uppercase text-primary">Total Revenue</span>
                <span class="font-mono text-primary text-base">৳{{ number_format($totalIncome, 2) }}</span>
            </div>
        </div>

        {{-- Right Column: OPERATING EXPENSES --}}
        <div class="panel flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b pb-3 mb-4 dark:border-gray-700">
                    <h3 class="text-base font-bold uppercase text-danger flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-danger inline-block"></span>
                        Operating Expenses
                    </h3>
                    <span class="badge badge-outline-danger text-xs">{{ count($sections['expense']) }} Accounts</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="border-b dark:border-gray-700 text-white-dark">
                                <th class="py-2 text-left">Code</th>
                                <th class="py-2 text-left">Account Name</th>
                                <th class="py-2 text-right">Amount (৳)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sections['expense'] as $row)
                                <tr class="border-t dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="py-2.5 font-mono font-bold text-primary">{{ $row['code'] }}</td>
                                    <td class="py-2.5 font-medium">{{ $row['name'] }}</td>
                                    <td class="py-2.5 text-right font-mono font-semibold text-danger">
                                        ৳{{ number_format($row['balance'], 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="py-4 text-center text-gray-400">No expenses recorded in this period.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6 border-t-2 border-danger pt-3 flex items-center justify-between font-extrabold text-sm bg-danger/5 dark:bg-danger/10 p-3 rounded-md">
                <span class="uppercase text-danger">Total Operating Expenses</span>
                <span class="font-mono text-danger text-base">৳{{ number_format($totalExpenses, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Bottom Net Result Banner --}}
    <div class="mt-6 panel text-white p-5 rounded-xl shadow-lg {{ $netIncome >= 0 ? 'bg-gradient-to-r from-emerald-600 to-teal-700' : 'bg-gradient-to-r from-red-600 to-rose-700' }}">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h4 class="text-lg font-extrabold uppercase">
                    Net {{ $netIncome >= 0 ? 'Income (Profit)' : 'Operating Loss' }}
                </h4>
                <p class="text-xs text-white/80 mt-1">
                    Revenue (৳{{ number_format($totalIncome, 2) }}) minus Expenses (৳{{ number_format($totalExpenses, 2) }})
                </p>
            </div>
            <div class="text-right">
                <p class="text-3xl font-black font-mono">
                    ৳{{ number_format(abs($netIncome), 2) }}
                </p>
                <span class="text-xs font-semibold bg-white/20 px-2.5 py-1 rounded inline-block mt-1">
                    Margin: {{ $totalIncome > 0 ? number_format(($netIncome / $totalIncome) * 100, 1) : 0 }}%
                </span>
            </div>
        </div>
    </div>
@endsection
