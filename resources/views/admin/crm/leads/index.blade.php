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
                            <a href="{{ route('admin.crm.leads.show', $lead) }}" class="btn btn-sm btn-outline-info">View</a>
                            <a href="{{ route('admin.crm.leads.edit', $lead) }}" class="btn btn-sm btn-outline-primary">Edit</a>
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
