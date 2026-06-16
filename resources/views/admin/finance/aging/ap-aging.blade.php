@extends('admin.layouts.master')

@section('title', 'Accounts Payable Aging')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Accounts Payable Aging</h2>
        <a href="{{ route('admin.finance.bills.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            View Bills
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.finance.aging.ap') }}" method="GET" class="mb-5 flex items-center gap-3 w-full">
            <select name="project_id" class="form-select flex-1">
                <option value="">All Projects</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                @endforeach
            </select>
            <select name="vendor_id" class="form-select flex-1">
                <option value="">All Vendors</option>
                @foreach($vendors as $vendor)
                    <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
            @if(request()->anyFilled(['project_id', 'vendor_id']))
                <a href="{{ route('admin.finance.aging.ap') }}" class="btn btn-outline-danger">Reset</a>
            @endif
        </form>

        <div class="mb-5 grid gap-4 sm:grid-cols-5">
            @foreach($buckets as $key => $bucket)
                <div class="panel text-center">
                    <p class="text-xs text-white-dark">{{ $bucket['label'] }}</p>
                    <p class="text-lg font-bold {{ $key === 'days90_plus' ? 'text-danger' : ($key === 'current' ? 'text-success' : 'text-warning') }}">{{ number_format($bucket['total']) }}</p>
                </div>
            @endforeach
        </div>

        <div class="overflow-x-auto">
            @foreach($buckets as $key => $bucket)
                @if($bucket['bills']->count() > 0)
                    <h5 class="mb-2 mt-4 font-semibold {{ $key === 'days90_plus' ? 'text-danger' : ($key === 'current' ? 'text-success' : 'text-warning') }}">{{ $bucket['label'] }} — {{ number_format($bucket['total']) }}</h5>
                    <table class="table-hover mb-4 w-full table-auto">
                        <thead><tr><th>Bill #</th><th>Vendor</th><th>Project</th><th>Due Date</th><th>Total</th><th>Due</th><th>Days Overdue</th></tr></thead>
                        <tbody>
                            @foreach($bucket['bills'] as $bill)
                                <tr>
                                    <td><a href="{{ route('admin.finance.bills.show', $bill->id) }}" class="font-mono text-xs text-primary hover:underline">{{ $bill->bill_number }}</a></td>
                                    <td class="text-xs">{{ $bill->vendor->name ?? 'N/A' }}</td>
                                    <td class="text-xs">{{ $bill->project->name ?? 'N/A' }}</td>
                                    <td class="text-xs">{{ $bill->due_date?->format('d/m/Y') ?? '-' }}</td>
                                    <td>{{ number_format($bill->total_amount) }}</td>
                                    <td class="font-semibold text-danger">{{ number_format($bill->due_amount) }}</td>
                                    <td class="text-xs">{{ $bill->due_date ? max(0, now()->diffInDays($bill->due_date, false)) : 0 }}d</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @endforeach
            @if($grandTotal <= 0)
                <p class="text-center py-4 text-white-dark">No outstanding payables.</p>
            @endif
        </div>

        <div class="mt-4 border-t pt-4 text-right">
            <p class="text-lg font-bold">Grand Total Outstanding: <span class="{{ $grandTotal > 0 ? 'text-danger' : 'text-success' }}">{{ number_format($grandTotal) }}</span></p>
        </div>
    </div>
@endsection
