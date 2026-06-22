@extends('admin.layouts.master')

@section('title', 'Fuel Logs')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Fuel Consumption Logs</h2>
        <a href="{{ route('admin.hr.fuel-logs.create') }}" class="btn btn-primary gap-2">+ Add Entry</a>
    </div>

    <div class="panel mt-6">
        <form method="GET" class="mb-4 flex flex-nowrap items-end gap-2 overflow-x-auto">
            <div>
                <label class="text-xs font-semibold">Equipment</label>
                <select name="equipment_id" class="form-select" style="min-width: 200px;">
                    <option value="">All Equipment</option>
                    @foreach($equipment as $id => $name)
                        <option value="{{ $id }}" {{ request('equipment_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold">Fuel Type</label>
                <select name="fuel_type" class="form-select" style="min-width: 200px;">
                    <option value="">All Types</option>
                    <option value="diesel" {{ request('fuel_type') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                    <option value="petrol" {{ request('fuel_type') == 'petrol' ? 'selected' : '' }}>Petrol</option>
                    <option value="gas" {{ request('fuel_type') == 'gas' ? 'selected' : '' }}>Gas</option>
                    <option value="other" {{ request('fuel_type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['equipment_id', 'fuel_type']))
                    <a href="{{ route('admin.hr.fuel-logs.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </div>
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
                        <th>Total</th>
                        <th>Meter Hrs</th>
                        <th>Vendor</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $r)
                        <tr>
                            <td class="whitespace-nowrap">{{ $r->date->format('d M Y') }}</td>
                            <td class="font-semibold">{{ $r->equipment->name }}</td>
                            <td><span class="badge badge-outline-{{ $r->fuel_type === 'diesel' ? 'secondary' : ($r->fuel_type === 'petrol' ? 'warning' : 'info') }}">{{ ucfirst($r->fuel_type) }}</span></td>
                            <td>{{ number_format($r->quantity, 1) }} {{ $r->unit }}</td>
                            <td>{{ number_format($r->unit_cost, 2) }}</td>
                            <td>{{ number_format($r->total_cost, 2) }}</td>
                            <td>{{ $r->meter_hours ? number_format($r->meter_hours) : '—' }}</td>
                            <td>{{ $r->vendor ?? '—' }}</td>
                            <td class="flex gap-1">
                                <a href="{{ route('admin.hr.fuel-logs.show', $r) }}" class="btn btn-sm btn-outline-info">View</a>
                                <a href="{{ route('admin.hr.fuel-logs.edit', $r) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form action="{{ route('admin.hr.fuel-logs.destroy', $r) }}" method="POST" class="inline" onsubmit="return confirm('Delete this entry?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
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
