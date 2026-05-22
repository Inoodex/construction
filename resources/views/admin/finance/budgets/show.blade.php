@extends('admin.layouts.master')

@section('title', 'Budget Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Budget Details</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.finance.budgets.edit', $budget->id) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('admin.finance.budgets.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to List
            </a>
        </div>
    </div>

    @php $variance = $budget->budgeted_amount - $budget->actual_amount; @endphp
    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="panel lg:col-span-2">
            <h5 class="mb-4 text-base font-semibold">Budget Information</h5>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="text-xs text-white-dark">Project</label>
                    <p class="font-semibold">{{ $budget->project->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Cost Code</label>
                    <p class="font-mono font-semibold text-primary">{{ $budget->cost_code }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="text-xs text-white-dark">Description</label>
                    <p class="font-semibold">{{ $budget->description ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Financial Year</label>
                    <p class="font-semibold">{{ $budget->financial_year ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Created By</label>
                    <p class="font-semibold">{{ $budget->creator->name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="panel">
            <h5 class="mb-4 text-base font-semibold">Financial Summary</h5>
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Budgeted</span>
                    <span class="text-sm font-bold dark:text-white">৳{{ number_format($budget->budgeted_amount) }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Actual</span>
                    <span class="text-sm font-bold dark:text-white">৳{{ number_format($budget->actual_amount) }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg p-3 {{ $variance >= 0 ? 'bg-success/10' : 'bg-danger/10' }}">
                    <span class="text-xs font-semibold {{ $variance >= 0 ? 'text-success' : 'text-danger' }}">Variance</span>
                    <span class="text-sm font-bold {{ $variance >= 0 ? 'text-success' : 'text-danger' }}">
                        @if($variance >= 0)+@endif{{ number_format($variance) }}
                    </span>
                </div>
                @if($budget->budgeted_amount > 0)
                    @php $pct = round(($budget->actual_amount / $budget->budgeted_amount) * 100, 1); @endphp
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                        <span class="text-xs text-gray-600 dark:text-gray-300">Utilization</span>
                        <span class="text-sm font-bold dark:text-white">{{ $pct }}%</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if($budget->notes)
        <div class="panel mt-6">
            <h5 class="mb-4 text-base font-semibold">Notes</h5>
            <p>{{ $budget->notes }}</p>
        </div>
    @endif
@endsection
