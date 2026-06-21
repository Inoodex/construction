@extends('admin.layouts.master')

@section('title', 'Fuel Log - ' . $fuelLog->date->format('d M Y'))

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Fuel Log Entry</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.hr.fuel-logs.edit', $fuelLog) }}" class="btn btn-outline-secondary">Edit</a>
            <a href="{{ route('admin.hr.fuel-logs.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
        </div>
    </div>

    <div class="panel mt-6 max-w-2xl">
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
