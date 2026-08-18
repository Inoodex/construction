@extends('admin.layouts.master')

@section('title', 'HSE Checklists')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">HSE Checklists</h2>
        <a href="{{ route('admin.hr.hse-checklists.create') }}" class="btn btn-primary gap-2">+ New HSE Checklist</a>
    </div>

    <div class="panel mt-6">
        <form method="GET" class="mb-4 flex flex-nowrap items-end gap-2 overflow-x-auto">
            <div>
                <label class="text-xs font-semibold">Type</label>
                <select name="checklist_type" class="form-select" style="min-width: 150px">
                    <option value="">All Types</option>
                    <option value="general" {{ request('checklist_type') == 'general' ? 'selected' : '' }}>General</option>
                    <option value="fire" {{ request('checklist_type') == 'fire' ? 'selected' : '' }}>Fire Safety</option>
                    <option value="electrical" {{ request('checklist_type') == 'electrical' ? 'selected' : '' }}>Electrical</option>
                    <option value="scaffolding" {{ request('checklist_type') == 'scaffolding' ? 'selected' : '' }}>Scaffolding</option>
                    <option value="ppe" {{ request('checklist_type') == 'ppe' ? 'selected' : '' }}>PPE Compliance</option>
                    <option value="excavation" {{ request('checklist_type') == 'excavation' ? 'selected' : '' }}>Excavation</option>
                    <option value="other" {{ request('checklist_type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold">Status</label>
                <select name="status" class="form-select" style="min-width: 150px">
                    <option value="">All Status</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['checklist_type', 'status']))
                    <a href="{{ route('admin.hr.hse-checklists.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Project / Site</th>
                        <th>Inspector</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $r)
                        <tr>
                            <td>{{ $r->title }}</td>
                            <td><span class="badge badge-outline-{{ $r->checklist_type === 'fire' ? 'danger' : ($r->checklist_type === 'ppe' ? 'success' : 'info') }}">{{ str_replace('-', ' ', ucfirst($r->checklist_type)) }}</span></td>
                            <td>{{ $r->project?->name ?? '—' }} / {{ $r->site?->name ?? 'All' }}</td>
                            <td>{{ $r->inspector?->name ?? '—' }}</td>
                            <td>{{ $r->inspection_date->format('d M Y') }}</td>
                            <td>
                                <span class="badge {{ $r->status === 'closed' ? 'badge-outline-success' : 'badge-outline-warning' }}">{{ ucfirst($r->status) }}</span>
                            </td>
                            <td class="text-center">
                                <x-action-dropdown>
                                    <x-slot name="items">
                                        <li><a href="{{ route('admin.hr.hse-checklists.show', $r) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                        <li><a href="{{ route('admin.hr.hse-checklists.edit', $r) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/></svg> Edit</a></li>
                                        <li class="divider"></li>
                                        <li class="danger">
                                            <form action="{{ route('admin.hr.hse-checklists.destroy', $r) }}" method="POST" onsubmit="return confirm('Delete this checklist?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 001-1h4a1 1 0 001 1m-6 0h6"/></svg> Delete</button>
                                            </form>
                                        </li>
                                    </x-slot>
                                </x-action-dropdown>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-gray-400 py-4">No HSE checklists found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $records->links() }}</div>
    </div>
@endsection
