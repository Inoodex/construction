@extends('admin.layouts.master')

@section('title', 'Material Wastage')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Material Wastage</h2>
        <a href="{{ route('admin.procurement.material-wastages.create') }}" class="btn btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Report Wastage
        </a>
    </div>

    <div class="panel mt-6">
        <div class="mb-5 flex flex-col gap-5 md:flex-row md:items-center">
            <form action="{{ route('admin.procurement.material-wastages.index') }}" method="GET" class="flex flex-1 flex-col gap-5 md:flex-row md:items-center w-full">
                <div class="flex gap-2">
                    <select name="project_id" class="form-select w-full md:w-44">
                        <option value="">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                    <select name="material_id" class="form-select w-full md:w-44">
                        <option value="">All Materials</option>
                        @foreach($materials as $material)
                            <option value="{{ $material->id }}" {{ request('material_id') == $material->id ? 'selected' : '' }}>{{ $material->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Site</th>
                            <th>Material</th>
                            <th>Qty</th>
                            <th>Reason</th>
                            <th>Date</th>
                            <th>Reported By</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wastages as $w)
                            <tr>
                                <td class="text-xs">{{ $w->project->name ?? 'N/A' }}</td>
                                <td class="text-xs">{{ $w->site->name ?? 'N/A' }}</td>
                                <td class="text-xs">{{ $w->material->name ?? 'N/A' }}</td>
                                <td class="text-xs">{{ number_format($w->quantity, 2) }} {{ $w->material->unit ?? '' }}</td>
                                <td class="text-xs max-w-[200px] truncate">{{ $w->reason }}</td>
                                <td class="text-xs">{{ $w->reported_date->format('d M Y') }}</td>
                                <td class="text-xs">{{ $w->reporter->name ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.procurement.material-wastages.show', $w->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.procurement.material-wastages.edit', $w->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                        <form action="{{ route('admin.procurement.material-wastages.destroy', $w->id) }}" method="POST" onsubmit="return confirm('Delete this wastage record?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center">No wastage records found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $wastages->links() }}</div>
        </div>
    </div>
@endsection
