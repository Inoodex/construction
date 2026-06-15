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
        <div class="flex items-center justify-between">
            <h5 class="text-base font-semibold">Bids Received</h5>
            <button type="button" onclick="document.getElementById('addBidForm').classList.toggle('hidden')" class="btn btn-sm btn-outline-primary">+ Add Bid</button>
        </div>

        <div id="addBidForm" class="mb-5 mt-3 hidden rounded-lg border p-4 dark:border-gray-700">
            <form action="{{ route('admin.finance.tenders.bids.store', $tender->id) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-3 md:grid-cols-5">
                    <div>
                        <select name="vendor_id" class="form-select" required>
                            <option value="">Vendor</option>
                            @foreach($vendors as $v)
                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <input type="number" step="0.01" name="bid_amount" placeholder="Bid Amount" class="form-input" required />
                    </div>
                    <div>
                        <input type="number" name="technical_score" placeholder="Tech Score (0-100)" class="form-input" />
                    </div>
                    <div>
                        <input type="number" name="financial_score" placeholder="Fin Score (0-100)" class="form-input" />
                    </div>
                    <div>
                        <input type="date" name="submitted_at" class="form-input" required value="{{ date('Y-m-d') }}" />
                    </div>
                </div>
                <div class="mt-2">
                    <textarea name="notes" class="form-input" rows="1" placeholder="Notes (optional)"></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Add Bid</button>
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
                                <div class="flex justify-center gap-1">
                                    <button type="button" onclick="document.getElementById('editBidForm_{{ $bid->id }}').classList.toggle('hidden')" class="btn btn-sm btn-outline-info">Edit</button>
                                    <form action="{{ route('admin.finance.tenders.bids.destroy', [$tender->id, $bid->id]) }}" method="POST" onsubmit="return confirm('Remove this bid?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                    </form>
                                </div>
                                <div id="editBidForm_{{ $bid->id }}" class="mt-2 hidden rounded-lg border p-3 text-left dark:border-gray-700">
                                    <form action="{{ route('admin.finance.tenders.bids.update', [$tender->id, $bid->id]) }}" method="POST">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="vendor_id" value="{{ $bid->vendor_id }}" />
                                        <input type="hidden" name="submitted_at" value="{{ $bid->submitted_at->format('Y-m-d') }}" />
                                        <input type="hidden" name="notes" value="{{ $bid->notes }}" />
                                        <div class="grid grid-cols-2 gap-2 text-xs">
                                            <div>
                                                <input type="number" step="0.01" name="bid_amount" value="{{ $bid->bid_amount }}" placeholder="Amount" class="form-input text-xs" required />
                                            </div>
                                            <div>
                                                <input type="number" name="technical_score" value="{{ $bid->technical_score }}" placeholder="Tech" class="form-input text-xs" />
                                            </div>
                                            <div>
                                                <input type="number" name="financial_score" value="{{ $bid->financial_score }}" placeholder="Fin" class="form-input text-xs" />
                                            </div>
                                            <div>
                                                <select name="status" class="form-select text-xs">
                                                    <option value="submitted" {{ $bid->status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                                    <option value="evaluated" {{ $bid->status == 'evaluated' ? 'selected' : '' }}>Evaluated</option>
                                                    <option value="shortlisted" {{ $bid->status == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                                    <option value="awarded" {{ $bid->status == 'awarded' ? 'selected' : '' }}>Awarded</option>
                                                    <option value="rejected" {{ $bid->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm mt-2 w-full text-xs">Update</button>
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
