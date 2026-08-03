@extends('admin.layouts.master')

@section('title', 'RFIs')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Request for Information (RFI)</h2>
        @if(!auth()->user()->hasRole('client'))
            <a href="{{ route('admin.core.documents.rfis.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                New RFI
            </a>
        @endif
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.core.documents.rfis.index') }}" method="GET" class="flex items-center gap-3 w-full">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by RFI number or subject..." class="form-input ltr:pr-11 rtl:pl-11 w-full" />
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
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="answered" {{ request('status') == 'answered' ? 'selected' : '' }}>Answered</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                <select name="priority" class="form-select flex-1">
                    <option value="">All Priority</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['search', 'project_id', 'status', 'priority']))
                    <a href="{{ route('admin.core.documents.rfis.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>RFI #</th>
                            <th>Subject</th>
                            <th>Project</th>
                            <th>Raised By</th>
                            <th>Assigned To</th>
                            <th>Priority</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rfis as $rfi)
                            <tr>
                                <td class="text-xs font-semibold">{{ $rfi->rfi_number }}</td>
                                <td>
                                    <div class="font-semibold">{{ $rfi->subject }}</div>
                                    @if($rfi->drawing)
                                        <div class="text-xs text-white-dark">Ref: {{ $rfi->drawing->drawing_number }}</div>
                                    @endif
                                </td>
                                <td class="text-xs">{{ $rfi->project->name ?? '—' }}</td>
                                <td class="text-xs">{{ $rfi->raiser->name ?? '—' }}</td>
                                <td class="text-xs">{{ $rfi->assignee->name ?? '—' }}</td>
                                <td>
                                    @php $pColors = ['low' => 'badge-outline-success', 'medium' => 'badge-outline-warning', 'high' => 'badge-outline-danger']; @endphp
                                    <span class="badge {{ $pColors[$rfi->priority] ?? 'badge-outline-secondary' }} capitalize">{{ $rfi->priority }}</span>
                                </td>
                                <td class="text-xs">{{ $rfi->due_date?->format('d M Y') ?: '—' }}</td>
                                <td>
                                    @php $sColors = ['open' => 'badge-outline-info', 'answered' => 'badge-outline-success', 'closed' => 'badge-outline-secondary']; @endphp
                                    <span class="badge {{ $sColors[$rfi->status] ?? 'badge-outline-secondary' }} capitalize">{{ $rfi->status }}</span>
                                </td>
                                <td class="text-center">
                                    <x-action-dropdown>
                                        <x-slot name="items">
                                            <li><a href="{{ route('admin.core.documents.rfis.show', $rfi) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                            <li><a href="{{ route('admin.core.documents.rfis.pdf', $rfi->id) }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> PDF</a></li>
                                            @if(!auth()->user()->hasRole('client'))
                                            <li><a href="{{ route('admin.core.documents.rfis.edit', $rfi) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/></svg> Edit</a></li>
                                            <li class="divider"></li>
                                            <li class="danger">
                                                <form action="{{ route('admin.core.documents.rfis.destroy', $rfi) }}" method="POST" onsubmit="return confirm('Delete this RFI?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 001-1h4a1 1 0 001 1m-6 0h6"/></svg> Delete</button>
                                                </form>
                                            </li>
                                            @endif
                                        </x-slot>
                                    </x-action-dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center">No RFIs found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $rfis->links() }}</div>
        </div>
    </div>
@endsection
