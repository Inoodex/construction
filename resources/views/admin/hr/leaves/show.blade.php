@extends('admin.layouts.master')

@section('title', 'Leave #' . $leave->id)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Leave Request #{{ $leave->id }}</h2>
        <a href="{{ route('admin.hr.leaves.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-100 p-3 text-green-700">{{ session('success') }}</div>
    @endif

    <div class="panel mt-6">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div><span class="text-xs text-white-dark">Employee</span><p class="font-semibold">{{ $leave->employee->full_name }}</p></div>
            <div><span class="text-xs text-white-dark">Leave Type</span><p><span class="badge badge-outline-info capitalize">{{ $leave->leave_type }}</span></p></div>
            <div><span class="text-xs text-white-dark">From</span><p>{{ $leave->start_date->format('d M Y') }}</p></div>
            <div><span class="text-xs text-white-dark">To</span><p>{{ $leave->end_date->format('d M Y') }}</p></div>
            <div><span class="text-xs text-white-dark">Days</span><p>{{ $leave->start_date->diffInDays($leave->end_date) + 1 }}</p></div>
            <div>
                <span class="text-xs text-white-dark">Status</span>
                @php $cls = match($leave->status) { 'approved' => 'badge-outline-success', 'pending' => 'badge-outline-warning', 'rejected' => 'badge-outline-danger', default => 'badge-outline-secondary' }; @endphp
                <span class="badge {{ $cls }} capitalize">{{ $leave->status }}</span>
            </div>
            <div><span class="text-xs text-white-dark">Approved By</span><p>{{ $leave->approver?->name ?? '—' }}</p></div>
            @if($leave->reason)
                <div class="md:col-span-4"><span class="text-xs text-white-dark">Reason</span><p>{{ $leave->reason }}</p></div>
            @endif
            @if($leave->remarks)
                <div class="md:col-span-4"><span class="text-xs text-white-dark">Remarks</span><p>{{ $leave->remarks }}</p></div>
            @endif
        </div>

        @if($leave->status === 'pending')
            <div class="mt-6 flex items-center gap-3 border-t border-gray-200 pt-4">
                <form action="{{ route('admin.hr.leaves.approve', $leave) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="remarks" value="" />
                    <button type="submit" class="btn btn-success">Approve</button>
                </form>
                <form action="{{ route('admin.hr.leaves.reject', $leave) }}" method="POST" onsubmit="return confirm('Reject this leave?');">
                    @csrf @method('PATCH')
                    <div class="flex items-center gap-2">
                        <input type="text" name="remarks" class="form-input" placeholder="Rejection reason..." />
                        <button type="submit" class="btn btn-danger">Reject</button>
                    </div>
                </form>
                <form action="{{ route('admin.hr.leaves.destroy', $leave) }}" method="POST" onsubmit="return confirm('Delete this request?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">Delete</button>
                </form>
            </div>
        @endif
    </div>
@endsection
