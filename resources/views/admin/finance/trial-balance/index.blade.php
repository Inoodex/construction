@extends('admin.layouts.master')

@section('title', 'Trial Balance')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Trial Balance</h5>
    </div>

    <form method="GET" class="mb-4 flex items-center gap-3">
        <input type="date" name="date" class="form-input" value="{{ $asOf }}" />
        <button type="submit" class="btn btn-primary">Run</button>
    </form>

    <div class="mb-3 text-sm text-white-dark">As of: <span class="font-semibold">{{ \Carbon\Carbon::parse($asOf)->format('d M Y') }}</span></div>

    <div class="overflow-x-auto">
        <table class="table-hover w-full table-auto">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Account</th>
                    <th>Type</th>
                    <th class="text-right">Debit (৳)</th>
                    <th class="text-right">Credit (৳)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $row)
                    <tr>
                        <td class="font-mono text-xs">{{ $row['code'] }}</td>
                        <td>{{ $row['name'] }}</td>
                        <td><span class="badge badge-outline-primary capitalize">{{ $row['type'] }}</span></td>
                        <td class="text-right font-mono">{{ $row['debit'] > 0 ? number_format($row['debit'], 2) : '—' }}</td>
                        <td class="text-right font-mono">{{ $row['credit'] > 0 ? number_format($row['credit'], 2) : '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-gray-500">No transactions posted yet.</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="font-semibold">
                    <td colspan="3" class="text-right">Total:</td>
                    <td class="text-right font-mono">{{ number_format($totalDebit, 2) }}</td>
                    <td class="text-right font-mono">{{ number_format($totalCredit, 2) }}</td>
                </tr>
                @if(abs($totalDebit - $totalCredit) > 0.01)
                    <tr>
                        <td colspan="5" class="text-center text-danger">
                            ⚠ Difference: {{ number_format(abs($totalDebit - $totalCredit), 2) }}
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="5" class="text-center text-success">✓ Balanced</td>
                    </tr>
                @endif
            </tfoot>
        </table>
    </div>
</div>
@endsection
