@extends('admin.layouts.master')

@section('title', 'Inspection Checklist Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">{{ $checklist->title }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.core.inspection-checklists.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to List
            </a>
            <a href="{{ route('admin.core.inspection-checklists.edit', $checklist) }}" class="btn btn-primary gap-2">Edit</a>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="panel lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h5 class="text-base font-semibold">Checklist Information</h5>
                @php $colors = ['pending' => 'badge-outline-warning', 'passed' => 'badge-outline-success', 'failed' => 'badge-outline-danger', 'conditional' => 'badge-outline-info']; @endphp
                <span class="badge {{ $colors[$checklist->status] ?? 'badge-outline-secondary' }} capitalize">{{ $checklist->status }}</span>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div><label class="text-xs text-white-dark">Site</label><p class="font-semibold">{{ $checklist->site->name ?? '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Inspector</label><p class="font-semibold">{{ $checklist->inspector->name ?? '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Inspection Date</label><p class="font-semibold">{{ $checklist->inspection_date->format('d M Y') }}</p></div>
                <div><label class="text-xs text-white-dark">Project</label><p class="font-semibold">{{ $checklist->site->project->name ?? '—' }}</p></div>
            </div>

            @if($checklist->description)
                <hr class="my-4 border-white-light dark:border-gray-700">
                <div><label class="text-xs text-white-dark">Description</label><p class="mt-1">{{ $checklist->description }}</p></div>
            @endif

            @if($checklist->items->isNotEmpty())
                <hr class="my-4 border-white-light dark:border-gray-700">
                <div>
                    <label class="text-xs text-white-dark mb-2 block">Checklist Items</label>
                    <div class="space-y-2">
                        @foreach($checklist->items as $item)
                            <div class="flex items-start gap-3 rounded-lg border p-3 dark:border-gray-700">
                                <div class="mt-0.5">
                                    @if($item->is_checked)
                                        <svg class="h-5 w-5 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    @else
                                        <svg class="h-5 w-5 text-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium {{ $item->is_checked ? '' : 'line-through text-white-dark' }}">{{ $item->item_name }}</p>
                                    @if($item->remarks)
                                        <p class="text-xs text-white-dark">{{ $item->remarks }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($checklist->notes)
                <hr class="my-4 border-white-light dark:border-gray-700">
                <div><label class="text-xs text-white-dark">Notes</label><p class="mt-1 whitespace-pre-wrap">{{ $checklist->notes }}</p></div>
            @endif
        </div>

        <div class="panel">
            <h5 class="mb-4 text-base font-semibold">Summary</h5>
            @php
                $total = $checklist->items->count();
                $passed = $checklist->items->where('is_checked', true)->count();
            @endphp
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs">Total Items</span>
                    <span class="text-sm font-bold">{{ $total }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-success">Passed</span>
                    <span class="text-sm font-bold text-success">{{ $passed }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-danger">Failed</span>
                    <span class="text-sm font-bold text-danger">{{ $total - $passed }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs">Pass Rate</span>
                    <span class="text-sm font-bold">{{ $total > 0 ? round(($passed / $total) * 100) : 0 }}%</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs">Created</span>
                    <span class="text-xs font-semibold">{{ $checklist->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
