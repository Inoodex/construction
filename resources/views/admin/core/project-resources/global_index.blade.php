@extends('admin.layouts.master')

@section('title', 'All Resources')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">All Project Resources</h2>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.core.resources.index') }}" method="GET" class="flex items-center gap-3 w-full">
                <select name="project_id" class="form-select flex-1">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
                <select name="resource_type" class="form-select flex-1">
                    <option value="">All Types</option>
                    @foreach(\App\Models\Category::resourceTypes()->get() as $cat)
                        <option value="{{ $cat->value }}" {{ request('resource_type') == $cat->value ? 'selected' : '' }}>{{ $cat->label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['project_id', 'resource_type']))
                    <a href="{{ route('admin.core.resources.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Project</th>
                            <th>Type</th>
                            <th>Qty</th>
                            <th>Unit Cost</th>
                            <th>Total Cost</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($resources as $resource)
                            <tr>
                                <td>
                                    <div class="font-semibold">{{ $resource->name }}</div>
                                    <div class="text-xs text-white-dark">{{ Str::limit($resource->description, 40) }}</div>
                                </td>
                                <td class="text-xs">{{ $resource->project->name ?? '—' }}</td>
                                <td>
                                    @php $colors = ['labor' => 'badge-outline-info', 'equipment' => 'badge-outline-warning', 'material' => 'badge-outline-primary']; @endphp
                                    <span class="badge {{ $colors[$resource->resource_type] ?? 'badge-outline-secondary' }} capitalize">{{ $resource->resource_type }}</span>
                                </td>
                                <td class="text-xs">{{ number_format($resource->quantity, 2) }} {{ $resource->unit }}</td>
                                <td class="text-xs">৳{{ number_format($resource->unit_cost, 2) }}</td>
                                <td class="text-xs font-semibold">৳{{ number_format($resource->total_cost, 2) }}</td>
                                <td class="text-center">
                                    <x-action-dropdown>
                                        <x-slot name="items">
                                            <li><a href="{{ route('admin.core.projects.resources.edit', [$resource->project, $resource]) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/></svg> Edit</a></li>
                                            <li class="divider"></li>
                                            <li class="danger">
                                                <form action="{{ route('admin.core.projects.resources.destroy', [$resource->project, $resource]) }}" method="POST" onsubmit="return confirm('Delete this resource?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 001-1h4a1 1 0 001 1m-6 0h6"/></svg> Delete</button>
                                                </form>
                                            </li>
                                        </x-slot>
                                    </x-action-dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">No resources found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $resources->links() }}</div>
        </div>
    </div>
@endsection
