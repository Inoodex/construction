@extends('admin.layouts.master')

@section('title', 'Fuel Logs')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Fuel Consumption Logs</h5>
        <a href="{{ route('admin.hr.fuel-logs.create') }}" class="btn btn-primary">+ Add Entry</a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-100 p-3 text-green-700">{{ session('success') }}</div>
    @endif

    <form method="GET" class="mb-4 flex flex-nowrap items-center gap-2 overflow-x-auto">
        <select name="equipment_id" class="form-select" onchange="this.form.submit()">
            <option value="">All Equipment</option>
            @foreach($equipment as $id => $name)
                <option value="{{ $id }}" {{ request('equipment_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
        <select name="fuel_type" class="form-select" onchange="this.form.submit()">
            <option value="">All Types</option>
            <option value="diesel" {{ request('fuel_type') == 'diesel' ? 'selected' : '' }}>Diesel</option>
            <option value="petrol" {{ request('fuel_type') == 'petrol' ? 'selected' : '' }}>Petrol</option>
            <option value="gas" {{ request('fuel_type') == 'gas' ? 'selected' : '' }}>Gas</option>
            <option value="other" {{ request('fuel_type') == 'other' ? 'selected' : '' }}>Other</option>
        </select>
        @if(request()->anyFilled(['equipment_id', 'fuel_type']))
            <a href="{{ route('admin.hr.fuel-logs.index') }}" class="btn btn-outline-danger btn-sm">Reset</a>
        @endif
    </form>

    <div class="overflow-x-auto">
        <table class="table-hover w-full table-auto">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Equipment</th>
                    <th>Type</th>
                    <th>Qty</th>
                    <th>Unit Cost</th>
                    <th class="text-right">Total</th>
                    <th>Meter Hrs</th>
                    <th>Vendor</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $r)
                    <tr>
                        <td class="text-xs whitespace-nowrap">{{ $r->date->format('d M Y') }}</td>
                        <td class="font-semibold">{{ $r->equipment->name }}</td>
                        <td><span class="badge badge-outline-{{ $r->fuel_type === 'diesel' ? 'secondary' : ($r->fuel_type === 'petrol' ? 'warning' : 'info') }}">{{ ucfirst($r->fuel_type) }}</span></td>
                        <td>{{ number_format($r->quantity, 1) }} {{ $r->unit }}</td>
                        <td>{{ number_format($r->unit_cost, 2) }}</td>
                        <td class="text-right font-semibold">{{ number_format($r->total_cost, 2) }}</td>
                        <td class="text-xs">{{ $r->meter_hours ? number_format($r->meter_hours) : '—' }}</td>
                        <td class="text-xs">{{ $r->vendor ?? '—' }}</td>
                        <td class="flex gap-1">
                            <a href="{{ route('admin.hr.fuel-logs.show', $r) }}" class="btn btn-xs btn-outline-info">View</a>
                            <a href="{{ route('admin.hr.fuel-logs.edit', $r) }}" class="btn btn-xs btn-outline-secondary">Edit</a>
                            <form action="{{ route('admin.hr.fuel-logs.destroy', $r) }}" method="POST" class="inline" onsubmit="return confirm('Delete this entry?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-outline-danger">×</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-gray-400 py-4">No fuel log entries found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $records->links() }}</div>
</div>
@endsection
