@extends('admin.layouts.master')

@section('title', 'Budgets')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Budgets</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.finance.budgets.forecasting') }}" class="btn btn-outline-info gap-2">Forecasting</a>
            <a href="{{ route('admin.finance.budgets.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                New Budget
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.finance.budgets.index') }}" method="GET" class="flex items-center gap-3 w-full">
                <select name="project_id" class="form-select flex-1">
                    <option value="">Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
                <input type="text" name="cost_code" value="{{ request('cost_code') }}" placeholder="Cost Code" class="form-input flex-1" />
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['project_id', 'cost_code']))
                    <a href="{{ route('admin.finance.budgets.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Cost Code</th>
                            <th>Description</th>
                            <th>Budgeted</th>
                            <th>Actual</th>
                            <th>Variance</th>
                            <th>Year</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($budgets as $b)
                            @php $variance = $b->budgeted_amount - $b->actual_cost; @endphp
                            <tr>
                                <td>{{ $b->project->name ?? 'N/A' }}</td>
                                <td><span class="font-mono text-xs font-semibold text-primary">{{ $b->cost_code }}</span></td>
                                <td>{{ $b->description ?? '-' }}</td>
                                <td>৳{{ number_format($b->budgeted_amount) }}</td>
                                <td>৳{{ number_format($b->actual_cost) }}</td>
                                <td>
                                    <span class="font-semibold {{ $variance >= 0 ? 'text-success' : 'text-danger' }}">
                                        @if($variance >= 0)+@endif{{ number_format($variance) }}
                                    </span>
                                </td>
                                <td>{{ $b->financial_year ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.finance.budgets.show', $b->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.finance.budgets.edit', $b->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                        <form action="{{ route('admin.finance.budgets.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Delete this budget item?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center">No budgets found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $budgets->links() }}</div>
        </div>
    </div>
@endsection
