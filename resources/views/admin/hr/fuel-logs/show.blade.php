@extends('admin.layouts.master')

@section('title', 'Fuel Log - ' . $fuelLog->date->format('d M Y'))

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Fuel Log Entry</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.hr.fuel-logs.edit', $fuelLog) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit
            </a>
            <a href="{{ route('admin.hr.fuel-logs.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to List
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <table class="w-full text-sm">
            <tr><td class="py-2 text-gray-500 w-36">Date</td><td>{{ $fuelLog->date->format('d M Y') }}</td></tr>
            <tr><td class="py-2 text-gray-500">Equipment</td><td class="font-semibold">{{ $fuelLog->equipment->name }} ({{ $fuelLog->equipment->code }})</td></tr>
            <tr><td class="py-2 text-gray-500">Fuel Type</td><td><span class="badge badge-outline-{{ $fuelLog->fuel_type === 'diesel' ? 'secondary' : ($fuelLog->fuel_type === 'petrol' ? 'warning' : 'info') }}">{{ ucfirst($fuelLog->fuel_type) }}</span></td></tr>
            <tr><td class="py-2 text-gray-500">Quantity</td><td>{{ number_format($fuelLog->quantity, 1) }} {{ $fuelLog->unit }}</td></tr>
            <tr><td class="py-2 text-gray-500">Unit Cost</td><td>{{ number_format($fuelLog->unit_cost, 2) }}</td></tr>
            <tr><td class="py-2 text-gray-500 font-semibold">Total Cost</td><td class="font-bold">{{ number_format($fuelLog->total_cost, 2) }}</td></tr>
            <tr><td class="py-2 text-gray-500">Meter Hours</td><td>{{ $fuelLog->meter_hours ? number_format($fuelLog->meter_hours) : '—' }}</td></tr>
            <tr><td class="py-2 text-gray-500">Vendor</td><td>{{ $fuelLog->vendor ?? '—' }}</td></tr>
            <tr><td class="py-2 text-gray-500">Receipt No.</td><td class="font-mono">{{ $fuelLog->receipt_no ?? '—' }}</td></tr>
            @if($fuelLog->notes)
                <tr><td class="py-2 text-gray-500 align-top">Notes</td><td>{{ $fuelLog->notes }}</td></tr>
            @endif
        </table>
    </div>
@endsection
