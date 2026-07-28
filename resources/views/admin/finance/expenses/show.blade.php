@extends('admin.layouts.master')

@section('title', 'Expense Details - ' . $expense->title)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Expense Voucher</h2>
            <p class="text-xs text-white-dark mt-0.5">{{ $expense->title }} — {{ $expense->category->label ?? 'N/A' }}</p>
        </div>
        <div class="flex items-center gap-2">
            @if($expense->status === 'draft')
                <form action="{{ route('admin.finance.expenses.mark-paid', $expense->id) }}" method="POST" class="inline" onsubmit="return confirm('Mark this expense as paid? This action cannot be undone.');">
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

    {{-- Top Metadata Panels --}}
    <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-5">
        <div class="panel">
            <label class="text-xs text-white-dark uppercase font-semibold">Expense Title</label>
            <p class="font-semibold text-sm">{{ $expense->title }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark uppercase font-semibold">Category</label>
            <p class="font-semibold text-sm text-primary">{{ $expense->category->label ?? 'N/A' }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark uppercase font-semibold">Vendor / Supplier</label>
            <p class="font-semibold text-sm">{{ $expense->vendor->name ?? 'N/A' }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark uppercase font-semibold">Project</label>
            <p class="font-semibold text-sm">{{ $expense->project->name ?? 'General Expense' }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark uppercase font-semibold">Status</label>
            <p><span class="badge {{ $badgeClass[$expense->status] ?? 'badge-outline-secondary' }} capitalize text-xs px-2.5 py-1">{{ $expense->status }}</span></p>
        </div>
    </div>

    {{-- Financial Summary Grid --}}
    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark uppercase font-semibold">Expense Date</label>
            <p class="text-base font-bold mt-1">{{ $expense->expense_date->format('d M Y') }}</p>
        </div>
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark uppercase font-semibold">Base Amount</label>
            <p class="text-2xl font-extrabold text-info mt-1">৳{{ number_format($expense->amount, 2) }}</p>
        </div>
        <div class="panel text-center flex flex-col items-center justify-center">
            <label class="text-xs text-white-dark uppercase font-semibold">Tax ({{ $expense->tax_rate }}%)</label>
            <p class="text-2xl font-extrabold text-warning mt-1">৳{{ number_format($expense->tax_amount, 2) }}</p>
        </div>
        <div class="panel text-center flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-800">
            <label class="text-xs text-white-dark uppercase font-semibold">Total Amount</label>
            <p class="text-2xl font-extrabold text-primary dark:text-white mt-1">৳{{ number_format($expense->total_amount, 2) }}</p>
        </div>
    </div>

    {{-- Payment Details Panel --}}
    <div class="mt-6 panel">
        <h5 class="mb-3 text-sm font-bold uppercase text-primary">Payment &amp; Reference Info</h5>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
            <div>
                <span class="text-white-dark block mb-0.5">Payment Method:</span>
                <span class="font-semibold text-sm capitalize">{{ $expense->payment_method ? str_replace('_', ' ', $expense->payment_method) : 'N/A' }}</span>
            </div>
            <div>
                <span class="text-white-dark block mb-0.5">Reference Number:</span>
                <span class="font-mono font-bold text-sm">{{ $expense->reference_number ?? '—' }}</span>
            </div>
            <div>
                <span class="text-white-dark block mb-0.5">Payment Account:</span>
                <span class="font-semibold text-sm">{{ $expense->paymentAccount->name ?? '—' }}</span>
            </div>
        </div>
    </div>

    @if($expense->description)
        <div class="panel mt-4">
            <h5 class="mb-2 text-sm font-semibold uppercase text-white-dark">Expense Description</h5>
            <p class="text-xs text-white-dark leading-relaxed">{{ $expense->description }}</p>
        </div>
    @endif

    @if($expense->notes)
        <div class="panel mt-4">
            <h5 class="mb-2 text-sm font-semibold uppercase text-white-dark">Notes</h5>
            <p class="text-xs text-white-dark leading-relaxed">{{ $expense->notes }}</p>
        </div>
    @endif

    @if($expense->receipt)
        <div class="panel mt-4 border-l-4 border-info">
            <div class="flex items-center justify-between">
                <div>
                    <h5 class="text-sm font-bold uppercase text-info">Attached Receipt Document</h5>
                    <p class="text-xs text-white-dark mt-0.5">Scanned bill / payment proof receipt</p>
                </div>
                <a href="{{ asset('storage/' . $expense->receipt) }}" target="_blank" class="btn btn-sm btn-outline-info gap-1.5">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    View Receipt
                </a>
            </div>
        </div>
    @endif
@endsection
