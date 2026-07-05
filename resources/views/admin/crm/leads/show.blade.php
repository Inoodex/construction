@extends('admin.layouts.master')

@section('title', 'Lead - ' . $lead->company_name)

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4">
    <h2 class="text-xl font-semibold uppercase">{{ $lead->company_name }}</h2>
    <div class="flex gap-2">
        @if($lead->status !== 'won' && $lead->status !== 'lost')
            <form action="{{ route('admin.crm.leads.convert', $lead) }}" method="POST" onsubmit="return confirm('Convert this lead to a client?');">
                @csrf
                <button type="submit" class="btn btn-success">Convert to Client</button>
            </form>
        @endif
        <a href="{{ route('admin.crm.leads.index') }}" class="btn btn-secondary">Back to List</a>
        <a href="{{ route('admin.crm.leads.edit', $lead) }}" class="btn btn-primary">Edit</a>
    </div>
</div>

@php $statusColors = ['new' => 'badge-outline-secondary', 'contacted' => 'badge-outline-info', 'proposal_sent' => 'badge-outline-warning', 'negotiation' => 'badge-outline-primary', 'won' => 'badge-outline-success', 'lost' => 'badge-outline-danger']; @endphp

<div class="panel mt-6">
    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <div><span class="text-xs text-white-dark">Company Name</span><p class="font-semibold">{{ $lead->company_name }}</p></div>
        <div><span class="text-xs text-white-dark">Contact Person</span><p>{{ $lead->contact_person ?? '—' }}</p></div>
        <div><span class="text-xs text-white-dark">Email</span><p>{{ $lead->email ?? '—' }}</p></div>
        <div><span class="text-xs text-white-dark">Phone</span><p>{{ $lead->phone ?? '—' }}</p></div>
        <div><span class="text-xs text-white-dark">Source</span><p>{{ $lead->source ?? '—' }}</p></div>
        <div><span class="text-xs text-white-dark">Estimated Value</span><p class="font-mono font-semibold">{{ $lead->estimated_value ? '৳' . number_format($lead->estimated_value) : '—' }}</p></div>
        <div><span class="text-xs text-white-dark">Status</span><p><span class="badge {{ $statusColors[$lead->status] ?? 'badge-outline-secondary' }} capitalize">{{ str_replace('_', ' ', $lead->status) }}</span></p></div>
        <div><span class="text-xs text-white-dark">Assigned To</span><p>{{ $lead->assignedTo?->name ?? 'Unassigned' }}</p></div>
        <div><span class="text-xs text-white-dark">Created By</span><p>{{ $lead->creator?->name ?? '—' }}</p></div>
        <div><span class="text-xs text-white-dark">Last Contacted</span><p>{{ $lead->last_contacted_at?->format('d M Y') ?? '—' }}</p></div>
        <div><span class="text-xs text-white-dark">Next Follow-up</span><p>{{ $lead->next_follow_up_at?->format('d M Y') ?? '—' }}</p></div>
        <div class="md:col-span-4"><span class="text-xs text-white-dark">Description</span><p>{{ $lead->description ?? '—' }}</p></div>
        <div class="md:col-span-4"><span class="text-xs text-white-dark">Notes</span><p>{{ $lead->notes ?? '—' }}</p></div>
    </div>
</div>

<div class="mt-6 panel">
    <div class="mb-3 flex items-center justify-between">
        <h5 class="text-base font-semibold">Communication Log</h5>
        <button type="button" onclick="document.getElementById('addCommForm').classList.toggle('hidden')" class="btn btn-sm btn-outline-primary">+ Log</button>
    </div>

    <div id="addCommForm" class="mb-3 hidden rounded-lg border p-3 dark:border-gray-700">
        <form action="{{ route('admin.crm.leads.communications.store', $lead) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-2 md:grid-cols-4">
                <select name="type" class="form-select text-xs" required>
                    <option value="note">Note</option>
                    <option value="call">Call</option>
                    <option value="email">Email</option>
                    <option value="meeting">Meeting</option>
                </select>
                <input type="text" name="subject" class="form-input text-xs" placeholder="Subject" />
                <input type="date" name="date" class="form-input text-xs" value="{{ date('Y-m-d') }}" required />
                <button type="submit" class="btn btn-sm btn-primary">Log</button>
            </div>
            <div class="mt-2">
                <textarea name="notes" class="form-textarea text-xs" rows="2" placeholder="Notes..."></textarea>
            </div>
        </form>
    </div>

    @if($lead->communications->count() > 0)
        <div class="space-y-2">
            @foreach($lead->communications as $log)
                <div class="rounded-lg border p-2 text-xs dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="badge badge-outline-{{ $log->type == 'call' ? 'info' : ($log->type == 'email' ? 'primary' : ($log->type == 'meeting' ? 'warning' : 'secondary')) }} text-xs capitalize">{{ $log->type }}</span>
                            @if($log->subject) <span class="font-semibold">{{ $log->subject }}</span> @endif
                        </div>
                        <span class="text-white-dark">{{ $log->date->format('d M Y') }} by {{ $log->creator?->name ?? '—' }}</span>
                    </div>
                    @if($log->notes) <p class="mt-1 text-white-dark">{{ $log->notes }}</p> @endif
                </div>
            @endforeach
        </div>
    @else
        <p class="text-xs text-white-dark">No communications logged yet.</p>
    @endif
</div>
@endsection
