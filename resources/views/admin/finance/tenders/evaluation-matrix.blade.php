@extends('admin.layouts.master')

@section('title', 'Bid Evaluation Matrix')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Bid Evaluation Matrix</h2>
        <div class="flex gap-2">
            <button onclick="window.print()" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Print
            </button>
            <a href="{{ route('admin.finance.tenders.show', $tender) }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
            <div>
                <label class="text-xs text-white-dark">Tender Number</label>
                <p class="font-mono font-semibold text-primary">{{ $tender->tender_number }}</p>
            </div>
            <div>
                <label class="text-xs text-white-dark">Title</label>
                <p class="font-semibold">{{ $tender->title }}</p>
            </div>
            <div>
                <label class="text-xs text-white-dark">Project</label>
                <p class="font-semibold">{{ $tender->project->name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-xs text-white-dark">Close Date</label>
                <p class="font-semibold">{{ $tender->close_date->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    @php
        $sortedBids = $tender->bids->filter(fn($b) => $b->total_score !== null)->sortByDesc('total_score');
        $maxScore = $sortedBids->isNotEmpty() ? $sortedBids->first()->total_score : null;
        $minScore = $sortedBids->isNotEmpty() ? $sortedBids->last()->total_score : null;
    @endphp

    <div class="panel mt-6">
        <h5 class="mb-4 text-base font-semibold">Comparison Matrix</h5>
        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Vendor</th>
                        <th>Bid Amount</th>
                        <th>Technical Score</th>
                        <th>Financial Score</th>
                        <th>Total Score</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sortedBids as $index => $bid)
                        @php
                            $rowClass = '';
                            if ($maxScore !== null && $minScore !== null && $maxScore !== $minScore) {
                                if ($bid->total_score == $maxScore) $rowClass = 'bg-green-50 dark:bg-green-900/20';
                                elseif ($bid->total_score == $minScore) $rowClass = 'bg-red-50 dark:bg-red-900/20';
                            }
                        @endphp
                        <tr class="{{ $rowClass }}">
                            <td class="text-center font-bold">{{ $index + 1 }}</td>
                            <td class="font-semibold">{{ $bid->vendor->name ?? 'N/A' }}</td>
                            <td>৳{{ number_format($bid->bid_amount) }}</td>
                            <td class="text-center">{{ $bid->technical_score ?? '-' }}</td>
                            <td class="text-center">{{ $bid->financial_score ?? '-' }}</td>
                            <td class="text-center font-bold">{{ $bid->total_score ?? '-' }}</td>
                            <td>
                                @php $bsc = ['submitted' => 'badge-outline-secondary', 'evaluated' => 'badge-outline-info', 'shortlisted' => 'badge-outline-warning', 'awarded' => 'badge-outline-success', 'rejected' => 'badge-outline-danger']; @endphp
                                <span class="badge {{ $bsc[$bid->status] ?? 'badge-outline-secondary' }} capitalize">{{ $bid->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">No bids with scores yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($sortedBids->isNotEmpty())
        @php $winner = $sortedBids->first(); @endphp
        <div class="panel mt-6 border-2 border-green-500">
            <h5 class="mb-3 text-base font-semibold text-success">Recommended Winner</h5>
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                <div>
                    <label class="text-xs text-white-dark">Vendor</label>
                    <p class="font-bold text-primary">{{ $winner->vendor->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Bid Amount</label>
                    <p class="font-semibold">৳{{ number_format($winner->bid_amount) }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Total Score</label>
                    <p class="font-bold text-success text-lg">{{ $winner->total_score }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Status</label>
                    <p><span class="badge badge-outline-success capitalize">{{ $winner->status }}</span></p>
                </div>
            </div>
        </div>
    @endif

    @if($tender->bids->where('status', 'awarded')->count())
        <div class="mt-6">
            <a href="{{ route('admin.finance.tenders.award-letter', $tender) }}" class="btn btn-outline-success">Generate Award Letter</a>
        </div>
    @endif

    <style>
        @media print {
            .btn, nav, .sidebar, .header, .footer { display: none !important; }
            .panel { break-inside: avoid; }
        }
    </style>
@endsection
