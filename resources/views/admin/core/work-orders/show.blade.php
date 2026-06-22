@extends('admin.layouts.master')

@section('title', 'Work Order Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">{{ $workOrder->work_order_number }} — {{ $workOrder->title }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.core.work-orders.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to List
            </a>
            {{-- <a href="{{ route('admin.core.work-orders.print', $workOrder) }}" target="_blank" class="btn btn-info gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Print
            </a> --}}
             
            <a href="{{ route('admin.core.work-orders.edit', $workOrder) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Edit</a>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="panel lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h5 class="text-base font-semibold">Work Order Details</h5>
                @php $colors = ['draft' => 'badge-outline-secondary', 'issued' => 'badge-outline-info', 'in_progress' => 'badge-outline-warning', 'completed' => 'badge-outline-success', 'cancelled' => 'badge-outline-danger']; @endphp
                <span class="badge {{ $colors[$workOrder->status] ?? 'badge-outline-secondary' }} capitalize">{{ str_replace('_', ' ', $workOrder->status) }}</span>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div><label class="text-xs text-white-dark">Work Order #</label><p class="font-semibold">{{ $workOrder->work_order_number }}</p></div>
                <div><label class="text-xs text-white-dark">Project</label><p class="font-semibold">{{ $workOrder->project->name ?? '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Related Task</label><p class="font-semibold">{{ $workOrder->task->name ?? '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Site</label><p class="font-semibold">{{ $workOrder->site->name ?? '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Assignee</label><p class="font-semibold">{{ $workOrder->assignee->name ?? '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Issued By</label><p class="font-semibold">{{ $workOrder->issuer->name ?? '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Issue Date</label><p class="font-semibold">{{ $workOrder->issue_date?->format('d M Y') ?: '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Due Date</label><p class="font-semibold">{{ $workOrder->due_date?->format('d M Y') ?: '—' }}</p></div>
                @if($workOrder->completed_date)
                    <div><label class="text-xs text-white-dark">Completed Date</label><p class="font-semibold">{{ $workOrder->completed_date->format('d M Y') }}</p></div>
                @endif
            </div>

            @if($workOrder->instructions)
                <hr class="my-4 border-white-light dark:border-gray-700">
                <div><label class="text-xs text-white-dark">Work Instructions</label><p class="mt-1 whitespace-pre-wrap">{{ $workOrder->instructions }}</p></div>
            @endif

            @if($workOrder->notes)
                <hr class="my-4 border-white-light dark:border-gray-700">
                <div><label class="text-xs text-white-dark">Notes</label><p class="mt-1 whitespace-pre-wrap">{{ $workOrder->notes }}</p></div>
            @endif
        </div>

        <div class="panel">
            <h5 class="mb-4 text-base font-semibold">Timeline</h5>
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs">Created</span>
                    <span class="text-xs font-semibold">{{ $workOrder->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs">Issued</span>
                    <span class="text-xs font-semibold">{{ $workOrder->issue_date?->format('d M Y') ?: '—' }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs">Due</span>
                    <span class="text-xs font-semibold">{{ $workOrder->due_date?->format('d M Y') ?: '—' }}</span>
                </div>
                @if($workOrder->due_date)
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                        <span class="text-xs">Remaining Days</span>
                        <span class="text-xs font-semibold">{{ now()->startOfDay()->diffInDays($workOrder->due_date, false) }} days</span>
                    </div>
                @endif
            </div>
            {{-- <div class="mt-4">
                <a href="{{ route('admin.core.work-orders.print', $workOrder) }}" target="_blank" class="btn btn-info w-full">Print Work Order</a>
            </div> --}}
        </div>
    </div>
@endsection
