@extends('admin.layouts.master')

@section('title', $materialSubmittal->submittal_number)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">{{ $materialSubmittal->submittal_number }}</h2>
        <div class="flex items-center gap-2">
            @if($materialSubmittal->status === 'draft')
                <a href="{{ route('admin.procurement.material-submittals.edit', $materialSubmittal) }}" class="btn btn-primary gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Edit
                </a>
                <form action="{{ route('admin.procurement.material-submittals.submit', $materialSubmittal) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success gap-2">Submit for Review</button>
                </form>
                <form action="{{ route('admin.procurement.material-submittals.destroy', $materialSubmittal) }}" method="POST" class="inline" onsubmit="return confirm('Delete this submittal?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">Delete</button>
                </form>
            @endif
            @if($materialSubmittal->status === 'submitted')
                <button type="button" class="btn btn-outline-info gap-2" onclick="document.getElementById('review-form').classList.toggle('hidden')">Review</button>
            @endif
            @if($materialSubmittal->status === 'rejected')
                <a href="{{ route('admin.procurement.material-submittals.resubmit-form', $materialSubmittal) }}" class="btn btn-outline-warning gap-2">Resubmit</a>
            @endif
            @if(in_array($materialSubmittal->status, ['approved', 'approved_with_conditions', 'rejected']))
                <form action="{{ route('admin.procurement.material-submittals.destroy', $materialSubmittal) }}" method="POST" class="inline" onsubmit="return confirm('Delete this submittal?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">Delete</button>
                </form>
            @endif
            <a href="{{ route('admin.procurement.material-submittals.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to List
            </a>
        </div>
    </div>

    @if($materialSubmittal->status === 'submitted')
    <div id="review-form" class="panel mt-4 hidden">
        <h3 class="text-lg font-semibold mb-4">Review Submittal</h3>
        <form action="{{ route('admin.procurement.material-submittals.review', $materialSubmittal) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-group">
                    <label for="action">Decision <span class="text-danger">*</span></label>
                    <select name="action" id="action" class="form-select" required onchange="toggleDeadline(this.value)">
                        <option value="">Select decision</option>
                        <option value="approved">Approved</option>
                        <option value="approved_with_conditions">Approved with Conditions</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="form-group hidden" id="deadline-group">
                    <label for="resubmission_deadline">Resubmission Deadline</label>
                    <input type="date" name="resubmission_deadline" id="resubmission_deadline" class="form-input" />
                </div>
                <div class="form-group md:col-span-2">
                    <label for="review_comments">Review Comments</label>
                    <textarea name="review_comments" id="review_comments" class="form-textarea" rows="4" placeholder="Provide feedback, conditions for approval, or reason for rejection..."></textarea>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-3">
                <button type="submit" class="btn btn-primary">Submit Review</button>
                <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('review-form').classList.add('hidden')">Cancel</button>
            </div>
        </form>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <div class="panel lg:col-span-2">
            <h3 class="text-lg font-semibold mb-4">{{ $materialSubmittal->title }}</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-white-dark">Submittal #:</span> {{ $materialSubmittal->submittal_number }}</div>
                <div>
                    <span class="text-white-dark">Status:</span>
                    @php
                        $sc = match($materialSubmittal->status) {
                            'draft' => 'badge-outline-secondary',
                            'submitted' => 'badge-outline-info',
                            'under_review' => 'badge-outline-warning',
                            'approved' => 'badge-outline-success',
                            'approved_with_conditions' => 'badge-outline-primary',
                            'rejected' => 'badge-outline-danger',
                            'resubmitted' => 'badge-outline-dark',
                            default => 'badge-outline-secondary',
                        };
                    @endphp
                    <span class="badge {{ $sc }} capitalize">{{ str_replace('_', ' ', $materialSubmittal->status) }}</span>
                </div>
                <div><span class="text-white-dark">Material:</span> {{ $materialSubmittal->material_name }}</div>
                <div><span class="text-white-dark">Quantity / Unit:</span> {{ $materialSubmittal->quantity_unit ?? '-' }}</div>
                <div><span class="text-white-dark">Manufacturer:</span> {{ $materialSubmittal->manufacturer ?? '-' }}</div>
                <div><span class="text-white-dark">Brand:</span> {{ $materialSubmittal->brand ?? '-' }}</div>
                <div><span class="text-white-dark">Model / Ref:</span> {{ $materialSubmittal->model_reference ?? '-' }}</div>
                <div><span class="text-white-dark">Project:</span> {{ $materialSubmittal->project?->name ?? '-' }}</div>
            </div>

            @if($materialSubmittal->description)
                <div class="mt-4">
                    <h4 class="font-semibold text-sm text-white-dark">Description</h4>
                    <p class="mt-1">{{ $materialSubmittal->description }}</p>
                </div>
            @endif

            @if($materialSubmittal->specification_details)
                <div class="mt-4">
                    <h4 class="font-semibold text-sm text-white-dark">Specification Details</h4>
                    <p class="mt-1 whitespace-pre-wrap">{{ $materialSubmittal->specification_details }}</p>
                </div>
            @endif

            @if($materialSubmittal->submitted_date)
                <div class="mt-4 text-xs text-white-dark">
                    Submitted by {{ $materialSubmittal->submitter?->name ?? 'N/A' }} on {{ $materialSubmittal->submitted_date->format('d/m/Y') }}
                </div>
            @endif
        </div>

        <div class="panel">
            <h3 class="text-lg font-semibold mb-4">Review History</h3>
            @if($materialSubmittal->reviewed_by)
                <div class="space-y-3">
                    <div class="text-sm">
                        <span class="text-white-dark">Reviewed by:</span>
                        <span class="font-semibold">{{ $materialSubmittal->reviewer?->name ?? '-' }}</span>
                    </div>
                    <div class="text-sm">
                        <span class="text-white-dark">Review date:</span>
                        <span>{{ $materialSubmittal->review_date?->format('d/m/Y') ?? '-' }}</span>
                    </div>
                    <div class="text-sm">
                        <span class="text-white-dark">Decision:</span>
                        @php
                            $ds = match($materialSubmittal->status) {
                                'approved' => 'text-green-600',
                                'approved_with_conditions' => 'text-lime-600',
                                'rejected' => 'text-red-600',
                                'resubmitted' => 'text-purple-600',
                                default => '',
                            };
                        @endphp
                        <span class="font-semibold {{ $ds }}">{{ str_replace('_', ' ', ucfirst($materialSubmittal->status)) }}</span>
                    </div>
                    @if($materialSubmittal->review_comments)
                        <div class="mt-2 p-3 bg-gray-50 dark:bg-gray-800 rounded text-sm">
                            <span class="text-white-dark block mb-1">Comments:</span>
                            {{ $materialSubmittal->review_comments }}
                        </div>
                    @endif
                    @if($materialSubmittal->resubmission_deadline)
                        <div class="text-sm">
                            <span class="text-white-dark">Resubmission deadline:</span>
                            <span class="text-danger font-semibold">{{ $materialSubmittal->resubmission_deadline->format('d/m/Y') }}</span>
                        </div>
                    @endif
                </div>
            @else
                <p class="text-white-dark text-center py-4 text-sm">Not yet reviewed.</p>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
function toggleDeadline(value) {
    const group = document.getElementById('deadline-group');
    group.classList.toggle('hidden', value !== 'rejected');
}
</script>
@endpush
