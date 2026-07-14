@extends('admin.layouts.master')

@section('title', 'Progress Payment Certificates')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Progress Payment Certificates</h2>
        <div class="flex w-full flex-wrap items-center justify-end gap-4 sm:w-auto">
            <a href="{{ route('admin.procurement.subcontract-progress-payments.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                New Certificate
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form method="GET" class="flex items-center gap-3 w-full">
                <select name="agreement_id" class="form-select flex-1">
                    <option value="">All Agreements</option>
                    @foreach($agreements as $a)
                        <option value="{{ $a->id }}" {{ request('agreement_id') == $a->id ? 'selected' : '' }}>{{ $a->agreement_number }} — {{ $a->subcontractor->name ?? 'N/A' }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select flex-1">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="certified" {{ request('status') == 'certified' ? 'selected' : '' }}>Certified</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['status', 'agreement_id']))
                    <a href="{{ route('admin.procurement.subcontract-progress-payments.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Certificate #</th>
                        <th>Agreement</th>
                        <th>Subcontractor</th>
                        <th>Period</th>
                        <th>Work Value</th>
                        <th>Net Payable</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $p)
                        <tr>
                            <td class="font-mono text-xs font-semibold text-primary">{{ $p->certificate_number }}</td>
                            <td class="text-xs">{{ $p->agreement->agreement_number ?? '—' }}</td>
                            <td class="text-xs">{{ $p->agreement->subcontractor->name ?? '—' }}</td>
                            <td class="text-xs">{{ $p->period_start->format('d M') }} — {{ $p->period_end->format('d M Y') }}</td>
                            <td class="font-semibold">৳{{ number_format($p->work_completed_value) }}</td>
                            <td class="font-semibold text-success">৳{{ number_format($p->net_payable) }}</td>
                            <td>
                                @php $sc = ['draft' => 'badge-outline-secondary', 'submitted' => 'badge-outline-info', 'certified' => 'badge-outline-success', 'paid' => 'badge-outline-primary']; @endphp
                                <span class="badge {{ $sc[$p->status] ?? 'badge-outline-secondary' }} capitalize">{{ $p->status }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.procurement.subcontract-progress-payments.show', $p->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                <a href="{{ route('admin.procurement.subcontract-progress-payments.pdf', $p->id) }}" target="_blank" class="btn btn-sm btn-outline-success">PDF</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center">No progress payment certificates found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $payments->links() }}</div>
    </div>
@endsection
