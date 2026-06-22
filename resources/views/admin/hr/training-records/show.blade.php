@extends('admin.layouts.master')

@section('title', $trainingRecord->training_name)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">{{ $trainingRecord->training_name }}</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.hr.training-records.edit', $trainingRecord) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit
            </a>
            <a href="{{ route('admin.hr.training-records.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to List
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <table class="w-full text-sm">
            <tr><td class="py-2 text-gray-500 w-36">Employee</td><td class="font-semibold">{{ $trainingRecord->employee->full_name }}</td></tr>
            <tr><td class="py-2 text-gray-500">Training Name</td><td>{{ $trainingRecord->training_name }}</td></tr>
            <tr><td class="py-2 text-gray-500">Provider</td><td>{{ $trainingRecord->provider ?? '—' }}</td></tr>
            <tr><td class="py-2 text-gray-500">Start Date</td><td>{{ $trainingRecord->start_date->format('d M Y') }}</td></tr>
            <tr><td class="py-2 text-gray-500">End Date</td><td>{{ $trainingRecord->end_date?->format('d M Y') ?? '—' }}</td></tr>
            <tr><td class="py-2 text-gray-500">Certificate No.</td><td class="font-mono">{{ $trainingRecord->certificate_no ?? '—' }}</td></tr>
            <tr><td class="py-2 text-gray-500">Expiry Date</td><td>{{ $trainingRecord->expiry_date?->format('d M Y') ?? '—' }}</td></tr>
            <tr><td class="py-2 text-gray-500">Cost</td><td>{{ $trainingRecord->cost ? number_format($trainingRecord->cost, 2) : '—' }}</td></tr>
            <tr><td class="py-2 text-gray-500">Status</td><td>
                @php
                    $cls = match($trainingRecord->status) {
                        'completed' => 'badge-outline-success',
                        'in-progress' => 'badge-outline-warning',
                        'expired' => 'badge-outline-danger',
                        default => 'badge-outline-secondary'
                    };
                @endphp
                <span class="badge {{ $cls }}">{{ ucfirst($trainingRecord->status) }}</span>
            </td></tr>
            @if($trainingRecord->notes)
                <tr><td class="py-2 text-gray-500 align-top">Notes</td><td>{{ $trainingRecord->notes }}</td></tr>
            @endif
        </table>
    </div>
@endsection
