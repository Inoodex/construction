@extends('admin.layouts.master')

@section('title', $subcontractProgressPayment->certificate_number)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">{{ $subcontractProgressPayment->certificate_number }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.procurement.subcontract-progress-payments.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="panel lg:col-span-2">
            <h5 class="mb-4 text-base font-semibold">Certificate Details</h5>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="text-xs text-white-dark">Certificate #</label>
                    <p class="font-mono font-semibold text-primary">{{ $subcontractProgressPayment->certificate_number }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Status</label>
                    <div>
                        @php $sc = ['draft' => 'badge-outline-secondary', 'submitted' => 'badge-outline-info', 'certified' => 'badge-outline-success', 'paid' => 'badge-outline-primary']; @endphp
                        <span class="badge {{ $sc[$subcontractProgressPayment->status] ?? 'badge-outline-secondary' }} capitalize">{{ $subcontractProgressPayment->status }}</span>
                    </div>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Agreement</label>
                    <p class="font-semibold">{{ $subcontractProgressPayment->agreement->agreement_number ?? '—' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Subcontractor</label>
                    <p class="font-semibold">{{ $subcontractProgressPayment->agreement->subcontractor->name ?? '—' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Period</label>
                    <p class="font-semibold">{{ $subcontractProgressPayment->period_start->format('d M Y') }} — {{ $subcontractProgressPayment->period_end->format('d M Y') }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Project</label>
                    <p class="font-semibold">{{ $subcontractProgressPayment->agreement->project->name ?? '—' }}</p>
                </div>
            </div>
        </div>

        <div class="panel">
            <h5 class="mb-4 text-base font-semibold">Payment Summary</h5>
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600">Work Completed</span>
                    <span class="text-sm font-bold">৳{{ number_format($subcontractProgressPayment->work_completed_value) }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600">Less Retention</span>
                    <span class="text-sm font-bold text-danger">−৳{{ number_format($subcontractProgressPayment->retention_amount) }}</span>
                </div>
                @if($subcontractProgressPayment->retention_released > 0)
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600">Retention Released</span>
                    <span class="text-sm font-bold text-success">+৳{{ number_format($subcontractProgressPayment->retention_released) }}</span>
                </div>
                @endif
                <div class="flex items-center justify-between rounded-lg bg-success/10 p-3">
                    <span class="text-xs font-semibold text-success">Net Payable</span>
                    <span class="text-lg font-bold text-success">৳{{ number_format($subcontractProgressPayment->net_payable + ($subcontractProgressPayment->retention_released ?? 0)) }}</span>
                </div>
                <hr class="my-1 border-white-light dark:border-dark" />
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600">Previous Certified</span>
                    <span class="text-sm font-bold">৳{{ number_format($subcontractProgressPayment->previous_certified_value) }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600">Total Certified to Date</span>
                    <span class="text-sm font-bold">৳{{ number_format($subcontractProgressPayment->total_certified_to_date) }}</span>
                </div>
            </div>

            @if($subcontractProgressPayment->status === 'draft')
                <form action="{{ route('admin.procurement.subcontract-progress-payments.status', $subcontractProgressPayment) }}" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="status" value="submitted" />
                    <button type="submit" class="btn btn-info w-full">Submit for Certification</button>
                </form>
            @elseif($subcontractProgressPayment->status === 'submitted')
                <div class="mt-4 space-y-2">
                    <form action="{{ route('admin.procurement.subcontract-progress-payments.status', $subcontractProgressPayment) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="certified" />
                        <button type="submit" class="btn btn-success w-full">Certify Payment</button>
                    </form>
                </div>
            @elseif($subcontractProgressPayment->status === 'certified')
                <div class="mt-4 space-y-2">
                    <form action="{{ route('admin.procurement.subcontract-progress-payments.status', $subcontractProgressPayment) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="paid" />
                        <div class="form-group">
                            <label class="text-xs">Retention Released (৳)</label>
                            <input type="number" step="0.01" min="0" name="retention_released" class="form-input" value="{{ $subcontractProgressPayment->retention_amount }}" />
                        </div>
                        <button type="submit" class="btn btn-primary w-full mt-2">Mark as Paid</button>
                    </form>
                </div>
            @endif
            @if(in_array($subcontractProgressPayment->status, ['draft', 'submitted']))
                <div class="mt-2">
                    <form action="{{ route('admin.procurement.subcontract-progress-payments.destroy', $subcontractProgressPayment) }}" method="POST" onsubmit="return confirm('Delete this certificate?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-full">Delete</button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    @if($subcontractProgressPayment->certified_by)
    <div class="panel mt-6">
        <h5 class="mb-3 text-base font-semibold">Certification Info</h5>
        <p class="text-sm">Certified by <span class="font-semibold">{{ $subcontractProgressPayment->certifier->name ?? 'Unknown' }}</span> on {{ $subcontractProgressPayment->certified_at->format('d M Y h:i A') }}</p>
    </div>
    @endif

    @if($subcontractProgressPayment->notes)
    <div class="panel mt-4">
        <h5 class="mb-3 text-base font-semibold">Notes</h5>
        <p class="text-sm">{{ $subcontractProgressPayment->notes }}</p>
    </div>
    @endif
@endsection
