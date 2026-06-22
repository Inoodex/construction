@extends('admin.layouts.master')

@section('title', 'PPE Issuances')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">PPE Issuances</h5>
        <a href="{{ route('admin.hr.ppe-issuances.create') }}" class="btn btn-primary">+ New Issuance</a>
    </div>

    <form method="GET" class="mb-4 flex flex-nowrap items-center gap-2 overflow-x-auto">
        <select name="employee_id" class="form-select" onchange="this.form.submit()">
            <option value="">All Employees</option>
            @foreach($employees as $id => $name)
                <option value="{{ $id }}" {{ request('employee_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
        <select name="category" class="form-select" onchange="this.form.submit()">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        <select name="returned" class="form-select" onchange="this.form.submit()">
            <option value="">All Items</option>
            <option value="no" {{ request('returned') == 'no' ? 'selected' : '' }}>Not Returned</option>
            <option value="yes" {{ request('returned') == 'yes' ? 'selected' : '' }}>Returned</option>
        </select>
        @if(request()->anyFilled(['employee_id', 'category', 'returned']))
            <a href="{{ route('admin.hr.ppe-issuances.index') }}" class="btn btn-outline-danger btn-sm">Reset</a>
        @endif
    </form>

    <div class="overflow-x-auto">
        <table class="table-hover w-full table-auto">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Item</th>
                    <th>Category</th>
                    <th>Qty</th>
                    <th>Size</th>
                    <th>Issue Date</th>
                    <th>Return Date</th>
                    <th>Status</th>
                    <th style="text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $r)
                    <tr>
                        <td>{{ $r->employee->full_name }}</td>
                        <td>{{ $r->item_name }}</td>
                        <td>{{ $r->category ?? '—' }}</td>
                        <td>{{ $r->quantity }}</td>
                        <td>{{ $r->size ?? '—' }}</td>
                        <td>{{ $r->issue_date->format('d M Y') }}</td>
                        <td>{{ $r->return_date?->format('d M Y') ?? '—' }}</td>
                        <td>
                            @if($r->return_date)
                                <span class="badge badge-outline-success">Returned</span>
                            @else
                                <span class="badge badge-outline-warning">Issued</span>
                            @endif
                        </td>
                        <td class="flex gap-1" style="justify-content: center;">
                            <a href="{{ route('admin.hr.ppe-issuances.show', $r) }}" class="btn btn-sm btn-outline-info">View</a>
                            <a href="{{ route('admin.hr.ppe-issuances.edit', $r) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="{{ route('admin.hr.ppe-issuances.destroy', $r) }}" method="POST" class="inline" onsubmit="return confirm('Delete this record?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-gray-400 py-4">No PPE issuances found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $records->links() }}</div>
</div>
@endsection
