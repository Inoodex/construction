@extends('admin.layouts.master')

@section('title', 'ITP - ' . $itp->itp_number)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">ITP: {{ $itp->itp_number }}</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.quality.itps.edit', $itp) }}" class="btn btn-primary gap-2">Edit</a>
            <a href="{{ route('admin.quality.itps.index') }}" class="btn btn-secondary gap-2">&larr; Back to List</a>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-3 gap-4">
        <div class="panel">
            <h4 class="font-semibold mb-3">Details</h4>
            <table class="w-full text-sm">
                <tr><td class="py-1 text-gray-500 w-32">ITP Number</td><td class="font-mono font-semibold text-primary">{{ $itp->itp_number }}</td></tr>
                <tr><td class="py-1 text-gray-500">Title</td><td>{{ $itp->title }}</td></tr>
                <tr><td class="py-1 text-gray-500">Project</td><td>{{ $itp->project->name ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Phase</td><td><span class="badge badge-outline-secondary">{{ ucfirst($itp->phase) }}</span></td></tr>
                <tr><td class="py-1 text-gray-500">Status</td><td>
                    @php $stCls = match($itp->status) { 'completed' => 'badge-outline-success', 'active' => 'badge-outline-primary', 'archived' => 'badge-outline-secondary', default => 'badge-outline-warning' }; @endphp
                    <span class="badge {{ $stCls }}">{{ ucfirst($itp->status) }}</span>
                </td></tr>
            </table>
        </div>

        <div class="panel">
            <h4 class="font-semibold mb-3">Progress</h4>
            @php $pct = $itp->completion_percent; @endphp
            <div class="flex items-center gap-3">
                <div class="progress-bar-outer w-full"><div class="progress-bar-inner {{ $pct >= 100 ? 'bg-success' : ($pct >= 50 ? 'bg-warning' : 'bg-primary') }}" style="width: {{ $pct }}%"></div></div>
                <span class="text-lg font-bold">{{ $pct }}%</span>
            </div>
            <p class="mt-2 text-sm text-gray-500">{{ $itp->items->where('status', 'passed')->count() }}/{{ $itp->items->count() }} items passed</p>
        </div>

        <div class="panel">
            <h4 class="font-semibold mb-3">Description</h4>
            <p class="text-sm text-gray-500">{{ $itp->description ?? 'No description provided.' }}</p>
        </div>
    </div>

    <div class="panel mt-4">
        <div class="mb-4 flex items-center justify-between">
            <h4 class="font-semibold">Inspection Items ({{ $itp->items->count() }})</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Description</th>
                        <th>Spec Ref</th>
                        <th>Type</th>
                        <th>Method</th>
                        <th>Criteria</th>
                        <th>Status</th>
                        <th>Inspector</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($itp->items as $item)
                        <tr>
                            <td class="text-xs">{{ $item->order_index + 1 }}</td>
                            <td class="text-sm">{{ $item->description }}</td>
                            <td class="text-xs font-mono">{{ $item->specification_reference ?? '—' }}</td>
                            <td><span class="badge badge-outline-secondary text-xs">{{ ucfirst($item->inspection_type) }}</span></td>
                            <td class="text-xs">{{ ucfirst($item->method) }}</td>
                            <td class="text-xs text-gray-500">{{ Str::limit($item->acceptance_criteria, 40) ?? '—' }}</td>
                            <td>
                                @php $isCls = match($item->status) { 'passed' => 'badge-outline-success', 'failed' => 'badge-outline-danger', 'in_progress' => 'badge-outline-warning', 'n_a' => 'badge-outline-info', default => 'badge-outline-secondary' }; @endphp
                                <span class="badge {{ $isCls }}">{{ str_replace('_', ' ', ucfirst($item->status)) }}</span>
                            </td>
                            <td class="text-sm">{{ $item->inspector ?? '—' }}</td>
                            <td class="text-sm">{{ $item->inspected_date?->format('d M Y') ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-gray-400 py-4">No items.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
