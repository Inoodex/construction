@extends('admin.layouts.master')

@section('title', 'Expense Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Expense: {{ $expense->title }}</h2>
        <div class="flex gap-2">
            @if($expense->status === 'draft')
                <form action="{{ route('admin.finance.expenses.mark-paid', $expense->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-success">Mark as Paid</button>
                </form>
            @endif
            <a href="{{ route('admin.finance.expenses.edit', $expense->id) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit
            </a>
            <a href="{{ route('admin.finance.expenses.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to List
            </a>
        </div>
    </div>

    @php
        $badgeClass = ['draft' => 'badge-outline-secondary', 'paid' => 'badge-outline-success'];
    @endphp

    <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="panel">
            <label class="text-xs text-white-dark">Title</label>
            <p class="font-semibold">{{ $expense->title }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Category</label>
            <p class="font-semibold">{{ $expense->category->label ?? 'N/A' }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Status</label>
            <p><span class="badge {{ $badgeClass[$expense->status] }} capitalize">{{ $expense->status }}</span></p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Date</label>
            <p>{{ $expense->expense_date->format('d M Y') }}</p>
        </div>
    </div>

    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <div class="panel bg-info/10 dark:bg-info/20">
            <label class="text-xs text-white-dark">Amount</label>
            <p class="text-lg font-bold text-info">{{ number_format($expense->amount) }}</p>
        </div>
        <div class="panel bg-warning/10 dark:bg-warning/20">
            <label class="text-xs text-white-dark">Tax ({{ $expense->tax_rate }}%)</label>
            <p class="text-lg font-bold text-warning">{{ number_format($expense->tax_amount) }}</p>
        </div>
        <div class="panel bg-dark/10 dark:bg-dark/20">
            <label class="text-xs text-white-dark">Total</label>
            <p class="text-lg font-bold">{{ number_format($expense->total_amount) }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Payment Method</label>
            <p class="font-semibold capitalize">{{ $expense->payment_method ? str_replace('_', ' ', $expense->payment_method) : 'N/A' }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Reference #</label>
            <p class="font-semibold">{{ $expense->reference_number ?? 'N/A' }}</p>
        </div>
    </div>

    <div class="mt-4 grid gap-4 sm:grid-cols-2">
        <div class="panel">
            <label class="text-xs text-white-dark">Vendor</label>
            <p class="font-semibold">{{ $expense->vendor->name ?? 'N/A' }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Project</label>
            <p class="font-semibold">{{ $expense->project->name ?? 'General Expense' }}</p>
        </div>
    </div>

        <div class="panel mt-4">
            <h5 class="mb-2 text-base font-semibold">Description</h5>
            <p class="text-xs text-white-dark">{{ $expense->description ?? 'N/A' }}</p>
        </div>

        <div class="panel mt-4">
            <h5 class="mb-2 text-base font-semibold">Notes</h5>
            <p class="text-xs text-white-dark">{{ $expense->notes ?? 'N/A' }}</p>
        </div>

    @if($expense->receipt)
        <div class="panel mt-4">
            <h5 class="mb-2 text-base font-semibold">Receipt</h5>
            <a href="{{ asset('storage/' . $expense->receipt) }}" target="_blank" class="btn btn-outline-info w-32">View Receipt</a>
        </div>
    @endif
@endsection
