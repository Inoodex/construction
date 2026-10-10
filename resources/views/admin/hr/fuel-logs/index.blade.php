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
                            <td class="text-center">
                                <x-action-dropdown>
                                    <x-slot name="items">
                                        <li><a href="{{ route('admin.hr.fuel-logs.show', $r) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                        <li><a href="{{ route('admin.hr.fuel-logs.edit', $r) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/></svg> Edit</a></li>
                                        <li class="divider"></li>
                                        <li class="danger">
                                            <form action="{{ route('admin.hr.fuel-logs.destroy', $r) }}" method="POST" onsubmit="return confirm('Delete this entry?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 001-1h4a1 1 0 001 1m-6 0h6"/></svg> Delete</button>
                                            </form>
                                        </li>
                                    </x-slot>
                                </x-action-dropdown>
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
