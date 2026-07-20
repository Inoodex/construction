@extends('admin.layouts.master')

@section('title', 'Rod Calculations (BBS)')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Rod Calculations</h2>
        <a href="{{ route('admin.finance.rod-calculations.create') }}" class="btn btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            New Rod Calculation
        </a>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.finance.rod-calculations.index') }}" method="GET" class="flex items-center gap-3 w-full">
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
                    <a href="{{ route('admin.finance.rod-calculations.index') }}" class="btn btn-outline-danger">Reset</a>
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
                            <th>Steel Grade</th>
                            <th>Revision</th>
                            <th>Status</th>
                            <th>Members</th>
                            <th>Total (kg)</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rodCalculations as $calc)
                            <tr>
                                <td><span class="font-mono text-xs font-semibold text-primary">{{ $calc->reference_no }}</span></td>
                                <td class="font-semibold text-xs">{{ $calc->title }}</td>
                                <td class="text-xs">{{ $calc->project->name ?? 'N/A' }}</td>
                                <td class="text-xs">{{ $calc->steel_grade ?? '-' }}</td>
                                <td class="text-xs">{{ $calc->revision ?? '-' }}</td>
                                <td>
                                    @php $sc = ['draft' => 'badge-outline-secondary', 'approved' => 'badge-outline-success', 'completed' => 'badge-outline-info']; @endphp
                                    <span class="badge {{ $sc[$calc->status] ?? 'badge-outline-secondary' }} capitalize">{{ $calc->status }}</span>
                                </td>
                                <td class="text-xs text-center">{{ $calc->members_count ?? $calc->members->count() }}</td>
                                <td class="font-semibold text-xs">{{ number_format($calc->total_weight_kg ?? 0, 2) }} kg</td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.finance.rod-calculations.show', $calc->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                        @if($calc->isDraft())
                                            <a href="{{ route('admin.finance.rod-calculations.edit', $calc->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                            <form action="{{ route('admin.finance.rod-calculations.destroy', $calc->id) }}" method="POST" onsubmit="return confirm('Delete this Rod Calculation?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center">No rod calculations found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $rodCalculations->links() }}</div>
        </div>
    </div>
@endsection
