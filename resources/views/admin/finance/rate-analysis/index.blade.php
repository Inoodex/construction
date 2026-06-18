@extends('admin.layouts.master')

@section('title', 'Rate Analysis Library')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Rate Analysis Library</h2>
        <a href="{{ route('admin.finance.rate-analysis.create') }}" class="btn btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            New Rate Analysis
        </a>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.finance.rate-analysis.index') }}" method="GET" class="flex items-center gap-3 w-full">
                <select name="project_id" class="form-select flex-1">
                    <option value="">Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
                <select name="resource_type" class="form-select flex-1">
                    <option value="">Resource Type</option>
                    @foreach(\App\Models\Category::resourceTypes()->get() as $cat)
                        <option value="{{ $cat->value }}" {{ request('resource_type') == $cat->value ? 'selected' : '' }}>{{ $cat->label }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select flex-1">
                    <option value="">Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="revised" {{ request('status') == 'revised' ? 'selected' : '' }}>Revised</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['project_id', 'resource_type', 'status']))
                    <a href="{{ route('admin.finance.rate-analysis.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>RA #</th>
                            <th>Title</th>
                            <th>Project</th>
                            <th>Items</th>
                            <th>Total Rate</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rateAnalyses as $ra)
                            <tr>
                                <td><span class="font-mono text-xs font-semibold text-primary">{{ $ra->ra_number }}</span></td>
                                <td class="font-semibold">{{ $ra->title }}</td>
                                <td class="text-xs">{{ $ra->project->name ?? 'N/A' }}</td>
                                <td class="text-xs">{{ $ra->items->count() }}</td>
                                <td class="font-semibold">৳{{ number_format($ra->total_rate) }}</td>
                                <td>
                                    @php $sc = ['draft' => 'badge-outline-secondary', 'approved' => 'badge-outline-success', 'revised' => 'badge-outline-warning']; @endphp
                                    <span class="badge {{ $sc[$ra->status] ?? 'badge-outline-secondary' }} capitalize">{{ $ra->status }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.finance.rate-analysis.show', $ra->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.finance.rate-analysis.edit', $ra->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                        <form action="{{ route('admin.finance.rate-analysis.destroy', $ra->id) }}" method="POST" onsubmit="return confirm('Delete this Rate Analysis?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">No Rate Analyses found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $rateAnalyses->links() }}</div>
        </div>
    </div>
@endsection
