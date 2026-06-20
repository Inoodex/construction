@extends('admin.layouts.master')

@section('title', $materialTakeoff->description)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">{{ $materialTakeoff->description }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.finance.material-takeoffs.edit', $materialTakeoff) }}" class="btn btn-outline-secondary">Edit</a>
            <a href="{{ route('admin.finance.material-takeoffs.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <h4 class="font-semibold mb-3">Details</h4>
                <table class="w-full text-sm">
                    <tr><td class="py-1 text-gray-500 w-28">Project</td><td class="font-semibold">{{ $materialTakeoff->project->name }}</td></tr>
                    <tr><td class="py-1 text-gray-500">BOQ Item</td><td>{{ $materialTakeoff->boqItem?->item_number ?? '—' }} {{ $materialTakeoff->boqItem ? '- ' . $materialTakeoff->boqItem->description : '' }}</td></tr>
                    <tr><td class="py-1 text-gray-500">Description</td><td>{{ $materialTakeoff->description }}</td></tr>
                    <tr><td class="py-1 text-gray-500">Unit</td><td>{{ $materialTakeoff->unit ?? '—' }}</td></tr>
                    <tr><td class="py-1 text-gray-500">Source Drawing</td><td>{{ $materialTakeoff->source_drawing ?? '—' }}</td></tr>
                </table>
            </div>
            <div>
                <h4 class="font-semibold mb-3">Quantities</h4>
                <table class="w-full text-sm">
                    <tr><td class="py-1 text-gray-500 w-28">Quantity</td><td class="font-semibold">{{ number_format($materialTakeoff->quantity, 2) }}</td></tr>
                    <tr><td class="py-1 text-gray-500">Unit Price</td><td class="font-semibold">{{ number_format($materialTakeoff->unit_price, 2) }}</td></tr>
                    <tr><td class="py-1 text-gray-500 font-bold">Total Price</td><td class="font-bold text-lg">{{ number_format($materialTakeoff->total_price, 2) }}</td></tr>
                </table>
            </div>
        </div>
        @if($materialTakeoff->notes)
            <div class="mt-4 border-t pt-3">
                <h4 class="font-semibold mb-1">Notes</h4>
                <p class="text-sm text-gray-600">{{ $materialTakeoff->notes }}</p>
            </div>
        @endif
    </div>
@endsection
