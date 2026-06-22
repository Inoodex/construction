@extends('admin.layouts.master')

@section('title', $subcontractAgreement->agreement_number)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">{{ $subcontractAgreement->agreement_number }}</h2>
        <div class="flex items-center gap-2">
            @if($subcontractAgreement->status === 'draft')
                <a href="{{ route('admin.procurement.subcontract-agreements.edit', $subcontractAgreement) }}" class="btn btn-primary gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Edit
                </a>
                <form action="{{ route('admin.procurement.subcontract-agreements.activate', $subcontractAgreement) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success">Activate</button>
                </form>
                <form action="{{ route('admin.procurement.subcontract-agreements.terminate', $subcontractAgreement) }}" method="POST" class="inline" onsubmit="return confirm('Terminate this agreement?')">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-outline-danger">Terminate</button>
                </form>
            @endif
            @if($subcontractAgreement->status === 'active')
                <form action="{{ route('admin.procurement.subcontract-agreements.complete', $subcontractAgreement) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success">Mark Completed</button>
                </form>
                <form action="{{ route('admin.procurement.subcontract-agreements.terminate', $subcontractAgreement) }}" method="POST" class="inline" onsubmit="return confirm('Terminate this agreement?')">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-outline-danger">Terminate</button>
                </form>
            @endif
            <a href="{{ route('admin.procurement.subcontract-agreements.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <div class="panel lg:col-span-2">
            <h3 class="text-lg font-semibold mb-4">{{ $subcontractAgreement->title }}</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-white-dark">Agreement #:</span> {{ $subcontractAgreement->agreement_number }}</div>
                <div>
                    <span class="text-white-dark">Status:</span>
                    @php
                        $sc = match($subcontractAgreement->status) {
                            'draft' => 'badge-outline-secondary',
                            'active' => 'badge-outline-success',
                            'completed' => 'badge-outline-info',
                            'terminated' => 'badge-outline-danger',
                            'cancelled' => 'badge-outline-warning',
                            default => 'badge-outline-secondary',
                        };
                    @endphp
                    <span class="badge {{ $sc }} capitalize">{{ $subcontractAgreement->status }}</span>
                </div>
                <div><span class="text-white-dark">Subcontractor:</span> {{ $subcontractAgreement->subcontractor->name }}</div>
                <div><span class="text-white-dark">Project:</span> {{ $subcontractAgreement->project?->name ?? '-' }}</div>
                <div><span class="text-white-dark">Agreement Date:</span> {{ $subcontractAgreement->agreement_date->format('d/m/Y') }}</div>
                <div><span class="text-white-dark">Period:</span> {{ $subcontractAgreement->start_date->format('d/m/Y') }} - {{ $subcontractAgreement->end_date?->format('d/m/Y') ?? 'Ongoing' }}</div>
            </div>

            <div class="grid grid-cols-3 gap-4 mt-6">
                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded text-center">
                    <div class="text-2xl font-bold text-primary">{{ number_format($subcontractAgreement->contract_value, 2) }}</div>
                    <div class="text-xs text-white-dark mt-1">Contract Value</div>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded text-center">
                    <div class="text-2xl font-bold text-warning">{{ number_format($subcontractAgreement->retentionAmount(), 2) }}</div>
                    <div class="text-xs text-white-dark mt-1">Retention ({{ $subcontractAgreement->retention_percentage }}%)</div>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded text-center">
                    <div class="text-2xl font-bold text-success">{{ number_format($subcontractAgreement->contract_value - $subcontractAgreement->retentionAmount(), 2) }}</div>
                    <div class="text-xs text-white-dark mt-1">Net Payable</div>
                </div>
            </div>

            @if($subcontractAgreement->scope_of_work)
                <div class="mt-6">
                    <h4 class="font-semibold text-sm text-white-dark">Scope of Work</h4>
                    <p class="mt-1 whitespace-pre-wrap">{{ $subcontractAgreement->scope_of_work }}</p>
                </div>
            @endif

            @if($subcontractAgreement->payment_terms)
                <div class="mt-4">
                    <h4 class="font-semibold text-sm text-white-dark">Payment Terms</h4>
                    <p class="mt-1">{{ $subcontractAgreement->payment_terms }}</p>
                </div>
            @endif
        </div>

        <div class="panel">
            <h3 class="text-lg font-semibold mb-4">Additional Details</h3>

            @if($subcontractAgreement->special_conditions)
                <div class="mb-4">
                    <h4 class="font-semibold text-sm text-white-dark">Special Conditions</h4>
                    <p class="mt-1 text-sm">{{ $subcontractAgreement->special_conditions }}</p>
                </div>
            @endif

            @if($subcontractAgreement->insurance_requirements)
                <div class="mb-4">
                    <h4 class="font-semibold text-sm text-white-dark">Insurance Requirements</h4>
                    <p class="mt-1 text-sm">{{ $subcontractAgreement->insurance_requirements }}</p>
                </div>
            @endif

            <div class="text-xs text-white-dark mt-4">
                Created by {{ $subcontractAgreement->creator?->name ?? 'N/A' }}<br />
                {{ $subcontractAgreement->created_at->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>
@endsection
