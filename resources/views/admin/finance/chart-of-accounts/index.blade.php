@extends('admin.layouts.master')

@section('title', 'Chart of Accounts')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Chart of Accounts</h5>
        <a href="{{ route('admin.finance.chart-of-accounts.create') }}" class="btn btn-primary">+ Add Account</a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-100 p-3 text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-md bg-red-100 p-3 text-red-700">{{ session('error') }}</div>
    @endif

    <form method="GET" class="mb-4 flex items-center gap-3">
        <input type="text" name="search" class="form-input flex-1" placeholder="Search by code or name..." value="{{ request('search') }}" />
        <select name="type" class="form-select flex-1">
            <option value="">All Types</option>
            @foreach($types as $val => $label)
                <option value="{{ $val }}" {{ request('type') == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->anyFilled(['search', 'type']))
            <a href="{{ route('admin.finance.chart-of-accounts.index') }}" class="btn btn-outline-danger">Reset</a>
        @endif
    </form>

    <div class="datatable">
        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Normal Balance</th>
                        <th>Parent</th>
                        <th>Active</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accounts as $acc)
                        <tr>
                            <td class="font-mono text-xs">{{ $acc->account_code }}</td>
                            <td class="font-semibold">{{ $acc->name }}</td>
                            <td><span class="badge badge-outline-primary capitalize">{{ $types[$acc->type] ?? $acc->type }}</span></td>
                            <td class="capitalize">{{ $acc->normal_balance }}</td>
                            <td>{{ $acc->parent?->name ?? '—' }}</td>
                            <td>{!! $acc->is_active ? '<span class="badge badge-outline-success">Yes</span>' : '<span class="badge badge-outline-danger">No</span>' !!}</td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.finance.chart-of-accounts.edit', $acc) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('admin.finance.chart-of-accounts.destroy', $acc) }}" method="POST" onsubmit="return confirm('Delete this account?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-gray-500">No accounts found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $accounts->links() }}</div>
</div>
@endsection
