@extends('admin.layouts.master')

@section('title', 'Balance Sheet')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Balance Sheet</h5>
        <form method="GET" class="flex items-center gap-2">
            <label class="text-xs">As of</label>
            <input type="date" name="date" class="form-input w-auto" value="{{ $asOf }}" onchange="this.form.submit()" />
        </form>
    </div>

    @php $labels = ['asset' => 'Assets', 'liability' => 'Liabilities', 'equity' => "Owner's Equity"]; @endphp

    <div class="overflow-x-auto">
        <table class="table-hover w-full table-auto">
            <thead>
                <tr><th class="w-1/3">Account</th><th class="w-1/3">Code</th><th class="w-1/3 text-right">Amount (৳)</th></tr>
            </thead>
            <tbody>
                @foreach(['asset', 'liability', 'equity'] as $section)
                    <tr class="bg-gray-100 font-semibold dark:bg-gray-700">
                        <td colspan="3">{{ $labels[$section] }}</td>
                    </tr>
                    @forelse($sections[$section] as $row)
                        <tr>
                            <td class="pl-6">{{ $row['name'] }}</td>
                            <td class="font-mono text-xs">{{ $row['code'] }}</td>
                            <td class="font-mono text-right">{{ number_format($row['balance'], 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="pl-6 text-gray-500">No {{ $section }} accounts with balance.</td></tr>
                    @endforelse
                    <tr class="font-semibold border-t-2 border-gray-300">
                        <td colspan="2">Total {{ $labels[$section] }}</td>
                        <td class="font-mono text-right">{{ number_format($totals[$section], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray-200 text-base font-bold dark:bg-gray-600">
                    <td colspan="2">Total Assets</td>
                    <td class="font-mono text-right">{{ number_format($totalAssets, 2) }}</td>
                </tr>
                <tr class="bg-gray-200 text-base font-bold dark:bg-gray-600">
                    <td colspan="2">Total Liabilities &amp; Equity</td>
                    <td class="font-mono text-right">{{ number_format($totalLiabilitiesEquity, 2) }}</td>
                </tr>
                @if(abs($difference) > 0.01)
                    <tr class="bg-red-100 text-base font-bold text-danger">
                        <td colspan="2">Difference</td>
                        <td class="font-mono text-right">{{ number_format($difference, 2) }}</td>
                    </tr>
                @else
                    <tr class="bg-green-100 text-base font-bold text-success">
                        <td colspan="2">Balanced ✓</td>
                        <td class="font-mono text-right">0.00</td>
                    </tr>
                @endif
            </tfoot>
        </table>
    </div>
</div>
@endsection
