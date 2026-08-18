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
            <form action="{{ route('admin.quality.risks.index') }}" method="GET" class="flex flex-nowrap items-center gap-2 overflow-x-auto pb-2 w-full">
                <div class="relative min-w-[200px] flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by risk # or title..." class="form-input ltr:pr-11 rtl:pl-11 w-full" />
                    <button type="submit" class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5" /><path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" /></svg>
                    </button>
                </div>
                <select name="project_id" class="form-select min-w-[140px] flex-1">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select min-w-[120px] flex-1">
                    <option value="">All Status</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="mitigated" {{ request('status') == 'mitigated' ? 'selected' : '' }}>Mitigated</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                <select name="probability" class="form-select min-w-[130px] flex-1">
                    <option value="">All Probability</option>
                    <option value="very_low" {{ request('probability') == 'very_low' ? 'selected' : '' }}>Very Low</option>
                    <option value="low" {{ request('probability') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('probability') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('probability') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="very_high" {{ request('probability') == 'very_high' ? 'selected' : '' }}>Very High</option>
                </select>
                <select name="impact" class="form-select min-w-[120px] flex-1">
                    <option value="">All Impact</option>
                    <option value="very_low" {{ request('impact') == 'very_low' ? 'selected' : '' }}>Very Low</option>
                    <option value="low" {{ request('impact') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('impact') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('impact') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="very_high" {{ request('impact') == 'very_high' ? 'selected' : '' }}>Very High</option>
                </select>
                <select name="category" class="form-select min-w-[130px] flex-1">
                    <option value="">All Categories</option>
                    <option value="technical" {{ request('category') == 'technical' ? 'selected' : '' }}>Technical</option>
                    <option value="safety" {{ request('category') == 'safety' ? 'selected' : '' }}>Safety</option>
                    <option value="financial" {{ request('category') == 'financial' ? 'selected' : '' }}>Financial</option>
                    <option value="environmental" {{ request('category') == 'environmental' ? 'selected' : '' }}>Environmental</option>
                    <option value="schedule" {{ request('category') == 'schedule' ? 'selected' : '' }}>Schedule</option>
                    <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                <button type="submit" class="btn btn-primary whitespace-nowrap">Filter</button>
                @if(request()->anyFilled(['search', 'project_id', 'status', 'probability', 'impact', 'category']))
                    <a href="{{ route('admin.quality.risks.index') }}" class="btn btn-outline-danger whitespace-nowrap">Reset</a>
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
                                    <x-action-dropdown>
                                        <x-slot name="items">
                                            <li><a href="{{ route('admin.quality.risks.show', $risk) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                            <li><a href="{{ route('admin.quality.risks.edit', $risk) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/></svg> Edit</a></li>
                                            <li class="divider"></li>
                                            <li class="danger">
                                                <form action="{{ route('admin.quality.risks.destroy', $risk) }}" method="POST" onsubmit="return confirm('Delete this risk?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 001-1h4a1 1 0 001 1m-6 0h6"/></svg> Delete</button>
                                                </form>
                                            </li>
                                        </x-slot>
                                    </x-action-dropdown>
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
