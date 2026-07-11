@extends('admin.layouts.master')

@section('title', 'Drawing Transmittals')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Drawing Transmittals</h2>
        @if(!auth()->user()->hasRole('client'))
            <a href="{{ route('admin.core.documents.transmittals.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                New Transmittal
            </a>
        @endif
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.core.documents.transmittals.index') }}" method="GET" class="flex items-center gap-3 w-full">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by transmittal # or recipient..." class="form-input ltr:pr-11 rtl:pl-11 w-full" />
                    <button type="submit" class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5" /><path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" /></svg>
                    </button>
                </div>
                <select name="project_id" class="form-select flex-1">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select flex-1">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="acknowledged" {{ request('status') == 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['search', 'project_id', 'status']))
                    <a href="{{ route('admin.core.documents.transmittals.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Transmittal #</th>
                            <th>Project</th>
                            <th>To Party</th>
                            <th>Sent Date</th>
                            <th>Purpose</th>
                            <th>Drawings</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transmittals as $t)
                            <tr>
                                <td class="text-xs font-semibold">{{ $t->transmittal_number }}</td>
                                <td class="text-xs">{{ $t->project->name ?? '—' }}</td>
                                <td class="text-xs">{{ $t->to_party }}</td>
                                <td class="text-xs">{{ $t->sent_date->format('d M Y') }}</td>
                                <td class="text-xs capitalize">{{ str_replace('_', ' ', $t->purpose) }}</td>
                                <td class="text-xs">{{ $t->items_count ?? $t->items->count() }}</td>
                                <td>
                                    @php $sColors = ['draft' => 'badge-outline-secondary', 'sent' => 'badge-outline-info', 'acknowledged' => 'badge-outline-success']; @endphp
                                    <span class="badge {{ $sColors[$t->status] ?? 'badge-outline-secondary' }} capitalize">{{ $t->status }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('admin.core.documents.transmittals.show', $t) }}" class="btn btn-sm btn-outline-info">View</a>
                                        @if(!auth()->user()->hasRole('client'))
                                            <a href="{{ route('admin.core.documents.transmittals.edit', $t) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <form action="{{ route('admin.core.documents.transmittals.destroy', $t) }}" method="POST" onsubmit="return confirm('Delete this transmittal?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center">No transmittals found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $transmittals->links() }}</div>
        </div>
    </div>
@endsection
