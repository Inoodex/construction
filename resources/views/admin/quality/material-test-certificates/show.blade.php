@extends('admin.layouts.master')

@section('title', 'Material Test Certificate - ' . $materialTestCertificate->certificate_number)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Certificate: {{ $materialTestCertificate->certificate_number }}</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.quality.material-test-certificates.edit', $materialTestCertificate) }}" class="btn btn-primary gap-2">Edit</a>
            <a href="{{ route('admin.quality.material-test-certificates.index') }}" class="btn btn-secondary gap-2">&larr; Back to List</a>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-2 gap-4">
        <div class="panel">
            <h4 class="font-semibold mb-3">Details</h4>
            <table class="w-full text-sm">
                <tr><td class="py-1 text-gray-500 w-36">Certificate#</td><td class="font-mono font-semibold text-primary">{{ $materialTestCertificate->certificate_number }}</td></tr>
                <tr><td class="py-1 text-gray-500">Material</td><td>{{ $materialTestCertificate->material_name }}</td></tr>
                <tr><td class="py-1 text-gray-500">Type</td><td><span class="badge badge-outline-secondary">{{ ucfirst($materialTestCertificate->material_type) }}</span></td></tr>
                <tr><td class="py-1 text-gray-500">Supplier</td><td>{{ $materialTestCertificate->supplier ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Batch</td><td>{{ $materialTestCertificate->batch_number ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Project</td><td>{{ $materialTestCertificate->project->name ?? '—' }}</td></tr>
            </table>
        </div>

        <div class="panel">
            <h4 class="font-semibold mb-3">Test Results</h4>
            <table class="w-full text-sm">
                <tr><td class="py-1 text-gray-500 w-36">Test Date</td><td>{{ $materialTestCertificate->test_date->format('d M Y') }}</td></tr>
                <tr><td class="py-1 text-gray-500">Result</td><td>
                    @php $resCls = match($materialTestCertificate->test_result) { 'pass' => 'badge-outline-success', 'fail' => 'badge-outline-danger', default => 'badge-outline-warning' }; @endphp
                    <span class="badge {{ $resCls }}">{{ ucfirst($materialTestCertificate->test_result) }}</span>
                </td></tr>
                <tr><td class="py-1 text-gray-500">Compliance</td><td>
                    @php $compCls = match($materialTestCertificate->compliance_status) { 'compliant' => 'badge-outline-success', 'non_compliant' => 'badge-outline-danger', default => 'badge-outline-warning' }; @endphp
                    <span class="badge {{ $compCls }}">{{ str_replace('_', ' ', ucfirst($materialTestCertificate->compliance_status)) }}</span>
                </td></tr>
                <tr><td class="py-1 text-gray-500">Valid Until</td><td>{{ $materialTestCertificate->valid_until?->format('d M Y') ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Created By</td><td>{{ $materialTestCertificate->creator?->name ?? '—' }}</td></tr>
            </table>
        </div>
    </div>

    @if($materialTestCertificate->test_parameters)
    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Test Parameters</h4>
        <p class="text-sm whitespace-pre-wrap">{{ $materialTestCertificate->test_parameters }}</p>
    </div>
    @endif

    @if($materialTestCertificate->notes)
    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Notes</h4>
        <p class="text-sm whitespace-pre-wrap">{{ $materialTestCertificate->notes }}</p>
    </div>
    @endif

    @if($materialTestCertificate->certificate_file)
    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Attached File</h4>
        <a href="{{ asset('storage/' . $materialTestCertificate->certificate_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
            📎 View Certificate File
        </a>
    </div>
    @endif
@endsection
