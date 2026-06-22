@extends('admin.layouts.master')

@section('title', $equipment->name)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">{{ $equipment->name }}</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.hr.equipment.edit', $equipment) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit
            </a>
            <a href="{{ route('admin.hr.equipment.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to List
            </a>
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
                    <tr><td class="py-1 text-gray-500">Type</td><td><span class="badge badge-outline-info capitalize">{{ $equipment->acquisition_type }}</span></td></tr>
                    <tr><td class="py-1 text-gray-500">Status</td><td>
                        <span class="badge {{ $equipment->status === 'active' ? 'badge-outline-success' : ($equipment->status === 'under-maintenance' ? 'badge-outline-warning' : 'badge-outline-secondary') }} capitalize">{{ $equipment->status === 'under-maintenance' ? 'Maint' : $equipment->status }}</span>
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
                    @if($equipment->acquisition_type === 'hired')
                        <tr class="border-t"><td colspan="2" class="py-1 font-semibold text-info pt-2">Hire Details</td></tr>
                        <tr><td class="py-1 text-gray-500">Hire Rate</td><td>{{ $equipment->hire_rate ? number_format($equipment->hire_rate, 2) . ($equipment->hire_rate_period ? ' / ' . $equipment->hire_rate_period : '') : '—' }}</td></tr>
                        <tr><td class="py-1 text-gray-500">Hire Period</td><td>{{ $equipment->hire_start_date?->format('d M Y') ?? '—' }} — {{ $equipment->hire_end_date?->format('d M Y') ?? 'Ongoing' }}</td></tr>
                        <tr><td class="py-1 text-gray-500">Hire Vendor</td><td>{{ $equipment->hire_vendor ?? '—' }}</td></tr>
                    @endif
                </table>
            </div>
            <div>
                <h4 class="font-semibold mb-3">Usage & Allocation</h4>
                <table class="w-full text-sm">
                    <tr><td class="py-1 text-gray-500 w-28">Location</td><td>{{ $equipment->location ?? '—' }}</td></tr>
                    <tr><td class="py-1 text-gray-500">Project</td><td>{{ $equipment->project?->name ?? '—' }}</td></tr>
                    <tr><td class="py-1 text-gray-500">Site</td><td>{{ $equipment->site?->name ?? '—' }}</td></tr>
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
