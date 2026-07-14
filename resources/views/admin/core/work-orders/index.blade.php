@extends('admin.layouts.master')

@section('title', 'Work Orders')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Work Orders</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.core.work-orders.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Generate Work Order
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.core.work-orders.index') }}" method="GET" class="flex items-center gap-3 w-full">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title or WO number..." class="form-input ltr:pr-11 rtl:pl-11 w-full" />
                    <button type="submit" class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5" /><path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" /></svg>
                    </button>
                </div>
                <select name="project_id" class="form-select flex-1">
                    <option value="">Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select flex-1">
                    <option value="">Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="issued" {{ request('status') == 'issued' ? 'selected' : '' }}>Issued</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['search', 'project_id', 'status']))
                    <a href="{{ route('admin.core.work-orders.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>WO #</th>
                            <th>Title</th>
                            <th>Project</th>
                            <th>Assignee</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($workOrders as $wo)
                            <tr>
                                <td class="text-xs font-semibold">{{ $wo->work_order_number }}</td>
                                <td>
                                    <div class="font-semibold">{{ $wo->title }}</div>
                                    <div class="text-xs text-white-dark">{{ Str::limit($wo->instructions, 40) }}</div>
                                </td>
                                <td class="text-xs">{{ $wo->project->name ?? '—' }}</td>
                                <td class="text-xs">{{ $wo->assignee->name ?? '—' }}</td>
                                <td class="text-xs">{{ $wo->issue_date?->format('d M Y') ?: '—' }}</td>
                                <td class="text-xs">{{ $wo->due_date?->format('d M Y') ?: '—' }}</td>
                                <td>
                                    @php $colors = ['draft' => 'badge-outline-secondary', 'issued' => 'badge-outline-info', 'in_progress' => 'badge-outline-warning', 'completed' => 'badge-outline-success', 'cancelled' => 'badge-outline-danger']; @endphp
                                    <span class="badge {{ $colors[$wo->status] ?? 'badge-outline-secondary' }} capitalize">{{ str_replace('_', ' ', $wo->status) }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('admin.core.work-orders.show', $wo) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.core.work-orders.pdf', $wo) }}" class="btn btn-sm btn-outline-success" target="_blank">PDF</a>
                                        <a href="{{ route('admin.core.work-orders.edit', $wo) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.core.work-orders.destroy', $wo) }}" method="POST" onsubmit="return confirm('Delete this work order?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center">No work orders found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $workOrders->links() }}</div>
        </div>
    </div>
@endsection
