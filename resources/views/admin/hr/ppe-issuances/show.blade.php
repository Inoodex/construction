@extends('admin.layouts.master')

@section('title', $ppeIssuance->item_name)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">{{ $ppeIssuance->item_name }}</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.hr.ppe-issuances.edit', $ppeIssuance) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit
            </a>
            <a href="{{ route('admin.hr.ppe-issuances.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to List
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <table class="w-full text-sm">
            <tr><td class="py-2 text-gray-500 w-36">Employee</td><td class="font-semibold">{{ $ppeIssuance->employee->full_name }}</td></tr>
            <tr><td class="py-2 text-gray-500">Item</td><td>{{ $ppeIssuance->item_name }}</td></tr>
            <tr><td class="py-2 text-gray-500">Category</td><td>{{ $ppeIssuance->category ?? '—' }}</td></tr>
            <tr><td class="py-2 text-gray-500">Quantity</td><td>{{ $ppeIssuance->quantity }}</td></tr>
            <tr><td class="py-2 text-gray-500">Size</td><td>{{ $ppeIssuance->size ?? '—' }}</td></tr>
            <tr><td class="py-2 text-gray-500">Issue Date</td><td>{{ $ppeIssuance->issue_date->format('d M Y') }}</td></tr>
            <tr><td class="py-2 text-gray-500">Condition on Issue</td><td>{{ $ppeIssuance->condition_on_issue ?? '—' }}</td></tr>
            <tr><td class="py-2 text-gray-500">Return Date</td><td>{{ $ppeIssuance->return_date?->format('d M Y') ?? '—' }}</td></tr>
            <tr><td class="py-2 text-gray-500">Condition on Return</td><td>{{ $ppeIssuance->condition_on_return ?? '—' }}</td></tr>
            <tr><td class="py-2 text-gray-500">Status</td><td>
                @if($ppeIssuance->return_date)
                    <span class="badge badge-outline-success">Returned</span>
                @else
                    <span class="badge badge-outline-warning">Issued</span>
                @endif
            </td></tr>
            @if($ppeIssuance->notes)
                <tr><td class="py-2 text-gray-500 align-top">Notes</td><td>{{ $ppeIssuance->notes }}</td></tr>
            @endif
        </table>
    </div>
@endsection
