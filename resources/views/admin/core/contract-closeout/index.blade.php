@extends('admin.layouts.master')

@section('title', 'Contract Closeout Checklists')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Contract Closeout Checklists</h2>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.core.contract-closeout.index') }}" method="GET" class="flex items-center gap-3 w-full">
                <div class="relative" style="width: 300px;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search contracts..." class="form-input ltr:pr-11 rtl:pl-11 w-full" />
                    <button type="submit" class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5" /><path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" /></svg>
                    </button>
                </div>
                <select name="complete" class="form-select" style="width: 160px;">
                    <option value="">All Progress</option>
                    <option value="yes" {{ request('complete') == 'yes' ? 'selected' : '' }}>Fully Complete</option>
                    <option value="no" {{ request('complete') == 'no' ? 'selected' : '' }}>Incomplete</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['search', 'complete']))
                    <a href="{{ route('admin.core.contract-closeout.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Contract #</th>
                            <th>Title</th>
                            <th>Project</th>
                            <th>Total Items</th>
                            <th>Completed</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contracts as $contract)
                            @php
                                $total = $contract->closeout_items_count;
                                $completed = $contract->completed_items_count;
                                $pct = $total > 0 ? round(($completed / $total) * 100) : 0;
                                $isComplete = $total > 0 && $completed === $total;
                            @endphp
                            <tr>
                                <td class="font-mono text-xs font-semibold text-primary">{{ $contract->contract_number }}</td>
                                <td class="font-semibold">{{ $contract->title }}</td>
                                <td class="text-xs">{{ $contract->project->name ?? '—' }}</td>
                                <td class="text-center">{{ $total }}</td>
                                <td class="text-center font-semibold {{ $isComplete ? 'text-success' : '' }}">{{ $completed }}</td>
                                <td style="min-width: 150px;">
                                    <div class="flex items-center gap-2">
                                        <div class="progress h-2 w-full rounded-full bg-gray-200">
                                            <div class="h-2 rounded-full {{ $isComplete ? 'bg-success' : 'bg-primary' }}" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <span class="text-xs font-semibold whitespace-nowrap">{{ $pct }}%</span>
                                    </div>
                                </td>
                                <td>
                                    @if($isComplete)
                                        <span class="badge badge-outline-success">Complete</span>
                                    @elseif($completed > 0)
                                        <span class="badge badge-outline-warning">In Progress</span>
                                    @else
                                        <span class="badge badge-outline-secondary">Not Started</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.core.contracts.show', $contract) }}#closeout" class="btn btn-sm btn-outline-info">View Checklist</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center">No contracts with closeout checklists found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $contracts->links() }}</div>
        </div>
    </div>
@endsection
