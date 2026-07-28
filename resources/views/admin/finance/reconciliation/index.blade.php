@extends('admin.layouts.master')

@section('title', 'Ledger Reconciliation')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Control Account Reconciliation</h2>
            <p class="text-xs text-white-dark mt-0.5">As of {{ \Carbon\Carbon::parse($asOf)->format('d F Y') }} — Compares GL control accounts against operational subledgers</p>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="panel mt-6">
        <form method="GET" class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-xs font-semibold text-white-dark whitespace-nowrap">As of Date:</span>
                <input type="date" name="date" class="form-input w-48" value="{{ $asOf }}" />
                <button type="submit" class="btn btn-primary">Run Reconciliation</button>
            </div>
            <div class="flex items-center gap-2">
                @php
                    $matchedCount = collect($rows)->where('matched', true)->count();
                    $totalCount = count($rows);
                @endphp
                @if($matchedCount === $totalCount)
                    <span class="badge badge-outline-success text-sm py-1.5 px-3 flex items-center gap-1.5">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        ALL {{ $totalCount }} ACCOUNTS MATCHED
                    </span>
                @else
                    <span class="badge badge-outline-danger text-sm py-1.5 px-3 flex items-center gap-1.5">
                        ⚠ {{ $totalCount - $matchedCount }} OF {{ $totalCount }} ACCOUNTS MISMATCHED
                    </span>
                @endif
            </div>
        </form>
    </div>

    {{-- Top Metric Cards --}}
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark uppercase font-semibold">Total GL Ledger</label>
            <p class="text-2xl font-extrabold text-primary mt-1">৳{{ number_format(collect($rows)->sum('ledger'), 2) }}</p>
            <span class="text-[11px] text-white-dark mt-0.5">General Ledger Control Accounts</span>
        </div>
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark uppercase font-semibold">Total Subledger</label>
            <p class="text-2xl font-extrabold text-info mt-1">৳{{ number_format(collect($rows)->sum('subledger'), 2) }}</p>
            <span class="text-[11px] text-white-dark mt-0.5">Operational Modules Sum</span>
        </div>
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark uppercase font-semibold">Reconciliation Health</label>
            <p class="text-2xl font-extrabold {{ $matchedCount === $totalCount ? 'text-success' : 'text-danger' }} mt-1">
                {{ $matchedCount }} / {{ $totalCount }}
            </p>
            <span class="text-[11px] {{ $matchedCount === $totalCount ? 'text-success' : 'text-danger' }} font-semibold mt-0.5">
                {{ $matchedCount === $totalCount ? '✓ All Subledgers Reconciled' : '⚠ Action Required' }}
            </span>
        </div>
    </div>

    {{-- Reconciliation Table --}}
    <div class="panel mt-6">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-sm font-bold uppercase text-primary">Control Account Reconciliation Summary</h5>
            <span class="badge badge-outline-primary text-xs">{{ count($rows) }} Accounts Analyzed</span>
        </div>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto text-xs">
                <thead>
                    <tr class="border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        <th class="py-3 px-4 text-left">Control Account</th>
                        <th class="py-3 px-4 text-left">GL Code</th>
                        <th class="py-3 px-4 text-right">GL Ledger (৳)</th>
                        <th class="py-3 px-4 text-right">Subledger (৳)</th>
                        <th class="py-3 px-4 text-right">Difference (৳)</th>
                        <th class="py-3 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $row)
                        <tr class="border-t dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-sm text-primary">{{ $row['label'] }}</div>
                                <div class="text-xs text-white-dark mt-0.5">{{ $row['note'] }}</div>
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold text-primary">{{ $row['code'] }}</td>
                            <td class="py-3.5 px-4 text-right font-mono text-sm font-semibold">
                                ৳{{ number_format($row['ledger'], 2) }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono text-sm font-semibold text-info">
                                ৳{{ number_format($row['subledger'], 2) }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono text-sm font-bold {{ $row['matched'] ? 'text-success' : 'text-danger' }}">
                                {{ $row['matched'] ? '৳0.00' : '৳' . number_format($row['difference'], 2) }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($row['matched'])
                                    <span class="badge badge-outline-success text-xs px-2.5 py-1">✓ MATCHED</span>
                                @else
                                    <span class="badge badge-outline-danger text-xs px-2.5 py-1">⚠ MISMATCH</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 rounded-lg border-l-4 border-primary bg-primary/5 p-4 dark:bg-primary/10 text-xs text-white-dark leading-relaxed">
            <strong class="text-primary block font-semibold mb-1">Reconciliation Audit Guidance:</strong>
            A variance mismatch indicates a document was entered into the General Ledger manually with an incorrect amount or an expected automated posting was skipped. To resolve variances, filter the <strong>General Ledger</strong> for the specific control account code during this period.
        </div>
    </div>
@endsection
