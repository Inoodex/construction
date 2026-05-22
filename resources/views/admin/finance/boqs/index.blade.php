@extends('admin.layouts.master')

@section('title', 'Bill of Quantities')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Bill of Quantities</h2>
        <a href="{{ route('admin.finance.boqs.create') }}" class="btn btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            New BOQ
        </a>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.finance.boqs.index') }}" method="GET" class="flex items-center gap-3 w-full">
                <select name="project_id" class="form-select flex-1">
                    <option value="">Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select flex-1">
                    <option value="">Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="revised" {{ request('status') == 'revised' ? 'selected' : '' }}>Revised</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['project_id', 'status']))
                    <a href="{{ route('admin.finance.boqs.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>BOQ #</th>
                            <th>Title</th>
                            <th>Project</th>
                            <th>Items</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($boqs as $boq)
                            <tr>
                                <td><span class="font-mono text-xs font-semibold text-primary">{{ $boq->boq_number }}</span></td>
                                <td class="font-semibold">{{ $boq->title }}</td>
                                <td class="text-xs">{{ $boq->project->name ?? 'N/A' }}</td>
                                <td class="text-xs">{{ $boq->items->count() }}</td>
                                <td class="font-semibold">৳{{ number_format($boq->total_amount) }}</td>
                                <td>
                                    @php $sc = ['draft' => 'badge-outline-secondary', 'approved' => 'badge-outline-success', 'revised' => 'badge-outline-warning']; @endphp
                                    <span class="badge {{ $sc[$boq->status] ?? 'badge-outline-secondary' }} capitalize">{{ $boq->status }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.finance.boqs.show', $boq->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.finance.boqs.edit', $boq->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                        <form action="{{ route('admin.finance.boqs.destroy', $boq->id) }}" method="POST" onsubmit="return confirm('Delete this BOQ?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">No BOQs found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $boqs->links() }}</div>
        </div>
    </div>
@endsection
