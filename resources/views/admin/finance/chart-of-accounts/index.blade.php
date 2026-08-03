@extends('admin.layouts.master')

@section('title', 'Chart of Accounts')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Chart of Accounts</h5>
        <a href="{{ route('admin.finance.chart-of-accounts.create') }}" class="btn btn-primary">+ Add Account</a>
    </div>

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
                                <x-action-dropdown>
                                    <x-slot name="items">
                                        <li><a href="{{ route('admin.finance.chart-of-accounts.edit', $acc) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/></svg> Edit</a></li>
                                        <li class="divider"></li>
                                        <li class="danger">
                                            <form action="{{ route('admin.finance.chart-of-accounts.destroy', $acc) }}" method="POST" onsubmit="return confirm('Delete this account?');">
                                                @csrf @method('DELETE')
                                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 001-1h4a1 1 0 001 1m-6 0h6"/></svg> Delete</button>
                                            </form>
                                        </li>
                                    </x-slot>
                                </x-action-dropdown>
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
