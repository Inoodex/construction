@extends('admin.layouts.master')

@section('title', $trainingRecord->training_name)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">{{ $trainingRecord->training_name }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.hr.training-records.edit', $trainingRecord) }}" class="btn btn-outline-secondary">Edit</a>
            <a href="{{ route('admin.hr.training-records.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
        </div>
    </div>

    <div class="panel mt-6 max-w-2xl">
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
