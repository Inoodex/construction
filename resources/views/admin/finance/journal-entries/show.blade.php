@extends('admin.layouts.master')

@section('title', 'Journal #' . $entry->journal_number)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Journal #{{ $entry->journal_number }}</h2>
        <a href="{{ route('admin.finance.journal-entries.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div><span class="text-xs text-white-dark">Journal #</span><p class="font-mono font-semibold">{{ $entry->journal_number }}</p></div>
            <div><span class="text-xs text-white-dark">Date</span><p>{{ $entry->date->format('d M Y') }}</p></div>
            <div><span class="text-xs text-white-dark">Type</span><p class="capitalize">{{ $entry->type }}</p></div>
            <div><span class="text-xs text-white-dark">Status</span>
                <p>@if($entry->status == 'posted')<span class="badge badge-outline-success">Posted</span>@else<span class="badge badge-outline-warning">Draft</span>@endif</p>
            </div>
            @if($entry->description)
                <div class="md:col-span-4"><span class="text-xs text-white-dark">Description</span><p>{{ $entry->description }}</p></div>
            @endif
            <div class="md:col-span-4"><span class="text-xs text-white-dark">Created By</span><p>{{ $entry->creator?->name ?? '—' }}</p></div>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Account Code</th>
                        <th>Account Name</th>
                        <th>Description</th>
                        <th class="text-right">Debit (৳)</th>
                        <th class="text-right">Credit (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalDebit = 0; $totalCredit = 0; @endphp
                    @foreach($entry->items as $item)
                        @php
                            $totalDebit += $item->debit_amount;
                            $totalCredit += $item->credit_amount;
                        @endphp
                        <tr>
                            <td class="font-mono text-xs">{{ $item->account->account_code ?? '—' }}</td>
                            <td>{{ $item->account->name ?? '—' }}</td>
                            <td class="text-xs">{{ $item->description ?? '—' }}</td>
                            <td class="text-right font-mono {{ $item->debit_amount > 0 ? 'text-success' : '' }}">{{ $item->debit_amount > 0 ? number_format($item->debit_amount, 2) : '—' }}</td>
                            <td class="text-right font-mono {{ $item->credit_amount > 0 ? 'text-danger' : '' }}">{{ $item->credit_amount > 0 ? number_format($item->credit_amount, 2) : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-semibold">
                        <td colspan="3" class="text-right">Total:</td>
                        <td class="text-right font-mono text-success">{{ number_format($totalDebit, 2) }}</td>
                        <td class="text-right font-mono text-danger">{{ number_format($totalCredit, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
