@extends('admin.layouts.master')

@section('title', 'Site Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Site Details</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.core.sites.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to List
            </a>
            <a href="{{ route('admin.core.sites.edit', $site->id) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Edit
            </a>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="panel lg:col-span-2">
            <h5 class="mb-4 text-base font-semibold">General Information</h5>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="text-xs text-white-dark">Site Name</label>
                    <p class="font-semibold">{{ $site->name }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Status</label>
                    <div>
                        <span class="badge {{ $site->status == 'active' ? 'badge-outline-success' : 'badge-outline-secondary' }} capitalize">{{ $site->status }}</span>
                    </div>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Project</label>
                    <p class="font-semibold">{{ $site->project->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Location</label>
                    <p class="font-semibold">{{ $site->location_address ?: '—' }}</p>
                </div>
            </div>
        </div>

        <div class="panel">
            <h5 class="mb-4 text-base font-semibold">Site Summary</h5>
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Total Tasks</span>
                    <span class="text-sm font-bold dark:text-white">{{ $site->tasks->count() }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Total Logs</span>
                    <span class="text-sm font-bold dark:text-white">{{ $site->siteLogs->count() }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Deliveries</span>
                    <span class="text-sm font-bold dark:text-white">{{ $site->goodsReceivedNotes->count() }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Created</span>
                    <span class="text-xs font-semibold dark:text-white">{{ $site->created_at->format('d M Y') }}</span>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.core.sites.logs.index', $site) }}" class="btn btn-sm btn-outline-primary w-full">View Logs</a>
            </div>
        </div>
    </div>

    @if($site->siteLogs->isNotEmpty())
        <div class="mt-6 panel">
            <div class="mb-4 flex items-center justify-between">
                <h5 class="text-base font-semibold">Recent Logs</h5>
                <a href="{{ route('admin.core.sites.logs.index', $site) }}" class="text-xs text-primary">View All</a>
            </div>
            <div class="space-y-3">
                @foreach($site->siteLogs->sortByDesc('log_date')->take(5) as $log)
                    <div class="flex items-center justify-between rounded-lg border p-3 dark:border-gray-700">
                        <div class="flex-1">
                            <a href="{{ route('admin.core.sites.logs.show', [$site, $log]) }}" class="text-sm font-semibold hover:text-primary">{{ $log->title }}</a>
                            <p class="text-xs text-white-dark">{{ $log->log_date->format('d M Y') }} — {{ ucfirst(str_replace('_', ' ', $log->report_type)) }}</p>
                        </div>
                        <span class="badge {{ $log->status == 'submitted' ? 'badge-outline-success' : 'badge-outline-warning' }} text-xs">{{ $log->status }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mt-6 panel">
        <div class="mb-4 flex items-center justify-between">
            <h5 class="text-base font-semibold">Photos</h5>
            <a href="{{ route('admin.core.sites.photos.index', $site) }}" class="text-xs text-primary">Manage Photos</a>
        </div>
        @if($site->photos->isNotEmpty())
            <div class="grid grid-cols-3 gap-3 sm:grid-cols-4 md:grid-cols-6">
                @foreach($site->photos->sortByDesc('created_at')->take(6) as $photo)
                    <a href="{{ asset('storage/' . $photo->file_path) }}" target="_blank" class="group overflow-hidden rounded-lg border dark:border-gray-700">
                        <img src="{{ asset('storage/' . $photo->file_path) }}" alt="{{ $photo->caption ?: $photo->original_name }}" class="h-20 w-full object-cover transition group-hover:scale-105" loading="lazy" />
                    </a>
                @endforeach
            </div>
        @else
            <p class="py-4 text-center text-sm text-white-dark">No photos yet. <a href="{{ route('admin.core.sites.photos.index', $site) }}" class="text-primary hover:underline">Upload photos</a></p>
        @endif
    </div>

    <div class="mt-6 panel">
        <div class="mb-4 flex items-center justify-between">
            <h5 class="text-base font-semibold">Material Deliveries</h5>
            <a href="{{ route('admin.procurement.goods-received-notes.index', ['site_id' => $site->id]) }}" class="text-xs text-primary">View All</a>
        </div>
        @if($site->goodsReceivedNotes->isNotEmpty())
            <div class="datatable">
                <div class="overflow-x-auto">
                    <table class="table-hover w-full table-auto">
                        <thead>
                            <tr>
                                <th>GRN</th>
                                <th>Date</th>
                                <th>Vehicle</th>
                                <th>Materials</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($site->goodsReceivedNotes->sortByDesc('received_date')->take(10) as $grn)
                                <tr>
                                    <td><a href="{{ route('admin.procurement.goods-received-notes.show', $grn) }}" class="font-mono text-xs font-semibold text-primary hover:underline">{{ $grn->grn_number }}</a></td>
                                    <td class="text-xs">{{ $grn->received_date->format('d M Y') }}</td>
                                    <td class="text-xs">{{ $grn->vehicle_number ?: '—' }}</td>
                                    <td class="text-xs">
                                        @foreach($grn->items->take(3) as $item)
                                            <div>{{ $item->material->name ?? 'Unknown' }}: {{ number_format($item->quantity_accepted, 1) }} accepted</div>
                                        @endforeach
                                        @if($grn->items->count() > 3)
                                            <div class="text-primary text-[10px]">+{{ $grn->items->count() - 3 }} more</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $grn->status == 'verified' ? 'badge-outline-success' : 'badge-outline-warning' }} text-xs capitalize">{{ $grn->status }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <p class="py-4 text-center text-sm text-white-dark">No deliveries recorded for this site yet.</p>
        @endif
    </div>
@endsection
