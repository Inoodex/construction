@extends('admin.layouts.master')

@section('title', 'Ledger Reconciliation')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Control Account Reconciliation</h5>
    </div>

    <form method="GET" class="mb-4 flex items-center gap-3">
        <input type="date" name="date" class="form-input" value="{{ $asOf }}" />
        <button type="submit" class="btn btn-primary">Run</button>
    </form>

    <div class="mb-3 text-sm text-white-dark">
        As of: <span class="font-semibold">{{ \Carbon\Carbon::parse($asOf)->format('d M Y') }}</span>
        &mdash; compares each general-ledger control account against its operational subledger.
    </div>

    <div class="overflow-x-auto">
        <table class="table-hover w-full table-auto">
            <thead>
                <tr>
                    <th>Control Account</th>
                    <th>Code</th>
                    <th class="text-right">Ledger (৳)</th>
                    <th class="text-right">Subledger (৳)</th>
                    <th class="text-right">Difference (৳)</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        <td>
                            <div class="font-semibold">{{ $row['label'] }}</div>
                            <div class="text-xs text-white-dark">{{ $row['note'] }}</div>
                        </td>
                        <td class="font-mono text-xs">{{ $row['code'] }}</td>
                        <td class="text-right font-mono">{{ number_format($row['ledger'], 2) }}</td>
                        <td class="text-right font-mono">{{ number_format($row['subledger'], 2) }}</td>
                        <td class="text-right font-mono {{ $row['matched'] ? '' : 'text-danger font-semibold' }}">
                            {{ number_format($row['difference'], 2) }}
                        </td>
                        <td class="text-center">
                            @if($row['matched'])
                                <span class="badge badge-outline-success">✓ Matched</span>
                            @else
                                <span class="badge badge-outline-danger">⚠ Mismatch</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-xs text-white-dark">
        A mismatch usually means a document was posted to the ledger manually with a wrong amount,
        or an expected auto entry is missing. Review the account's General Ledger for the period to locate the gap.
    </div>
</div>
@endsection
