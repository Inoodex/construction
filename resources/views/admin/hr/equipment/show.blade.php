@extends('admin.layouts.master')

@section('title', $equipment->name)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">{{ $equipment->name }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.hr.equipment.edit', $equipment) }}" class="btn btn-outline-secondary gap-2">Edit</a>
            <a href="{{ route('admin.hr.equipment.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
        </div>
    </div>

    {{-- Details --}}
    <div class="panel mt-6">
        <div class="grid grid-cols-3 gap-6">
            <div>
                <h4 class="font-semibold mb-3">General</h4>
                <table class="w-full text-sm">
                    <tr><td class="py-1 text-gray-500 w-28">Code</td><td class="font-mono">{{ $equipment->code }}</td></tr>
                    <tr><td class="py-1 text-gray-500">Category</td><td>{{ $equipment->category ?? '—' }}</td></tr>
                    <tr><td class="py-1 text-gray-500">Make / Model</td><td>{{ $equipment->make ?? '—' }} {{ $equipment->model ? '/ ' . $equipment->model : '' }}</td></tr>
                    <tr><td class="py-1 text-gray-500">Year</td><td>{{ $equipment->year ?? '—' }}</td></tr>
                    <tr><td class="py-1 text-gray-500">Serial #</td><td class="font-mono">{{ $equipment->serial_number ?? '—' }}</td></tr>
                    <tr><td class="py-1 text-gray-500">Type</td><td><span class="badge badge-{{ $equipment->acquisition_type === 'owned' ? 'info' : 'warning' }}">{{ ucfirst($equipment->acquisition_type) }}</span></td></tr>
                    <tr><td class="py-1 text-gray-500">Status</td><td>
                        <span class="badge badge-{{ $equipment->status === 'active' ? 'success' : ($equipment->status === 'under-maintenance' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($equipment->status) }}
                        </span>
                    </td></tr>
                </table>
            </div>
            <div>
                <h4 class="font-semibold mb-3">Financial</h4>
                <table class="w-full text-sm">
                    <tr><td class="py-1 text-gray-500 w-28">Purchase Cost</td><td class="font-semibold">{{ number_format($equipment->purchase_cost, 2) }}</td></tr>
                    <tr><td class="py-1 text-gray-500">Purchase Date</td><td>{{ $equipment->purchase_date?->format('d M Y') ?? '—' }}</td></tr>
                    <tr><td class="py-1 text-gray-500">Useful Life</td><td>{{ $equipment->useful_life_years }} years</td></tr>
                    <tr><td class="py-1 text-gray-500">Salvage Value</td><td>{{ number_format($equipment->salvage_value, 2) }}</td></tr>
                    <tr><td class="py-1 text-gray-500 font-semibold">Current Value</td><td class="font-bold">{{ number_format($equipment->current_value, 2) }}</td></tr>
                </table>
            </div>
            <div>
                <h4 class="font-semibold mb-3">Usage</h4>
                <table class="w-full text-sm">
                    <tr><td class="py-1 text-gray-500 w-28">Location</td><td>{{ $equipment->location ?? '—' }}</td></tr>
                    <tr><td class="py-1 text-gray-500">Operator</td><td>{{ $equipment->operator ?? '—' }}</td></tr>
                    <tr><td class="py-1 text-gray-500 font-semibold">Meter Hours</td><td class="font-bold">{{ number_format($equipment->meter_hours) }}</td></tr>
                    <tr><td class="py-1 text-gray-500">Maint Interval</td><td>{{ $equipment->maintenance_interval_hours ? number_format($equipment->maintenance_interval_hours) . ' hrs' : '—' }}</td></tr>
                    <tr><td class="py-1 text-gray-500">Next Maint @</td><td>{{ $equipment->next_maintenance_hours ? number_format($equipment->next_maintenance_hours) . ' hrs' : '—' }}</td></tr>
                </table>

                {{-- Quick meter update --}}
                <form action="{{ route('admin.hr.equipment.update-meter', $equipment) }}" method="POST" class="mt-3 flex items-end gap-2">
                    @csrf
                    <div>
                        <label class="text-xs text-gray-500">Update Meter</label>
                        <input type="number" name="meter_hours" class="form-input w-32" value="{{ $equipment->meter_hours }}" min="0" required />
                    </div>
                    <button type="submit" class="btn btn-xs btn-primary">Save</button>
                </form>
            </div>
        </div>
        @if($equipment->notes)
            <div class="mt-4 border-t pt-3">
                <h4 class="font-semibold mb-1">Notes</h4>
                <p class="text-sm text-gray-600">{{ $equipment->notes }}</p>
            </div>
        @endif
    </div>

    {{-- Maintenance History --}}
    <div class="panel mt-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold">Maintenance History</h3>
            <button type="button" onclick="document.getElementById('maint-form').classList.toggle('hidden')" class="btn btn-xs btn-outline-primary">+ Add Record</button>
        </div>

        <form id="maint-form" action="{{ route('admin.hr.equipment.maintenance.store', $equipment) }}" method="POST" class="hidden mb-4 p-4 border rounded">
            @csrf
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="text-xs font-semibold">Date <span class="text-danger">*</span></label>
                    <input type="date" name="maintenance_date" class="form-input" required value="{{ date('Y-m-d') }}" />
                </div>
                <div>
                    <label class="text-xs font-semibold">Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select" required>
                        <option value="preventive">Preventive</option>
                        <option value="corrective">Corrective</option>
                        <option value="inspection">Inspection</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold">Cost <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="cost" class="form-input" required value="0" />
                </div>
                <div>
                    <label class="text-xs font-semibold">Description <span class="text-danger">*</span></label>
                    <input type="text" name="description" class="form-input" required />
                </div>
                <div>
                    <label class="text-xs font-semibold">Meter Hours</label>
                    <input type="number" name="meter_hours" class="form-input" value="{{ $equipment->meter_hours }}" />
                </div>
                <div>
                    <label class="text-xs font-semibold">Vendor</label>
                    <input type="text" name="vendor" class="form-input" />
                </div>
                <div>
                    <label class="text-xs font-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="completed">Completed</option>
                        <option value="in-progress">In Progress</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold">Next Due Date</label>
                    <input type="date" name="next_due_date" class="form-input" />
                </div>
                <div>
                    <label class="text-xs font-semibold">Notes</label>
                    <input type="text" name="notes" class="form-input" />
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm mt-2">Save Record</button>
        </form>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Meter Hrs</th>
                        <th class="text-right">Cost</th>
                        <th>Vendor</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipment->maintenanceRecords as $m)
                        <tr>
                            <td>{{ $m->maintenance_date->format('d M Y') }}</td>
                            <td><span class="badge badge-outline-{{ $m->type === 'preventive' ? 'info' : ($m->type === 'corrective' ? 'warning' : 'secondary') }}">{{ ucfirst($m->type) }}</span></td>
                            <td>{{ $m->description }}</td>
                            <td>{{ $m->meter_hours ? number_format($m->meter_hours) : '—' }}</td>
                            <td class="text-right">{{ number_format($m->cost, 2) }}</td>
                            <td>{{ $m->vendor ?? '—' }}</td>
                            <td><span class="badge badge-{{ $m->status === 'completed' ? 'success' : ($m->status === 'in-progress' ? 'warning' : 'secondary') }}">{{ ucfirst($m->status) }}</span></td>
                            <td>
                                <form action="{{ route('admin.hr.equipment.maintenance.destroy', $m) }}" method="POST" class="inline" onsubmit="return confirm('Delete this record?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-gray-400 py-4">No maintenance records.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
