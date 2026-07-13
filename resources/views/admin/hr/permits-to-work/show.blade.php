@extends('admin.layouts.master')

@section('title', 'Permit to Work - ' . $permitToWork->permit_number)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Permit to Work</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.hr.permits-to-work.edit', $permitToWork) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit
            </a>
            <a href="{{ route('admin.hr.permits-to-work.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to List
            </a>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-3 gap-4">
        <div class="panel">
            <h4 class="font-semibold mb-3">Details</h4>
            <table class="w-full text-sm">
                <tr><td class="py-1 text-gray-500 w-36">Permit Number</td><td class="font-bold">{{ $permitToWork->permit_number }}</td></tr>
                <tr><td class="py-1 text-gray-500">Type</td><td>
                    @php
                        $typeBadge = match($permitToWork->permit_type) {
                            'hot_work' => 'badge-outline-danger',
                            'confined_space' => 'badge-outline-warning',
                            'working_at_height' => 'badge-outline-info',
                            'electrical' => 'badge-outline-secondary',
                            'excavation' => 'badge-outline-success',
                            'lifting' => 'badge-outline-info',
                            'radiography' => 'badge-outline-warning',
                            default => 'badge-outline-secondary'
                        };
                    @endphp
                    <span class="badge {{ $typeBadge }}">{{ str_replace('_', ' ', ucfirst($permitToWork->permit_type)) }}</span>
                </td></tr>
                <tr><td class="py-1 text-gray-500">Project</td><td>{{ $permitToWork->project?->name ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Site</td><td>{{ $permitToWork->site?->name ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Location</td><td>{{ $permitToWork->work_location }}</td></tr>
                <tr><td class="py-1 text-gray-500">Status</td><td>
                    @php
                        $stCls = match($permitToWork->status) {
                            'draft' => 'badge-outline-secondary',
                            'pending_approval' => 'badge-outline-warning',
                            'approved' => 'badge-outline-success',
                            'active' => 'badge-outline-success',
                            'completed' => 'badge-outline-info',
                            'cancelled' => 'badge-outline-danger',
                            default => 'badge-outline-secondary'
                        };
                    @endphp
                    <span class="badge {{ $stCls }}">{{ str_replace('_', ' ', ucfirst($permitToWork->status)) }}</span>
                </td></tr>
            </table>
        </div>

        <div class="panel">
            <h4 class="font-semibold mb-3">People</h4>
            <table class="w-full text-sm">
                <tr><td class="py-1 text-gray-500 w-36">Requested By</td><td>{{ $permitToWork->requester?->name ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Approved By</td><td>{{ $permitToWork->approver?->name ?? '—' }}</td></tr>
            </table>
        </div>

        <div class="panel">
            <h4 class="font-semibold mb-3">Validity</h4>
            <table class="w-full text-sm">
                <tr><td class="py-1 text-gray-500 w-36">Valid From</td><td>{{ $permitToWork->valid_from->format('d M Y') }}</td></tr>
                <tr><td class="py-1 text-gray-500">Valid Until</td><td>{{ $permitToWork->valid_until->format('d M Y') }}</td></tr>
                <tr><td class="py-1 text-gray-500">Created</td><td>{{ $permitToWork->created_at->format('d M Y H:i') }}</td></tr>
            </table>
        </div>
    </div>

    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Description of Work</h4>
        <p class="text-sm whitespace-pre-wrap">{{ $permitToWork->description_of_work }}</p>
    </div>

    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Hazards Identified</h4>
        <p class="text-sm whitespace-pre-wrap">{{ $permitToWork->hazards_identified }}</p>
    </div>

    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Safety Measures</h4>
        <p class="text-sm whitespace-pre-wrap">{{ $permitToWork->safety_measures }}</p>
    </div>

    @if($permitToWork->conditions)
    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Conditions</h4>
        <p class="text-sm whitespace-pre-wrap">{{ $permitToWork->conditions }}</p>
    </div>
    @endif

    @if($permitToWork->cancellation_reason)
    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Cancellation Reason</h4>
        <p class="text-sm whitespace-pre-wrap text-danger">{{ $permitToWork->cancellation_reason }}</p>
    </div>
    @endif
@endsection
