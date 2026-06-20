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
                            <td class="flex gap-1">
                                <a href="{{ route('admin.finance.material-takeoffs.show', $t) }}" class="btn btn-xs btn-outline-info">View</a>
                                <a href="{{ route('admin.finance.material-takeoffs.edit', $t) }}" class="btn btn-xs btn-outline-secondary">Edit</a>
                                <form action="{{ route('admin.finance.material-takeoffs.destroy', $t) }}" method="POST" class="inline" onsubmit="return confirm('Delete this takeoff?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-outline-danger">Delete</button>
                                </form>
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
