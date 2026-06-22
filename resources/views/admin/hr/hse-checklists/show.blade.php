@extends('admin.layouts.master')

@section('title', $hseChecklist->title)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">{{ $hseChecklist->title }}</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.hr.hse-checklists.edit', $hseChecklist) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit
            </a>
            <a href="{{ route('admin.hr.hse-checklists.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to List
            </a>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-3 gap-4">
        <div class="panel">
            <h4 class="font-semibold mb-3">Details</h4>
            <table class="w-full text-sm">
                <tr><td class="py-1 text-gray-500 w-32">Title</td><td class="font-semibold">{{ $hseChecklist->title }}</td></tr>
                <tr><td class="py-1 text-gray-500">Type</td><td><span class="badge badge-outline-{{ $hseChecklist->checklist_type === 'fire' ? 'danger' : ($hseChecklist->checklist_type === 'ppe' ? 'success' : 'info') }}">{{ str_replace('-', ' ', ucfirst($hseChecklist->checklist_type)) }}</span></td></tr>
                <tr><td class="py-1 text-gray-500">Location</td><td>{{ $hseChecklist->location ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Inspection Date</td><td>{{ $hseChecklist->inspection_date->format('d M Y') }}</td></tr>
                <tr><td class="py-1 text-gray-500">Status</td><td><span class="badge {{ $hseChecklist->status === 'closed' ? 'badge-outline-success' : 'badge-outline-warning' }}">{{ ucfirst($hseChecklist->status) }}</span></td></tr>
                <tr><td class="py-1 text-gray-500">Closure Date</td><td>{{ $hseChecklist->closure_date?->format('d M Y') ?? '—' }}</td></tr>
            </table>
        </div>

        <div class="panel">
            <h4 class="font-semibold mb-3">Inspector</h4>
            <table class="w-full text-sm">
                <tr><td class="py-1 text-gray-500 w-32">Name</td><td>{{ $hseChecklist->employee?->full_name ?? '—' }}</td></tr>
                <tr><td class="py-1 text-gray-500">Designation</td><td>{{ $hseChecklist->employee?->designation ?? '—' }}</td></tr>
            </table>
        </div>

        <div class="panel">
            <h4 class="font-semibold mb-3">Summary</h4>
            @php
                $total = $hseChecklist->items->count();
                $compliant = $hseChecklist->items->where('is_compliant', true)->count();
            @endphp
            <table class="w-full text-sm">
                <tr><td class="py-1 text-gray-500 w-32">Total Items</td><td class="font-bold">{{ $total }}</td></tr>
                <tr><td class="py-1 text-gray-500 text-success">Compliant</td><td class="font-bold text-success">{{ $compliant }}</td></tr>
                <tr><td class="py-1 text-gray-500 text-danger">Non-Compliant</td><td class="font-bold text-danger">{{ $total - $compliant }}</td></tr>
                <tr><td class="py-1 text-gray-500">Compliance Rate</td><td class="font-bold">{{ $total > 0 ? round(($compliant / $total) * 100) : 0 }}%</td></tr>
            </table>
        </div>
    </div>

    @if($hseChecklist->findings)
    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Findings</h4>
        <p class="text-sm whitespace-pre-wrap">{{ $hseChecklist->findings }}</p>
    </div>
    @endif

    @if($hseChecklist->corrective_actions)
    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Corrective Actions</h4>
        <p class="text-sm whitespace-pre-wrap">{{ $hseChecklist->corrective_actions }}</p>
    </div>
    @endif

    @if($hseChecklist->items->isNotEmpty())
    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Checklist Items</h4>
        <div class="space-y-2">
            @foreach($hseChecklist->items as $item)
                <div class="flex items-start gap-3 rounded-lg border p-3">
                    <div class="mt-0.5">
                        @if($item->is_compliant)
                            <svg class="h-5 w-5 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        @else
                            <svg class="h-5 w-5 text-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium {{ $item->is_compliant ? '' : 'line-through text-white-dark' }}">{{ $item->item_name }}</p>
                        @if($item->remarks)
                            <p class="text-xs text-white-dark">{{ $item->remarks }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($hseChecklist->notes)
    <div class="panel mt-4">
        <h4 class="font-semibold mb-3">Notes</h4>
        <p class="text-sm whitespace-pre-wrap">{{ $hseChecklist->notes }}</p>
    </div>
    @endif
@endsection
