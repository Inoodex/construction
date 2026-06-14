@extends('admin.layouts.master')

@section('title', 'Review Approval')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Review Approval</h2>
        <a href="{{ route('admin.approvals.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="panel lg:col-span-2">
            <h5 class="mb-4 text-base font-semibold">Approval Details</h5>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="text-xs text-white-dark">Document Type</label>
                    <p class="font-semibold capitalize">{{ str_replace('_', ' ', $approval->workflow->document_type) }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Status</label>
                    <div>
                        @php $sc = ['pending' => 'badge-outline-warning', 'approved' => 'badge-outline-success', 'rejected' => 'badge-outline-danger', 'withdrawn' => 'badge-outline-secondary']; @endphp
                        <span class="badge {{ $sc[$approval->status] ?? 'badge-outline-secondary' }} capitalize">{{ $approval->status }}</span>
                    </div>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Total Amount</label>
                    <p class="font-semibold">৳{{ number_format($approval->total_amount, 2) }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Current Level</label>
                    <p class="font-semibold">Level {{ $approval->current_level }} of {{ $approval->workflow->matrices->max('approval_level') }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Submitted By</label>
                    <p class="font-semibold">{{ $approval->submitter->name }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Submitted Date</label>
                    <p class="text-xs font-semibold">{{ $approval->submitted_at->format('d M Y h:i A') }}</p>
                </div>
            </div>
        </div>

        <div class="panel">
            <h5 class="mb-4 text-base font-semibold">Your Action</h5>
            @if($canApprove && $approval->status === 'pending')
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-white-dark">Comment</label>
                        <textarea id="approvalComment" class="form-textarea mt-1" rows="3" placeholder="Add any comments..."></textarea>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" class="btn btn-primary flex-1" id="approveBtn">Approve</button>
                        <button type="button" class="btn btn-danger flex-1" id="rejectBtn">Reject</button>
                    </div>
                </div>
            @else
                <div class="rounded-lg bg-warning/10 p-3 text-sm text-warning">
                    @if($approval->status !== 'pending')
                        This request has been {{ $approval->status }}.
                    @else
                        You are not authorized to approve this request.
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="panel mt-6">
        <h5 class="mb-4 text-base font-semibold">Approval History</h5>
        @if($approval->history->count() > 0)
            <div class="space-y-3">
                @foreach($approval->history as $record)
                    <div class="flex items-start gap-3 rounded-lg border p-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full text-white text-sm font-bold
                            {{ $record->status === 'approved' ? 'bg-success' : 'bg-danger' }}">
                            {{ substr($record->approver->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold">
                                {{ ucfirst($record->status) }} by {{ $record->approver->name }}
                                <span class="text-xs font-normal text-gray-500">at Level {{ $record->approval_level }}</span>
                            </p>
                            <p class="text-xs text-gray-500">{{ $record->approved_at->format('d M Y h:i A') }}</p>
                            @if($record->comment)
                                <p class="mt-1 text-xs text-gray-600 italic">"{{ $record->comment }}"</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-sm text-gray-500">No approval history yet.</p>
        @endif
    </div>

    <div class="panel mt-6">
        <h5 class="mb-4 text-base font-semibold">Approvers at Current Level</h5>
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($approval->getCurrentLevelApprovers() as $approver)
                <div class="flex items-center gap-3 rounded-lg border p-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-sm font-bold text-primary">
                        {{ substr($approver->name, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold">{{ $approver->name }}</p>
                        <p class="text-xs text-gray-500">{{ $approver->email }}</p>
                    </div>
                    @php
                        $hasApproved = $approval->history
                            ->where('approved_by', $approver->id)
                            ->where('approval_level', $approval->current_level)
                            ->where('status', 'approved')
                            ->count() > 0;
                    @endphp
                    <span class="badge {{ $hasApproved ? 'badge-outline-success' : 'badge-outline-warning' }}">
                        {{ $hasApproved ? 'Approved' : 'Pending' }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('approveBtn')?.addEventListener('click', function() {
    const comment = document.getElementById('approvalComment').value;
    fetch('{{ route('admin.approvals.approve', $approval) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ comment })
    })
    .then(r => r.json())
    .then(d => { alert(d.message); if(d.success) location.reload(); })
    .catch(e => alert('Error: ' + e));
});

document.getElementById('rejectBtn')?.addEventListener('click', function() {
    const comment = document.getElementById('approvalComment').value;
    if(!comment.trim()) { alert('Please provide a reason for rejection'); return; }
    fetch('{{ route('admin.approvals.reject', $approval) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ comment })
    })
    .then(r => r.json())
    .then(d => { alert(d.message); if(d.success) location.reload(); })
    .catch(e => alert('Error: ' + e));
});
</script>
@endpush
