@extends('admin.layouts.master')

@section('title', 'Tender Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Tender: {{ $tender->tender_number }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.finance.tenders.edit', $tender->id) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit
            </a>
            <a href="{{ route('admin.finance.tenders.evaluation-matrix', $tender) }}" class="btn btn-danger">Evaluation Matrix</a>
            @if($tender->bids->where('status', 'awarded')->count())
                <a href="{{ route('admin.finance.tenders.award-letter', $tender) }}" class="btn btn-outline-success">Award Letter</a>
            @endif
            <a href="{{ route('admin.finance.tenders.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to List
            </a>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-4">
        <div class="panel">
            <label class="text-xs text-white-dark">Tender Number</label>
            <p class="font-mono font-semibold text-primary">{{ $tender->tender_number }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Project</label>
            <p class="font-semibold">{{ $tender->project->name ?? 'N/A' }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Status</label>
            <p>@php $sc = ['draft' => 'badge-outline-secondary', 'open' => 'badge-outline-success', 'closed' => 'badge-outline-info', 'awarded' => 'badge-outline-primary', 'cancelled' => 'badge-outline-danger']; @endphp
                <span class="badge {{ $sc[$tender->status] ?? 'badge-outline-secondary' }} capitalize">{{ $tender->status }}</span></p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Bids Received</label>
            <p class="text-lg font-bold text-primary">{{ $tender->bids->count() }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Issue Date</label>
            <p class="font-semibold">{{ $tender->issue_date->format('d M Y') }}</p>
        </div>
        <div class="panel">
            <label class="text-xs text-white-dark">Close Date</label>
            <p class="font-semibold">{{ $tender->close_date->format('d M Y') }}</p>
        </div>
    </div>

    @if($tender->description)
        <div class="panel mt-6">
            <h5 class="mb-4 text-base font-semibold">Description</h5>
            <p>{{ $tender->description }}</p>
        </div>
    @endif

    <div class="panel mt-6">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-base font-semibold uppercase">Tender Package Documents</h5>
            <span class="badge badge-outline-primary text-xs">{{ $tender->packages->count() }} Attached Documents</span>
        </div>

        <div class="overflow-x-auto mb-5">
            <table class="table-hover w-full table-auto text-xs">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800 border-b dark:border-gray-700">
                        <th class="py-2.5 px-4 text-left">Document Name</th>
                        <th class="py-2.5 px-4 text-left">Type</th>
                        <th class="py-2.5 px-4 text-left w-1/3">Description</th>
                        <th class="py-2.5 px-4 text-center">File</th>
                        <th class="py-2.5 px-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tender->packages as $package)
                        @php
                            $typeBadge = match(strtolower($package->document_type)) {
                                'boq' => 'badge-outline-primary',
                                'specification' => 'badge-outline-info',
                                'terms' => 'badge-outline-warning',
                                'drawing' => 'badge-outline-success',
                                default => 'badge-outline-secondary'
                            };
                        @endphp
                        <tr class="border-t dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="py-3 px-4 font-semibold text-sm">{{ $package->document_name }}</td>
                            <td class="py-3 px-4">
                                <span class="badge {{ $typeBadge }} uppercase text-[11px]">{{ $package->document_type }}</span>
                            </td>
                            <td class="py-3 px-4 text-white-dark">{{ $package->description ?? '—' }}</td>
                            <td class="py-3 px-4 text-center">
                                @if($package->file_path)
                                    <a href="{{ asset('storage/' . $package->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary gap-1 py-1 px-2.5 text-xs inline-flex items-center">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                        Download
                                    </a>
                                @else
                                    <span class="text-gray-400 font-mono">—</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <form action="{{ route('admin.finance.tenders.packages.destroy', [$tender, $package]) }}" method="POST" onsubmit="return confirm('Remove this document?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 py-1 font-bold" title="Remove Document">X</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-gray-400">No tender package documents uploaded yet. Use the form below to upload.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <form action="{{ route('admin.finance.tenders.packages.store', $tender) }}" method="POST" enctype="multipart/form-data" class="flex flex-nowrap items-center gap-2 overflow-x-auto pb-1 bg-gray-50 dark:bg-gray-800/40 p-3 rounded-lg border border-gray-200 dark:border-gray-700">
            @csrf
            <input type="text" name="document_name" required class="form-input min-w-[180px]" placeholder="Document Name *" />
            <select name="document_type" required class="form-select w-40">
                <option value="boq">BOQ</option>
                <option value="specification">Specification</option>
                <option value="terms">Terms & Conditions</option>
                <option value="drawing">Drawing</option>
                <option value="other">Other</option>
            </select>
            <input type="text" name="description" class="form-input flex-1 min-w-[300px]" placeholder="Description (optional)" />
            <input type="file" name="file" class="form-input w-52" />
            <button type="submit" class="btn btn-primary whitespace-nowrap">+ Upload Document</button>
        </form>
    </div>

    <div class="panel mt-6">
        <div class="flex items-center justify-between">
            <h5 class="text-base font-semibold">Bids Received</h5>
            <button type="button" onclick="document.getElementById('addBidForm').classList.toggle('hidden')" class="btn btn-sm btn-outline-primary">+ Add Bid</button>
        </div>

        <div id="addBidForm" class="mb-5 mt-3 hidden rounded-lg border p-4 dark:border-gray-700">
            <form action="{{ route('admin.finance.tenders.bids.store', $tender->id) }}" method="POST" class="flex flex-nowrap items-center gap-2 overflow-x-auto pb-1">
                @csrf
                <select name="vendor_id" class="form-select min-w-[180px]" required>
                    <option value="">Select Vendor *</option>
                    @foreach($vendors as $v)
                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                    @endforeach
                </select>
                <input type="number" step="0.01" name="bid_amount" placeholder="Bid Amount (৳) *" class="form-input w-36" required />
                <input type="number" name="technical_score" placeholder="Tech Score" class="form-input w-28" min="0" max="100" />
                <input type="number" name="financial_score" placeholder="Fin Score" class="form-input w-28" min="0" max="100" />
                <input type="date" name="submitted_at" class="form-input w-36" required value="{{ date('Y-m-d') }}" />
                <input type="text" name="notes" class="form-input flex-1 min-w-[280px]" placeholder="Notes (optional)" />
                <button type="submit" class="btn btn-primary whitespace-nowrap">+ Add Bid</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Vendor</th>
                        <th>Bid Amount</th>
                        <th>Technical</th>
                        <th>Financial</th>
                        <th>Total</th>
                        <th>Submitted</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tender->bids->sortByDesc('total_score') as $bid)
                        <tr>
                            <td class="font-semibold">{{ $bid->vendor->name ?? 'N/A' }}</td>
                            <td class="font-semibold">৳{{ number_format($bid->bid_amount) }}</td>
                            <td class="text-xs">{{ $bid->technical_score ?? '-' }}</td>
                            <td class="text-xs">{{ $bid->financial_score ?? '-' }}</td>
                            <td class="font-semibold {{ $bid->total_score && $bid->total_score >= 140 ? 'text-success' : '' }}">{{ $bid->total_score ?? '-' }}</td>
                            <td class="text-xs">{{ $bid->submitted_at->format('d M Y') }}</td>
                            <td>
                                @php $bsc = ['submitted' => 'badge-outline-secondary', 'evaluated' => 'badge-outline-info', 'shortlisted' => 'badge-outline-warning', 'awarded' => 'badge-outline-success', 'rejected' => 'badge-outline-danger']; @endphp
                                <span class="badge {{ $bsc[$bid->status] ?? 'badge-outline-secondary' }} capitalize">{{ $bid->status }}</span>
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" onclick="document.getElementById('editBidForm_{{ $bid->id }}').classList.toggle('hidden')" class="btn btn-sm btn-outline-info">Edit</button>
                                    <form action="{{ route('admin.finance.tenders.bids.destroy', [$tender->id, $bid->id]) }}" method="POST" onsubmit="return confirm('Remove this bid?');" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 py-1 font-bold" title="Remove bid">X</button>
                                    </form>
                                </div>
                                <div id="editBidForm_{{ $bid->id }}" class="mt-2 hidden rounded-lg border p-3 text-left dark:border-gray-700">
                                    <form action="{{ route('admin.finance.tenders.bids.update', [$tender->id, $bid->id]) }}" method="POST" class="flex flex-nowrap items-center gap-2 overflow-x-auto text-xs">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="vendor_id" value="{{ $bid->vendor_id }}" />
                                        <input type="hidden" name="submitted_at" value="{{ $bid->submitted_at->format('Y-m-d') }}" />
                                        <input type="hidden" name="notes" value="{{ $bid->notes }}" />
                                        <input type="number" step="0.01" name="bid_amount" value="{{ $bid->bid_amount }}" placeholder="Amount" class="form-input text-xs w-28" required />
                                        <input type="number" name="technical_score" value="{{ $bid->technical_score }}" placeholder="Tech" class="form-input text-xs w-20" />
                                        <input type="number" name="financial_score" value="{{ $bid->financial_score }}" placeholder="Fin" class="form-input text-xs w-20" />
                                        <select name="status" class="form-select text-xs w-28">
                                            <option value="submitted" {{ $bid->status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                            <option value="evaluated" {{ $bid->status == 'evaluated' ? 'selected' : '' }}>Evaluated</option>
                                            <option value="shortlisted" {{ $bid->status == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                            <option value="awarded" {{ $bid->status == 'awarded' ? 'selected' : '' }}>Awarded</option>
                                            <option value="rejected" {{ $bid->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-sm text-xs whitespace-nowrap">Update</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center">No bids yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
