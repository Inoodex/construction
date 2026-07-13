@extends('admin.layouts.master')

@section('title', 'Award Letter')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 print:hidden">
        <h2 class="text-xl font-semibold uppercase">Award Letter</h2>
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
        @if(!$awardedBid)
            <div class="text-center py-10">
                <p class="text-lg text-gray-500">No awarded bid found for this tender. Please award a bid first.</p>
                <a href="{{ route('admin.finance.tenders.show', $tender) }}" class="btn btn-primary mt-4">Back to Tender</a>
            </div>
        @else
            <div id="letter-content" class="mx-auto max-w-3xl">
                <div class="mb-8 text-center">
                    <h1 class="text-2xl font-bold">CONSTRUCTION ERP</h1>
                    <p class="text-gray-500">Building Tomorrow's Infrastructure Today</p>
                </div>

                <div class="mb-6">
                    <p class="text-right">{{ now()->format('d M Y') }}</p>
                </div>

                <div class="mb-6">
                    <p><strong>{{ $awardedBid->vendor->name ?? 'N/A' }}</strong></p>
                    <p class="text-gray-500">Vendor / Contractor</p>
                </div>

                <div class="mb-6">
                    <p><strong>Subject: Award of Tender {{ $tender->tender_number }}</strong></p>
                </div>

                <div class="space-y-4">
                    <p>Dear Sir/Madam,</p>

                    <p>We are pleased to inform you that your bid for the following tender has been successful and your proposal has been accepted:</p>

                    <table class="w-full border-collapse">
                        <tr>
                            <td class="border border-gray-300 px-4 py-2 font-semibold">Tender Number</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $tender->tender_number }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2 font-semibold">Tender Title</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $tender->title }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2 font-semibold">Project</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $tender->project->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2 font-semibold">Awarded Bid Amount</td>
                            <td class="border border-gray-300 px-4 py-2 font-bold">৳{{ number_format($awardedBid->bid_amount) }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2 font-semibold">Evaluation Score</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $awardedBid->total_score ?? 'N/A' }}</td>
                        </tr>
                    </table>

                    <p>You are requested to proceed with the execution of the work in accordance with the terms and conditions of the tender documents and your submitted proposal.</p>

                    <p>Please confirm your acceptance of this award by signing and returning a copy of this letter within <strong>14 days</strong> from the date of receipt.</p>

                    <p>We look forward to a successful working relationship.</p>

                    <p>Yours faithfully,</p>

                    <div class="mt-10">
                        <div class="w-48 border-b border-black"></div>
                        <p class="mt-1 font-semibold">{{ $tender->creator->name ?? 'Authorized Signatory' }}</p>
                        <p class="text-sm text-gray-500">{{ $tender->creator->email ?? '' }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        @media print {
            .btn, nav, .sidebar, .header, .footer, .print\\:hidden { display: none !important; }
            body { background: white !important; }
            .panel { box-shadow: none !important; border: none !important; padding: 0 !important; }
            #letter-content { max-width: 100% !important; }
        }
    </style>
@endsection
