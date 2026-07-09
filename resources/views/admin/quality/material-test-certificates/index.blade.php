@extends('admin.layouts.master')

@section('title', 'Material Test Certificates')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Material Test Certificates</h2>
        <a href="{{ route('admin.quality.material-test-certificates.create') }}" class="btn btn-primary gap-2">+ New Certificate</a>
    </div>

    <div class="panel mt-6">
        <form method="GET" class="mb-4 flex flex-nowrap items-end gap-2 overflow-x-auto">
            <div>
                <label class="text-xs font-semibold">Project</label>
                <select name="project_id" class="form-select" style="min-width: 180px">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold">Material Type</label>
                <select name="material_type" class="form-select" style="min-width: 140px">
                    <option value="">All</option>
                    <option value="concrete" {{ request('material_type') == 'concrete' ? 'selected' : '' }}>Concrete</option>
                    <option value="steel" {{ request('material_type') == 'steel' ? 'selected' : '' }}>Steel</option>
                    <option value="soil" {{ request('material_type') == 'soil' ? 'selected' : '' }}>Soil</option>
                    <option value="aggregate" {{ request('material_type') == 'aggregate' ? 'selected' : '' }}>Aggregate</option>
                    <option value="cement" {{ request('material_type') == 'cement' ? 'selected' : '' }}>Cement</option>
                    <option value="other" {{ request('material_type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold">Compliance</label>
                <select name="compliance_status" class="form-select" style="min-width: 140px">
                    <option value="">All</option>
                    <option value="compliant" {{ request('compliance_status') == 'compliant' ? 'selected' : '' }}>Compliant</option>
                    <option value="non_compliant" {{ request('compliance_status') == 'non_compliant' ? 'selected' : '' }}>Non-Compliant</option>
                    <option value="pending" {{ request('compliance_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['project_id', 'material_type', 'compliance_status']))
                    <a href="{{ route('admin.quality.material-test-certificates.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Certificate#</th>
                        <th>Material</th>
                        <th>Type</th>
                        <th>Project</th>
                        <th>Test Date</th>
                        <th>Result</th>
                        <th>Compliance</th>
                        <th style="text-align: center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $r)
                        <tr>
                            <td class="font-mono text-xs font-semibold text-primary">{{ $r->certificate_number }}</td>
                            <td class="text-sm">{{ Str::limit($r->material_name, 25) }}</td>
                            <td><span class="badge badge-outline-secondary text-xs">{{ ucfirst($r->material_type) }}</span></td>
                            <td class="text-sm">{{ $r->project->name ?? '—' }}</td>
                            <td class="text-sm">{{ $r->test_date->format('d M Y') }}</td>
                            <td>
                                @php $resCls = match($r->test_result) { 'pass' => 'badge-outline-success', 'fail' => 'badge-outline-danger', default => 'badge-outline-warning' }; @endphp
                                <span class="badge {{ $resCls }}">{{ ucfirst($r->test_result) }}</span>
                            </td>
                            <td>
                                @php $compCls = match($r->compliance_status) { 'compliant' => 'badge-outline-success', 'non_compliant' => 'badge-outline-danger', default => 'badge-outline-warning' }; @endphp
                                <span class="badge {{ $compCls }}">{{ str_replace('_', ' ', ucfirst($r->compliance_status)) }}</span>
                            </td>
                            <td class="flex gap-1" style="justify-content: center">
                                <a href="{{ route('admin.quality.material-test-certificates.show', $r) }}" class="btn btn-sm btn-outline-info">View</a>
                                <a href="{{ route('admin.quality.material-test-certificates.edit', $r) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form action="{{ route('admin.quality.material-test-certificates.destroy', $r) }}" method="POST" class="inline" onsubmit="return confirm('Delete this certificate?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-gray-400 py-4">No certificates found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $records->links() }}</div>
    </div>
@endsection
