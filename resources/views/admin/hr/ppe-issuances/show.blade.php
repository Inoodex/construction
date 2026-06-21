@extends('admin.layouts.master')

@section('title', $ppeIssuance->item_name)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">{{ $ppeIssuance->item_name }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.hr.ppe-issuances.edit', $ppeIssuance) }}" class="btn btn-outline-secondary">Edit</a>
            <a href="{{ route('admin.hr.ppe-issuances.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
        </div>
    </div>

    <div class="panel mt-6 max-w-2xl">
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
