@extends('admin.layouts.master')

@section('title', $certification->certification_name)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">{{ $certification->certification_name }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.hr.certifications.edit', $certification) }}" class="btn btn-outline-secondary">Edit</a>
            <a href="{{ route('admin.hr.certifications.index') }}" class="btn btn-secondary gap-2">&larr; Back</a>
        </div>
    </div>

    <div class="panel mt-6 max-w-2xl">
        <table class="w-full text-sm">
            <tr><td class="py-2 text-gray-500 w-36">Employee</td><td class="font-semibold">{{ $certification->employee->full_name }}</td></tr>
            <tr><td class="py-2 text-gray-500">Name</td><td>{{ $certification->certification_name }}</td></tr>
            <tr><td class="py-2 text-gray-500">Category</td><td><span class="badge badge-outline-{{ $certification->category === 'license' ? 'warning' : ($certification->category === 'permit' ? 'info' : 'secondary') }}">{{ ucfirst($certification->category) }}</span></td></tr>
            <tr><td class="py-2 text-gray-500">Issuing Authority</td><td>{{ $certification->issuing_authority ?? '—' }}</td></tr>
            <tr><td class="py-2 text-gray-500">Certificate No.</td><td class="font-mono">{{ $certification->certificate_no ?? '—' }}</td></tr>
            <tr><td class="py-2 text-gray-500">Issue Date</td><td>{{ $certification->issue_date->format('d M Y') }}</td></tr>
            <tr><td class="py-2 text-gray-500">Expiry Date</td><td>{{ $certification->expiry_date?->format('d M Y') ?? 'No expiry' }}</td></tr>
            <tr><td class="py-2 text-gray-500">Renewal Reminder</td><td>{{ $certification->renewal_reminder_date?->format('d M Y') ?? '—' }}</td></tr>
            <tr><td class="py-2 text-gray-500">Status</td><td>
                @php
                    $cls = match($certification->status) {
                        'active' => 'badge-outline-success',
                        'expired' => 'badge-outline-danger',
                        'suspended' => 'badge-outline-warning',
                        default => 'badge-outline-secondary'
                    };
                @endphp
                <span class="badge {{ $cls }}">{{ ucfirst($certification->status) }}</span>
            </td></tr>
            @if($certification->notes)
                <tr><td class="py-2 text-gray-500 align-top">Notes</td><td>{{ $certification->notes }}</td></tr>
            @endif
        </table>
    </div>
@endsection
