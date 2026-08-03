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
                                <x-action-dropdown>
                                    <x-slot name="items">
                                        <li><a href="{{ route('admin.procurement.subcontract-progress-payments.show', $p->id) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                        <li><a href="{{ route('admin.procurement.subcontract-progress-payments.pdf', $p->id) }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> PDF</a></li>
                                    </x-slot>
                                </x-action-dropdown>
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
