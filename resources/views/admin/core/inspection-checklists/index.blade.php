@extends('admin.layouts.master')

@section('title', 'Inspection Checklists')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Inspection Checklists</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.core.inspection-checklists.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                New Checklist
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.core.inspection-checklists.index') }}" method="GET" class="flex items-center gap-3 w-full">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search checklists..." class="form-input ltr:pr-11 rtl:pl-11 w-full" />
                    <button type="submit" class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5" /><path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" /></svg>
                    </button>
                </div>
                <select name="site_id" class="form-select flex-1">
                    <option value="">All Sites</option>
                    @foreach($sites as $site)
                        <option value="{{ $site->id }}" {{ request('site_id') == $site->id ? 'selected' : '' }}>{{ $site->name }} ({{ $site->project->name ?? '—' }})</option>
                    @endforeach
                </select>
                <select name="status" class="form-select flex-1">
                    <option value="">Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="passed" {{ request('status') == 'passed' ? 'selected' : '' }}>Passed</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="conditional" {{ request('status') == 'conditional' ? 'selected' : '' }}>Conditional</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['search', 'site_id', 'status']))
                    <a href="{{ route('admin.core.inspection-checklists.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Site</th>
                            <th>Inspector</th>
                            <th>Inspection Date</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($checklists as $cl)
                            <tr>
                                <td>
                                    <div class="font-semibold">{{ $cl->title }}</div>
                                    <div class="text-xs text-white-dark">{{ Str::limit($cl->description, 40) }}</div>
                                </td>
                                <td class="text-xs">{{ $cl->site->name ?? '—' }}</td>
                                <td class="text-xs">{{ $cl->inspector->name ?? '—' }}</td>
                                <td class="text-xs">{{ $cl->inspection_date->format('d M Y') }}</td>
                                <td class="text-xs">{{ $cl->items_count ?? $cl->items->count() ?? 0 }}</td>
                                <td>
                                    @php $colors = ['pending' => 'badge-outline-warning', 'passed' => 'badge-outline-success', 'failed' => 'badge-outline-danger', 'conditional' => 'badge-outline-info']; @endphp
                                    <span class="badge {{ $colors[$cl->status] ?? 'badge-outline-secondary' }} capitalize">{{ $cl->status }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.core.inspection-checklists.show', $cl) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.core.inspection-checklists.edit', $cl) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.core.inspection-checklists.destroy', $cl) }}" method="POST" onsubmit="return confirm('Delete this checklist?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">No inspection checklists found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $checklists->links() }}</div>
        </div>
    </div>
@endsection
