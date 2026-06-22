@extends('admin.layouts.master')

@section('title', 'Equipment Registry')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Equipment Registry</h2>
        <a href="{{ route('admin.hr.equipment.create') }}" class="btn btn-primary gap-2">+ Register Equipment</a>
    </div>

    <div class="panel mt-6">
        <form method="GET" class="mb-4 flex flex-nowrap items-end gap-2 overflow-x-auto">
            <div>
                <label class="text-xs font-semibold">Search</label>
                <input type="text" name="search" class="form-input" placeholder="Name / Code / Serial" value="{{ request('search') }}" />
            </div>
            <div>
                <label class="text-xs font-semibold">Category</label>
                <select name="category" class="form-select">
                    <option value="">All</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="under-maintenance" {{ request('status') == 'under-maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                    <option value="retired" {{ request('status') == 'retired' ? 'selected' : '' }}>Retired</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold">Type</label>
                <select name="acquisition_type" class="form-select">
                    <option value="">All</option>
                    <option value="owned" {{ request('acquisition_type') == 'owned' ? 'selected' : '' }}>Owned</option>
                    <option value="hired" {{ request('acquisition_type') == 'hired' ? 'selected' : '' }}>Hired</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold">Project</label>
                <select name="project_id" class="form-select">
                    <option value="">All</option>
                    @foreach($projects as $id => $name)
                        <option value="{{ $id }}" {{ request('project_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold">Site</label>
                <select name="site_id" class="form-select">
                    <option value="">All</option>
                    @foreach($sites as $id => $name)
                        <option value="{{ $id }}" {{ request('site_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['search', 'category', 'status', 'acquisition_type', 'project_id', 'site_id']))
                    <a href="{{ route('admin.hr.equipment.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Project</th>
                        <th>Site</th>
                        <th>Type</th>
                        <th>Meter Hrs</th>
                        <th class="text-right">Cost</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipment as $eq)
                        <tr>
                            <td class="font-mono text-xs">{{ $eq->code }}</td>
                            <td class="font-semibold">{{ $eq->name }}</td>
                            <td>{{ $eq->category ?? '—' }}</td>
                            <td class="text-xs">{{ $eq->project?->name ?? '—' }}</td>
                            <td class="text-xs">{{ $eq->site?->name ?? '—' }}</td>
                            <td><span class="badge badge-outline-info capitalize">{{ $eq->acquisition_type }}</span></td>
                            <td>{{ number_format($eq->meter_hours) }}</td>
                            <td class="text-right">{{ number_format($eq->purchase_cost, 0) }}</td>
                            <td>
                                @php
                                    $statusClass = match($eq->status) {
                                        'active' => 'badge-outline-success',
                                        'under-maintenance' => 'badge-outline-warning',
                                        'retired' => 'badge-outline-secondary',
                                        default => 'badge-outline-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} capitalize">{{ $eq->status === 'under-maintenance' ? 'Maint' : $eq->status }}</span>
                            </td>
                            <td class="flex gap-1">
                                <a href="{{ route('admin.hr.equipment.show', $eq) }}" class="btn btn-sm btn-outline-info">View</a>
                                <a href="{{ route('admin.hr.equipment.edit', $eq) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form action="{{ route('admin.hr.equipment.destroy', $eq) }}" method="POST" class="inline" onsubmit="return confirm('Delete this equipment?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="text-center text-gray-400 py-4">No equipment registered.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $equipment->links() }}</div>
    </div>
@endsection
