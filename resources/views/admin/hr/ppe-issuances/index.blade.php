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
                        <td class="text-center">
                            <x-action-dropdown>
                                <x-slot name="items">
                                    <li><a href="{{ route('admin.hr.ppe-issuances.show', $r) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                    <li><a href="{{ route('admin.hr.ppe-issuances.edit', $r) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/></svg> Edit</a></li>
                                    <li class="divider"></li>
                                    <li class="danger">
                                        <form action="{{ route('admin.hr.ppe-issuances.destroy', $r) }}" method="POST" onsubmit="return confirm('Delete this record?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 001-1h4a1 1 0 001 1m-6 0h6"/></svg> Delete</button>
                                        </form>
                                    </li>
                                </x-slot>
                            </x-action-dropdown>
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
