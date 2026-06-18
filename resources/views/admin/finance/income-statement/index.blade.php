@extends('admin.layouts.master')

@section('title', 'Income Statement')

@section('content')
<div class="panel">
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <h5 class="text-lg font-semibold dark:text-white-light">Income Statement</h5>
        <form method="GET" class="flex items-center gap-2">
            <label class="text-xs">From</label>
            <input type="date" name="start_date" class="form-input w-auto" value="{{ $startDate }}" />
            <label class="text-xs">To</label>
            <input type="date" name="end_date" class="form-input w-auto" value="{{ $endDate }}" />
            <button type="submit" class="btn btn-primary btn-sm">Apply</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="table-hover w-full table-auto">
            <thead>
                <tr><th class="w-1/3">Account</th><th class="w-1/3">Code</th><th class="w-1/3 text-right">Amount (৳)</th></tr>
            </thead>
            <tbody>
                <tr class="bg-gray-100 font-semibold dark:bg-gray-700">
                    <td colspan="3">Income / Revenue</td>
                </tr>
                @forelse($sections['income'] as $row)
                    <tr>
                        <td class="pl-6">{{ $row['name'] }}</td>
                        <td class="font-mono text-xs">{{ $row['code'] }}</td>
                        <td class="font-mono text-right">{{ number_format($row['balance'], 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="pl-6 text-gray-500">No income recorded in this period.</td></tr>
                @endforelse
                <tr class="font-semibold border-t-2 border-gray-300">
                    <td colspan="2">Total Income</td>
                    <td class="font-mono text-right">{{ number_format($totalIncome, 2) }}</td>
                </tr>

                <tr class="bg-gray-100 font-semibold dark:bg-gray-700">
                    <td colspan="3" class="pt-4">Expenses</td>
                </tr>
                @forelse($sections['expense'] as $row)
                    <tr>
                        <td class="pl-6">{{ $row['name'] }}</td>
                        <td class="font-mono text-xs">{{ $row['code'] }}</td>
                        <td class="font-mono text-right">{{ number_format($row['balance'], 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="pl-6 text-gray-500">No expenses recorded in this period.</td></tr>
                @endforelse
                <tr class="font-semibold border-t-2 border-gray-300">
                    <td colspan="2">Total Expenses</td>
                    <td class="font-mono text-right">{{ number_format($totalExpenses, 2) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="bg-gray-200 text-base font-bold dark:bg-gray-600">
                    <td colspan="2">Net {{ $netIncome >= 0 ? 'Income' : 'Loss' }}</td>
                    <td class="font-mono text-right {{ $netIncome >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format(abs($netIncome), 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
