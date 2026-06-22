@extends('admin.layouts.master')

@section('title', 'Certifications & Licences')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Certifications & Licences</h2>
        <a href="{{ route('admin.hr.certifications.create') }}" class="btn btn-primary gap-2">+ Add Certification</a>
    </div>

    <div class="panel mt-6">
        <form method="GET" class="mb-4 flex flex-nowrap items-end gap-2 overflow-x-auto">
            <div>
                <label class="text-xs font-semibold">Employee</label>
                <select name="employee_id" class="form-select" style="min-width: 200px">
                    <option value="">All Employees</option>
                    @foreach($employees as $id => $name)
                        <option value="{{ $id }}" {{ request('employee_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold">Category</label>
                <select name="category" class="form-select" style="min-width: 200px">
                    <option value="">All Categories</option>
                    <option value="certification" {{ request('category') == 'certification' ? 'selected' : '' }}>Certification</option>
                    <option value="license" {{ request('category') == 'license' ? 'selected' : '' }}>License</option>
                    <option value="permit" {{ request('category') == 'permit' ? 'selected' : '' }}>Permit</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold">Status</label>
                <select name="status" class="form-select" style="min-width: 200px">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="revoked" {{ request('status') == 'revoked' ? 'selected' : '' }}>Revoked</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['employee_id', 'category', 'status']))
                    <a href="{{ route('admin.hr.certifications.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Authority</th>
                        <th>Certificate #</th>
                        <th>Issue Date</th>
                        <th>Expiry</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $r)
                        <tr>
                            <td>{{ $r->employee->full_name }}</td>
                            <td>{{ $r->certification_name }}</td>
                            <td><span class="badge badge-outline-{{ $r->category === 'license' ? 'warning' : ($r->category === 'permit' ? 'info' : 'secondary') }}">{{ ucfirst($r->category) }}</span></td>
                            <td>{{ $r->issuing_authority ?? '—' }}</td>
                            <td class="font-mono text-xs">{{ $r->certificate_no ?? '—' }}</td>
                            <td>{{ $r->issue_date->format('d M Y') }}</td>
                            <td>{{ $r->expiry_date?->format('d M Y') ?? '—' }}</td>
                            <td>
                                @php
                                    $cls = match($r->status) {
                                        'active' => 'badge-outline-success',
                                        'expired' => 'badge-outline-danger',
                                        'suspended' => 'badge-outline-warning',
                                        default => 'badge-outline-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $cls }}">{{ ucfirst($r->status) }}</span>
                            </td>
                            <td class="flex items-center gap-1">
                                <a href="{{ route('admin.hr.certifications.show', $r) }}" class="btn btn-sm btn-outline-info">View</a>
                                <a href="{{ route('admin.hr.certifications.edit', $r) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form action="{{ route('admin.hr.certifications.destroy', $r) }}" method="POST" class="inline-flex" onsubmit="return confirm('Delete this record?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-gray-400 py-4">No certifications found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $records->links() }}</div>
    </div>
@endsection
