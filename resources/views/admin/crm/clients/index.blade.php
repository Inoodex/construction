@extends('admin.layouts.master')

@section('title', 'Clients')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Clients</h5>
        <a href="{{ route('admin.crm.clients.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                New Client
            </a>
    </div>

    <form method="GET" class="mb-4 flex items-center gap-3">
        <input type="text" name="search" class="form-input w-auto" placeholder="Search by company, contact, email..." value="{{ request('search') }}" />
        <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->anyFilled(['search', 'status']))
            <a href="{{ route('admin.crm.clients.index') }}" class="btn btn-outline-danger">Reset</a>
        @endif
    </form>

    <div class="table-responsive">
        <table class="table-hover table">
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Contact Person</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                    <tr>
                        <td class="font-semibold">{{ $client->company_name }}</td>
                        <td class="text-xs">{{ $client->contact_person ?? '—' }}</td>
                        <td class="text-xs">{{ $client->email ?? '—' }}</td>
                        <td class="text-xs">{{ $client->phone ?? '—' }}</td>
                        <td><span class="badge {{ $client->status == 'active' ? 'badge-outline-success' : 'badge-outline-secondary' }} capitalize">{{ $client->status }}</span></td>
                         <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.crm.clients.show', $client) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.crm.clients.edit', $client) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.crm.clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Delete this client?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-gray-500">No clients found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $clients->links() }}</div>
</div>
@endsection
