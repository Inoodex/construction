@extends('admin.layouts.master')

@section('title', 'Project Resources')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Resources — {{ $project->name }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.core.projects.show', $project) }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to Project
            </a>
            <a href="{{ route('admin.core.projects.resource-gantt', $project) }}" class="btn btn-info gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><rect x="3" y="3" width="18" height="18" rx="2"></rect><line x1="9" y1="3" x2="9" y2="21"></line><line x1="7" y1="8" x2="13" y2="8"></line><line x1="7" y1="12" x2="17" y2="12"></line><line x1="7" y1="16" x2="11" y2="16"></line></svg>
                Allocation Chart
            </a>
            <a href="{{ route('admin.core.projects.resources.create', $project) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Add Resource
            </a>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="panel flex items-center gap-3">
            <div class="rounded-full bg-info/10 p-3 text-info">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.5" d="M15 22H9C6.17157 22 4.75736 22 3.87868 21.1213C3 20.2426 3 18.8284 3 16V8C3 5.17157 3 3.75736 3.87868 2.87868C4.75736 2 6.17157 2 9 2H15C17.8284 2 19.2426 2 20.1213 2.87868C21 3.75736 21 5.17157 21 8V16C21 18.8284 21 20.2426 20.1213 21.1213C19.2426 22 17.8284 22 15 22Z" fill="currentColor"/><path d="M8 12C8.55228 12 9 11.5523 9 11C9 10.4477 8.55228 10 8 10C7.44772 10 7 10.4477 7 11C7 11.5523 7.44772 12 8 12Z" fill="currentColor"/><path d="M12 12C12.5523 12 13 11.5523 13 11C13 10.4477 12.5523 10 12 10C11.4477 10 11 10.4477 11 11C11 11.5523 11.4477 12 12 12Z" fill="currentColor"/><path d="M16 12C16.5523 12 17 11.5523 17 11C17 10.4477 16.5523 10 16 10C15.4477 10 15 10.4477 15 11C15 11.5523 15.4477 12 16 12Z" fill="currentColor"/></svg>
            </div>
            <div>
                <p class="text-xs text-white-dark">Labor</p>
                <p class="text-lg font-bold">৳{{ number_format($totals['labor'], 2) }}</p>
            </div>
        </div>
        <div class="panel flex items-center gap-3">
            <div class="rounded-full bg-warning/10 p-3 text-warning">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.5" d="M2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12Z" fill="currentColor"/><path d="M12 6.75C12.4142 6.75 12.75 7.08579 12.75 7.5V11.25H16.5C16.9142 11.25 17.25 11.5858 17.25 12C17.25 12.4142 16.9142 12.75 16.5 12.75H12C11.5858 12.75 11.25 12.4142 11.25 12V7.5C11.25 7.08579 11.5858 6.75 12 6.75Z" fill="currentColor"/></svg>
            </div>
            <div>
                <p class="text-xs text-white-dark">Equipment</p>
                <p class="text-lg font-bold">৳{{ number_format($totals['equipment'], 2) }}</p>
            </div>
        </div>
        <div class="panel flex items-center gap-3">
            <div class="rounded-full bg-primary/10 p-3 text-primary">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.5" d="M3.46447 20.5355C4.92893 22 7.28595 22 12 22C16.714 22 19.0711 22 20.5355 20.5355C22 19.0711 22 16.714 22 12C22 7.28595 22 4.92893 20.5355 3.46447C19.0711 2 16.714 2 12 2C7.28595 2 4.92893 2 3.46447 3.46447C2 4.92893 2 7.28595 2 12C2 16.714 2 19.0711 3.46447 20.5355Z" fill="currentColor"/><path d="M15.59 7.41L9 14L7 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>
            </div>
            <div>
                <p class="text-xs text-white-dark">Material</p>
                <p class="text-lg font-bold">৳{{ number_format($totals['material'], 2) }}</p>
            </div>
        </div>
    </div>

    <div class="mt-4 panel">
        <div class="mb-3 flex items-center justify-between">
            <h5 class="text-base font-semibold">All Resources</h5>
            <span class="text-sm font-bold">Total: ৳{{ number_format($grandTotal, 2) }}</span>
        </div>
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Qty</th>
                            <th>Unit</th>
                            <th>Unit Cost</th>
                            <th>Total Cost</th>
                            <th>Allocated</th>
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
                                <td>
                                    @php $colors = ['labor' => 'badge-outline-info', 'equipment' => 'badge-outline-warning', 'material' => 'badge-outline-primary']; @endphp
                                    <span class="badge {{ $colors[$resource->resource_type] ?? 'badge-outline-secondary' }} capitalize">{{ $resource->resource_type }}</span>
                                </td>
                                <td class="text-xs">{{ number_format($resource->quantity, 2) }}</td>
                                <td class="text-xs">{{ $resource->unit ?? '—' }}</td>
                                <td class="text-xs">৳{{ number_format($resource->unit_cost, 2) }}</td>
                                <td class="text-xs font-semibold">৳{{ number_format($resource->total_cost, 2) }}</td>
                                <td class="text-xs">
                                    @php $allocPct = $resource->quantity > 0 ? ($resource->allocated_quantity / $resource->quantity) * 100 : 0; @endphp
                                    <div class="flex items-center gap-2">
                                        <div class="h-1.5 w-12 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                            <div class="h-full rounded-full {{ $allocPct >= 100 ? 'bg-success' : 'bg-primary' }}" style="width: {{ min($allocPct, 100) }}%"></div>
                                        </div>
                                        <span class="font-semibold">{{ number_format($resource->allocated_quantity, 1) }}/{{ number_format($resource->quantity, 1) }}</span>
                                    </div>
                                    @if($resource->taskAllocations->isNotEmpty())
                                        <div class="mt-1 text-[10px] text-white-dark">
                                            @foreach($resource->taskAllocations->take(2) as $ta)
                                                <div>→ {{ $ta->task->name }}</div>
                                            @endforeach
                                            @if($resource->taskAllocations->count() > 2)
                                                <div class="text-primary">+{{ $resource->taskAllocations->count() - 2 }} more</div>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.core.projects.resources.edit', [$project, $resource]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.core.projects.resources.destroy', [$project, $resource]) }}" method="POST" onsubmit="return confirm('Delete this resource?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center">No resources planned yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
