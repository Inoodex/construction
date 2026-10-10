@extends('admin.layouts.master')

@section('title', 'Material Takeoffs')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Material Takeoff Sheets</h2>
        <a href="{{ route('admin.finance.material-takeoffs.create') }}" class="btn btn-primary gap-2">+ New Takeoff</a>
    </div>

    <div class="panel mt-6">
        <form method="GET" class="mb-4">
            <div>
                <label class="text-sm font-semibold">Project</label>
                <select name="project_id" class="form-select" onchange="this.form.submit()">
                    <option value="">All Projects</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>BOQ Item</th>
                        <th>Description</th>
                        <th>Unit</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Unit Price</th>
                        <th class="text-right">Total</th>
                        <th>Drawing</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($takeoffs as $t)
                        <tr>
                            <td class="font-semibold">{{ $t->project->name }}</td>
                            <td class="text-xs">{{ $t->boqItem?->item_number ?? '—' }}</td>
                            <td>{{ $t->description }}</td>
                            <td>{{ $t->unit ?? '—' }}</td>
                            <td class="text-right">{{ number_format($t->quantity, 2) }}</td>
                            <td class="text-right">{{ number_format($t->unit_price, 2) }}</td>
                            <td class="text-right font-semibold">{{ number_format($t->total_price, 2) }}</td>
                            <td>{{ $t->source_drawing ?? '—' }}</td>
                            <td class="text-center">
                                <x-action-dropdown>
                                    <x-slot name="items">
                                        <li><a href="{{ route('admin.finance.material-takeoffs.show', $t) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> View</a></li>
                                        <li><a href="{{ route('admin.finance.material-takeoffs.edit', $t) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/></svg> Edit</a></li>
                                        <li class="divider"></li>
                                        <li class="danger">
                                            <form action="{{ route('admin.finance.material-takeoffs.destroy', $t) }}" method="POST" onsubmit="return confirm('Delete this takeoff?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 001-1h4a1 1 0 001 1m-6 0h6"/></svg> Delete</button>
                                            </form>
                                        </li>
                                    </x-slot>
                                </x-action-dropdown>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-gray-400 py-4">No takeoffs found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $takeoffs->links() }}</div>
    </div>
@endsection
