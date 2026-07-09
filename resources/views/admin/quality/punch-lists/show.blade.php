@extends('admin.layouts.master')

@section('title', 'Punch List - ' . $punchList->punch_list_number)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Punch List: {{ $punchList->punch_list_number }}</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.quality.punch-lists.edit', $punchList) }}" class="btn btn-primary gap-2">Edit</a>
            <a href="{{ route('admin.quality.punch-lists.index') }}" class="btn btn-secondary gap-2">&larr; Back to List</a>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-3 gap-4">
        <div class="panel">
            <h4 class="font-semibold mb-3">Details</h4>
            <table class="w-full text-sm">
                <tr><td class="py-1 text-gray-500 w-32">PL Number</td><td class="font-mono font-semibold text-primary">{{ $punchList->punch_list_number }}</td></tr>
                <tr><td class="py-1 text-gray-500">Title</td><td>{{ $punchList->title }}</td></tr>
                <tr><td class="py-1 text-gray-500">Project</td><td>{{ $punchList->project->name ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Status</td><td>
                    @php $stCls = match($punchList->status) { 'closed' => 'badge-outline-success', 'completed' => 'badge-outline-primary', 'in_progress' => 'badge-outline-warning', default => 'badge-outline-secondary' }; @endphp
                    <span class="badge {{ $stCls }}">{{ str_replace('_', ' ', ucfirst($punchList->status)) }}</span>
                </td></tr>
            </table>
        </div>

        <div class="panel">
            <h4 class="font-semibold mb-3">Dates & Progress</h4>
            <table class="w-full text-sm">
                <tr><td class="py-1 text-gray-500 w-32">Inspection Date</td><td>{{ $punchList->inspection_date->format('d M Y') }}</td></tr>
                <tr><td class="py-1 text-gray-500">Due Date</td><td>{{ $punchList->due_date?->format('d M Y') ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Created By</td><td>{{ $punchList->creator?->name ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Completion</td><td>
                    @php $pct = $punchList->completion_percent; @endphp
                    <div class="flex items-center gap-2">
                        <div class="progress-bar-outer w-24"><div class="progress-bar-inner {{ $pct >= 100 ? 'bg-success' : ($pct >= 50 ? 'bg-warning' : 'bg-primary') }}" style="width: {{ $pct }}%"></div></div>
                        <span class="text-xs font-semibold">{{ $pct }}%</span>
                    </div>
                </td></tr>
            </table>
        </div>

        <div class="panel">
            <h4 class="font-semibold mb-3">Description</h4>
            <p class="text-sm text-gray-500">{{ $punchList->description ?? 'No description provided.' }}</p>
        </div>
    </div>

    {{-- Items Table --}}
    <div class="panel mt-4">
        <div class="mb-4 flex items-center justify-between">
            <h4 class="font-semibold">Items ({{ $punchList->items->count() }})</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>Trade</th>
                        <th>Priority</th>
                        <th>Assigned To</th>
                        <th>Status</th>
                        <th>Completed</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($punchList->items as $item)
                        <tr>
                            <td class="text-xs">{{ $item->order_index + 1 }}</td>
                            <td class="text-sm">{{ $item->description }}</td>
                            <td class="text-sm">{{ $item->location ?? '—' }}</td>
                            <td><span class="badge badge-outline-secondary text-xs">{{ ucfirst($item->trade) }}</span></td>
                            <td>
                                @php $prCls = match($item->priority) { 'critical' => 'badge-outline-danger', 'high' => 'badge-outline-warning', default => 'badge-outline-info' }; @endphp
                                <span class="badge {{ $prCls }}">{{ ucfirst($item->priority) }}</span>
                            </td>
                            <td class="text-sm">{{ $item->assigned_to ?? '—' }}</td>
                            <td>
                                @php $isCls = match($item->status) { 'verified' => 'badge-outline-success', 'completed' => 'badge-outline-primary', 'in_progress' => 'badge-outline-warning', default => 'badge-outline-secondary' }; @endphp
                                <span class="badge {{ $isCls }}">{{ str_replace('_', ' ', ucfirst($item->status)) }}</span>
                            </td>
                            <td class="text-sm">{{ $item->completed_date?->format('d M Y') ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-gray-400 py-4">No items.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
