@extends('admin.layouts.master')

@section('title', 'Concrete Ratios')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Concrete Ratios</h2>
        <a href="{{ route('admin.finance.concrete-ratios.create') }}" class="btn btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            New Concrete Ratio
        </a>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.finance.concrete-ratios.index') }}" method="GET" class="flex items-center gap-3 w-full">
                <select name="project_id" class="form-select flex-1">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select flex-1">
                    <option value="">All Statuses</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['project_id', 'status']))
                    <a href="{{ route('admin.finance.concrete-ratios.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Ref #</th>
                            <th>Title</th>
                            <th>Project</th>
                            <th>Grade</th>
                            <th>Status</th>
                            <th>Members</th>
                            <th>Volume (m³)</th>
                            <th>Cement (bags)</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($concreteRatios as $cr)
                            <tr>
                                <td><span class="font-mono text-xs font-semibold text-primary">{{ $cr->reference_no }}</span></td>
                                <td class="font-semibold text-xs">{{ $cr->title }}</td>
                                <td class="text-xs">{{ $cr->project->name ?? 'N/A' }}</td>
                                <td class="text-xs font-semibold">{{ $cr->grade ?? '-' }}</td>
                                <td>
                                    @php $sc = ['draft' => 'badge-outline-secondary', 'approved' => 'badge-outline-success', 'completed' => 'badge-outline-info']; @endphp
                                    <span class="badge {{ $sc[$cr->status] ?? 'badge-outline-secondary' }} capitalize">{{ $cr->status }}</span>
                                </td>
                                <td class="text-xs text-center">{{ $cr->members_count ?? $cr->members->count() }}</td>
                                <td class="text-xs">{{ number_format($cr->total_volume_m3 ?? 0, 4) }}</td>
                                <td class="text-xs">{{ number_format($cr->total_cement_bags ?? 0, 2) }}</td>
                                <td class="text-center">
                                    <x-action-dropdown>
                                        <x-slot name="items">
                                            <li><a href="{{ route('admin.finance.concrete-ratios.show', $cr->id) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                            @if($cr->isDraft())
                                            <li><a href="{{ route('admin.finance.concrete-ratios.edit', $cr->id) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/></svg> Edit</a></li>
                                            <li class="divider"></li>
                                            <li class="danger">
                                                <form action="{{ route('admin.finance.concrete-ratios.destroy', $cr->id) }}" method="POST" onsubmit="return confirm('Delete this Concrete Ratio?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 001-1h4a1 1 0 001 1m-6 0h6"/></svg> Delete</button>
                                                </form>
                                            </li>
                                            @endif
                                        </x-slot>
                                    </x-action-dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center">No concrete ratios found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $concreteRatios->links() }}</div>
        </div>
    </div>
@endsection
