@extends('admin.layouts.master')

@section('title', 'Transmittal Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">{{ $transmittal->transmittal_number }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.core.documents.transmittals.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back
            </a>
            @if(!auth()->user()->hasRole('client'))
                <a href="{{ route('admin.core.documents.transmittals.edit', $transmittal) }}" class="btn btn-primary gap-2">Edit</a>
            @endif
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="panel lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h5 class="text-base font-semibold">Transmittal Details</h5>
                @php $sColors = ['draft' => 'badge-outline-secondary', 'sent' => 'badge-outline-info', 'acknowledged' => 'badge-outline-success']; @endphp
                <span class="badge {{ $sColors[$transmittal->status] ?? 'badge-outline-secondary' }} capitalize">{{ $transmittal->status }}</span>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div><label class="text-xs text-white-dark">Transmittal #</label><p class="font-semibold">{{ $transmittal->transmittal_number }}</p></div>
                <div><label class="text-xs text-white-dark">Project</label><p class="font-semibold">{{ $transmittal->project->name ?? '—' }}</p></div>
                <div><label class="text-xs text-white-dark">To Party</label><p class="font-semibold">{{ $transmittal->to_party }}</p></div>
                <div><label class="text-xs text-white-dark">From</label><p class="font-semibold">{{ $transmittal->fromUser->name ?? '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Sent Date</label><p class="font-semibold">{{ $transmittal->sent_date->format('d M Y') }}</p></div>
                <div><label class="text-xs text-white-dark">Purpose</label><p class="font-semibold capitalize">{{ str_replace('_', ' ', $transmittal->purpose) }}</p></div>
            </div>

            @if($transmittal->notes)
                <hr class="my-4 border-white-light dark:border-gray-700">
                <div><label class="text-xs text-white-dark">Notes</label><p class="mt-1 whitespace-pre-wrap">{{ $transmittal->notes }}</p></div>
            @endif

            <hr class="my-4 border-white-light dark:border-gray-700">
            <h6 class="mb-3 text-sm font-semibold">Drawing Items</h6>
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Drawing</th>
                            <th>Revision</th>
                            <th class="text-center">Copies</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transmittal->items as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td class="text-xs font-semibold">{{ $item->drawing->drawing_number ?? '—' }} — {{ $item->drawing->title ?? '—' }}</td>
                                <td class="text-xs">{{ $item->revision->revision ?? '—' }}</td>
                                <td class="text-center">{{ $item->copies }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center">No items.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel">
            <h5 class="mb-4 text-base font-semibold">Info</h5>
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs">Created</span>
                    <span class="text-xs font-semibold">{{ $transmittal->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs">Last Updated</span>
                    <span class="text-xs font-semibold">{{ $transmittal->updated_at->format('d M Y') }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs">Total Drawings</span>
                    <span class="text-xs font-semibold">{{ $transmittal->items->count() }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
