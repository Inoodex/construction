@extends('admin.layouts.master')

@section('title', 'Contract Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Contract: {{ $contract->contract_number }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.core.contracts.edit', $contract) }}" class="btn btn-outline-secondary">Edit</a>
            <a href="{{ route('admin.core.contracts.index') }}" class="btn btn-outline-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List</a>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="panel md:col-span-2">
            <h3 class="text-lg font-semibold mb-4">Contract Information</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">Contract Number:</span>
                    <p class="font-semibold font-mono">{{ $contract->contract_number }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Status:</span>
                    <p>
                        @php
                            $stCls = match($contract->status) {
                                'active' => 'badge-outline-success',
                                'completed' => 'badge-outline-info',
                                'suspended' => 'badge-outline-warning',
                                'terminated' => 'badge-outline-danger',
                                default => 'badge-outline-secondary',
                            };
                        @endphp
                        <span class="badge {{ $stCls }} capitalize">{{ $contract->status }}</span>
                    </p>
                </div>
                <div class="col-span-2">
                    <span class="text-gray-500">Title:</span>
                    <p class="font-semibold">{{ $contract->title }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Client:</span>
                    <p>{{ $contract->client_name }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Type:</span>
                    <p class="capitalize">{{ str_replace('_', ' ', $contract->contract_type) }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Project:</span>
                    <p>{{ $contract->project->name ?? '—' }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Currency:</span>
                    <p>{{ $contract->currency }}</p>
                </div>
            </div>
        </div>

        <div class="panel">
            <h3 class="text-lg font-semibold mb-4">Contract Value</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="text-gray-500">Original Value:</span>
                    <p class="text-xl font-bold text-primary">{{ number_format($contract->contract_value, 2) }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Amendments Total:</span>
                    <p class="font-semibold text-success">+ {{ number_format($contract->totalAmendmentsValue(), 2) }}</p>
                </div>
                <div class="border-t pt-3">
                    <span class="text-gray-500">Revised Value:</span>
                    <p class="text-xl font-bold text-success">{{ number_format($contract->revisedContractValue(), 2) }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Retention Rate:</span>
                    <p>{{ $contract->retention_percentage }}%</p>
                </div>
                @if($contract->liquidated_damages_rate)
                    <div>
                        <span class="text-gray-500">LD Rate (per day):</span>
                        <p>{{ number_format($contract->liquidated_damages_rate, 2) }}</p>
                    </div>
                @endif
                @if($contract->advance_payment_percentage)
                    <div>
                        <span class="text-gray-500">Advance Payment:</span>
                        <p>{{ $contract->advance_payment_percentage }}%</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="panel mt-6">
        <h3 class="text-lg font-semibold mb-4">Dates</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <span class="text-gray-500">Signing Date:</span>
                <p class="font-semibold">{{ $contract->signing_date?->format('d M Y') ?? '—' }}</p>
            </div>
            <div>
                <span class="text-gray-500">Commencement:</span>
                <p class="font-semibold">{{ $contract->commencement_date?->format('d M Y') ?? '—' }}</p>
            </div>
            <div>
                <span class="text-gray-500">Completion:</span>
                <p class="font-semibold">{{ $contract->completion_date?->format('d M Y') ?? '—' }}</p>
            </div>
            <div>
                <span class="text-gray-500">Extended Completion:</span>
                <p class="font-semibold">{{ $contract->extended_completion_date?->format('d M Y') ?? '—' }}</p>
            </div>
        </div>
    </div>

    @if($contract->notes)
        <div class="panel mt-6">
            <h3 class="text-lg font-semibold mb-4">Notes</h3>
            <p class="text-sm whitespace-pre-wrap">{{ $contract->notes }}</p>
        </div>
    @endif

    {{-- Amendments --}}
    <div class="panel mt-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Amendments ({{ $contract->amendments->count() }})</h3>
            <a href="{{ route('admin.core.contract-amendments.create', ['contract_id' => $contract->id]) }}" class="btn btn-sm btn-primary">+ New Amendment</a>
        </div>
        @if($contract->amendments->count())
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto text-sm">
                    <thead>
                        <tr>
                            <th>Amendment #</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Cost Impact</th>
                            <th>Time Impact</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contract->amendments as $amd)
                            <tr>
                                <td class="font-mono text-xs font-semibold text-primary">{{ $amd->amendment_number }}</td>
                                <td>{{ $amd->title }}</td>
                                <td class="text-xs capitalize">{{ str_replace('_', ' ', $amd->type) }}</td>
                                <td class="text-xs font-semibold {{ ($amd->cost_impact ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $amd->cost_impact !== null ? number_format($amd->cost_impact, 2) : '—' }}
                                </td>
                                <td class="text-xs">{{ $amd->time_impact_days ? $amd->time_impact_days . ' days' : '—' }}</td>
                                <td>
                                    @php $sc = match($amd->status) { 'approved' => 'badge-outline-success', 'submitted' => 'badge-outline-info', 'rejected' => 'badge-outline-danger', default => 'badge-outline-secondary' }; @endphp
                                    <span class="badge {{ $sc }} capitalize">{{ $amd->status }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.core.contract-amendments.show', $amd) }}" class="btn btn-sm btn-outline-info">View</a>
                                    <a href="{{ route('admin.core.contract-amendments.edit', $amd) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-gray-500 text-sm py-4">No amendments yet.</p>
        @endif
    </div>

    {{-- Claims --}}
    <div class="panel mt-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Claims ({{ $contract->claims->count() }})</h3>
            <a href="{{ route('admin.core.contract-claims.create', ['contract_id' => $contract->id]) }}" class="btn btn-sm btn-primary">+ New Claim</a>
        </div>
        @if($contract->claims->count())
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto text-sm">
                    <thead>
                        <tr>
                            <th>Claim #</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Claimed</th>
                            <th>Granted</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contract->claims as $claim)
                            <tr>
                                <td class="font-mono text-xs font-semibold text-primary">{{ $claim->claim_number }}</td>
                                <td>{{ $claim->title }}</td>
                                <td class="text-xs capitalize">{{ str_replace('_', ' ', $claim->type) }}</td>
                                <td class="text-xs">
                                    {{ $claim->claimed_amount ? number_format($claim->claimed_amount, 2) : '—' }}
                                    @if($claim->claimed_days) / {{ $claim->claimed_days }}d @endif
                                </td>
                                <td class="text-xs font-semibold text-success">
                                    {{ $claim->granted_amount ? number_format($claim->granted_amount, 2) : '—' }}
                                    @if($claim->granted_days) / {{ $claim->granted_days }}d @endif
                                </td>
                                <td>
                                    @php $sc = match($claim->status) { 'granted' => 'badge-outline-success', 'partially_granted' => 'badge-outline-info', 'submitted' => 'badge-outline-info', 'under_review' => 'badge-outline-warning', 'rejected' => 'badge-outline-danger', default => 'badge-outline-secondary' }; @endphp
                                    <span class="badge {{ $sc }} capitalize">{{ str_replace('_', ' ', $claim->status) }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.core.contract-claims.show', $claim) }}" class="btn btn-sm btn-outline-info">View</a>
                                    <a href="{{ route('admin.core.contract-claims.edit', $claim) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-gray-500 text-sm py-4">No claims yet.</p>
        @endif
    </div>

    {{-- Closeout Checklist --}}
    <div class="panel mt-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Closeout Checklist ({{ $contract->closeoutItems->count() }})</h3>
            <div class="flex items-center gap-2">
                @if($contract->closeoutChecklistComplete())
                    <span class="badge badge-outline-success">All Complete</span>
                @endif
                <button type="button" onclick="document.getElementById('addCloseoutModal').classList.remove('hidden')" class="btn btn-sm btn-primary">+ Add Item</button>
            </div>
        </div>
        @if($contract->closeoutItems->count())
            <div class="space-y-2">
                @foreach($contract->closeoutItems as $item)
                    <form action="{{ route('admin.core.contract-closeout.toggle', $item) }}" method="POST" class="flex items-center gap-3 p-3 rounded {{ $item->is_completed ? 'bg-success/10' : 'bg-gray-50' }}">
                        @csrf
                        @method('PATCH')
                        <input type="checkbox" {{ $item->is_completed ? 'checked' : '' }} onchange="this.form.submit()" class="form-checkbox h-5 w-5" />
                        <div class="flex-1">
                            <p class="text-sm font-semibold {{ $item->is_completed ? 'line-through text-gray-500' : '' }}">{{ $item->item }}</p>
                            @if($item->description)
                                <p class="text-xs text-gray-500">{{ $item->description }}</p>
                            @endif
                        </div>
                        @if($item->is_completed && $item->completed_date)
                            <span class="text-xs text-gray-500">Completed {{ $item->completed_date->format('d M Y') }}</span>
                        @endif
                        <form action="{{ route('admin.core.contract-closeout.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this item?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-danger text-xs">Delete</button>
                        </form>
                    </form>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-500 text-sm py-4">No closeout items yet.</p>
        @endif
    </div>

    {{-- Add Closeout Item Modal --}}
    <div id="addCloseoutModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h4 class="text-lg font-semibold mb-4">Add Closeout Item</h4>
            <form action="{{ route('admin.core.contract-closeout.store', $contract) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Item <span class="text-danger">*</span></label>
                        <input type="text" name="item" class="form-input w-full" required placeholder="e.g. Final invoice submitted" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea name="description" rows="2" class="form-input w-full"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Order</label>
                        <input type="number" name="order_index" value="0" class="form-input w-full" />
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" onclick="document.getElementById('addCloseoutModal').classList.add('hidden')" class="btn btn-outline-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
@endsection
