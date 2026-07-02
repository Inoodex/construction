@extends('admin.layouts.master')

@section('title', 'ETC / EAC Forecasting')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Forecasting (ETC / EAC)</h2>
        <a href="{{ route('admin.finance.budgets.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form method="GET" class="mb-4 flex flex-nowrap items-end gap-2">
            <div>
                <label class="text-xs font-semibold">Project</label>
                <select name="project_id" class="form-select" style="min-width:250px;">
                    <option value="">All Projects</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request('project_id'))
                    <a href="{{ route('admin.finance.budgets.forecasting') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </div>
        </form>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="rounded-lg border p-4 dark:border-gray-700">
                <p class="text-xs text-gray-500">Total Budget (BAC)</p>
                <p class="text-xl font-bold">{{ number_format($totals['budgeted'], 2) }}</p>
            </div>
            <div class="rounded-lg border p-4 dark:border-gray-700">
                <p class="text-xs text-gray-500">Estimate to Complete (ETC)</p>
                <p class="text-xl font-bold text-warning">{{ number_format($totals['etc'], 2) }}</p>
            </div>
            <div class="rounded-lg border p-4 dark:border-gray-700">
                <p class="text-xs text-gray-500">Estimate at Completion (EAC)</p>
                <p class="text-xl font-bold text-{{ $totals['eac'] > $totals['budgeted'] ? 'danger' : 'success' }}">{{ number_format($totals['eac'], 2) }}</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Cost Code</th>
                        <th class="text-right">BAC</th>
                        <th class="text-right">PV</th>
                        <th class="text-right">EV</th>
                        <th class="text-right">AC</th>
                        <th class="text-center">SPI</th>
                        <th class="text-center">CPI</th>
                        <th class="text-right">ETC</th>
                        <th class="text-right">EAC</th>
                        <th class="text-right">Variance</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($budgets as $b)
                        <tr>
                            <td class="font-semibold">{{ $b->project->name }}</td>
                            <td>{{ $b->cost_code }}</td>
                            <td class="text-right">{{ number_format($b->budgeted_amount, 2) }}</td>
                            <td class="text-right">{{ number_format($b->planned_value, 2) }}</td>
                            <td class="text-right">{{ number_format($b->earned_value, 2) }}</td>
                            <td class="text-right">{{ number_format($b->actual_cost, 2) }}</td>
                            <td class="text-center">
                                <span class="badge badge-outline-{{ $b->spi >= 1 ? 'success' : 'danger' }}">{{ number_format($b->spi, 2) }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-outline-{{ $b->cpi >= 1 ? 'success' : 'danger' }}">{{ number_format($b->cpi, 2) }}</span>
                            </td>
                            <td class="text-right">{{ number_format($b->etc, 2) }}</td>
                            <td class="text-right font-semibold">{{ number_format($b->eac, 2) }}</td>
                            <td class="text-right {{ $b->variance < 0 ? 'text-danger' : 'text-success' }}">{{ number_format($b->variance, 2) }}</td>
                            <td>
                                <a href="{{ route('admin.finance.budgets.edit', $b) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="12" class="text-center text-gray-400 py-4">No budgets found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
