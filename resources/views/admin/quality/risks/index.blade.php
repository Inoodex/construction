@extends('admin.layouts.master')

@section('title', 'Risk Register')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Risk Register</h2>
        <a href="{{ route('admin.quality.risks.create') }}" class="btn btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            New Risk
        </a>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.quality.risks.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by risk # or title..." class="form-input ltr:pr-11 rtl:pl-11 w-full" />
                    <button type="submit" class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5" /><path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" /></svg>
                    </button>
                </div>
                <select name="project_id" class="form-select" style="min-width: 150px;">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select" style="min-width: 130px;">
                    <option value="">All Status</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="mitigated" {{ request('status') == 'mitigated' ? 'selected' : '' }}>Mitigated</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                <select name="probability" class="form-select" style="min-width: 130px;">
                    <option value="">All Probability</option>
                    <option value="very_low" {{ request('probability') == 'very_low' ? 'selected' : '' }}>Very Low</option>
                    <option value="low" {{ request('probability') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('probability') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('probability') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="very_high" {{ request('probability') == 'very_high' ? 'selected' : '' }}>Very High</option>
                </select>
                <select name="impact" class="form-select" style="min-width: 130px;">
                    <option value="">All Impact</option>
                    <option value="very_low" {{ request('impact') == 'very_low' ? 'selected' : '' }}>Very Low</option>
                    <option value="low" {{ request('impact') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('impact') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('impact') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="very_high" {{ request('impact') == 'very_high' ? 'selected' : '' }}>Very High</option>
                </select>
                <select name="category" class="form-select" style="min-width: 140px;">
                    <option value="">All Categories</option>
                    <option value="technical" {{ request('category') == 'technical' ? 'selected' : '' }}>Technical</option>
                    <option value="safety" {{ request('category') == 'safety' ? 'selected' : '' }}>Safety</option>
                    <option value="financial" {{ request('category') == 'financial' ? 'selected' : '' }}>Financial</option>
                    <option value="environmental" {{ request('category') == 'environmental' ? 'selected' : '' }}>Environmental</option>
                    <option value="schedule" {{ request('category') == 'schedule' ? 'selected' : '' }}>Schedule</option>
                    <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['search', 'project_id', 'status', 'probability', 'impact', 'category']))
                    <a href="{{ route('admin.quality.risks.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Risk #</th>
                            <th>Title</th>
                            <th>Project</th>
                            <th>Category</th>
                            <th>Probability</th>
                            <th>Impact</th>
                            <th>Score</th>
                            <th>Owner</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($risks as $risk)
                            <tr>
                                <td class="font-mono text-xs font-semibold text-primary">{{ $risk->risk_number }}</td>
                                <td class="font-semibold">{{ $risk->title }}</td>
                                <td class="text-xs">{{ $risk->project->name ?? '—' }}</td>
                                <td class="text-xs capitalize">{{ str_replace('_', ' ', $risk->category) }}</td>
                                <td class="text-xs capitalize">{{ str_replace('_', ' ', $risk->probability) }}</td>
                                <td class="text-xs capitalize">{{ str_replace('_', ' ', $risk->impact) }}</td>
                                <td>
                                    <span class="badge {{ \App\Models\Risk::scoreColor($risk->risk_score) }}">{{ $risk->risk_score }}</span>
                                </td>
                                <td class="text-xs">{{ $risk->owner->name ?? '—' }}</td>
                                <td>
                                    @php
                                        $stCls = match($risk->status) {
                                            'closed' => 'badge-outline-success',
                                            'mitigated' => 'badge-outline-warning',
                                            'in_progress' => 'badge-outline-info',
                                            default => 'badge-outline-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $stCls }} capitalize">{{ str_replace('_', ' ', $risk->status) }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('admin.quality.risks.show', $risk) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.quality.risks.edit', $risk) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        <form action="{{ route('admin.quality.risks.destroy', $risk) }}" method="POST" onsubmit="return confirm('Delete this risk?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="text-center">No risks found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $risks->links() }}</div>
        </div>
    </div>
@endsection
