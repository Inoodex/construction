@extends('admin.layouts.master')

@section('title', 'Leads')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Leads</h5>
        <a href="{{ route('admin.crm.leads.create') }}" class="btn btn-primary">+ New Lead</a>
    </div>

    <form method="GET" class="mb-4 flex items-center gap-3">
        <input type="text" name="search" class="form-input w-auto" placeholder="Search by company, contact, email..." value="{{ request('search') }}" />
        <select name="status" class="form-select">
            <option value="">All Status</option>
            @foreach($statuses as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->anyFilled(['search', 'status']))
            <a href="{{ route('admin.crm.leads.index') }}" class="btn btn-outline-danger">Reset</a>
        @endif
    </form>

    <div class="table-responsive">
        <table class="table-hover table">
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Contact</th>
                    <th>Estimated Value</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Last Contacted</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                    @php $statusColors = ['new' => 'badge-outline-secondary', 'contacted' => 'badge-outline-info', 'proposal_sent' => 'badge-outline-warning', 'negotiation' => 'badge-outline-primary', 'won' => 'badge-outline-success', 'lost' => 'badge-outline-danger']; @endphp
                    <tr>
                        <td class="font-semibold">{{ $lead->company_name }}</td>
                        <td class="text-xs">{{ $lead->contact_person ?? '—' }}<br/><span class="text-white-dark">{{ $lead->email ?? '' }}</span></td>
                        <td class="font-mono text-xs">{{ $lead->estimated_value ? '৳' . number_format($lead->estimated_value) : '—' }}</td>
                        <td><span class="badge {{ $statusColors[$lead->status] ?? 'badge-outline-secondary' }} text-xs capitalize">{{ str_replace('_', ' ', $lead->status) }}</span></td>
                        <td class="text-xs">{{ $lead->assignedTo?->name ?? '—' }}</td>
                        <td class="text-xs">{{ $lead->last_contacted_at?->format('d M Y') ?? '—' }}</td>
                        <td class="text-center">
                            <x-action-dropdown>
                                <x-slot name="items">
                                    <li><a href="{{ route('admin.crm.leads.show', $lead) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                    <li><a href="{{ route('admin.crm.leads.edit', $lead) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/></svg> Edit</a></li>
                                </x-slot>
                            </x-action-dropdown>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-gray-500">No leads found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $leads->links() }}</div>
</div>
@endsection
