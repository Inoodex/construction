@extends('admin.layouts.master')

@section('title', 'Pending Approvals')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Pending Approvals</h2>
    </div>

    <div class="panel mt-6">
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Document Type</th>
                            <th>Document</th>
                            <th>Amount</th>
                            <th>Level</th>
                            <th>Submitted By</th>
                            <th>Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($approvals as $approval)
                            <tr>
                                <td>
                                    <span class="badge badge-outline-info capitalize">{{ str_replace('_', ' ', $approval->workflow->document_type) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.approvals.show', $approval) }}" class="font-semibold text-primary">
                                        {{ class_basename($approval->approvable_type) }} #{{ $approval->approvable_id }}
                                    </a>
                                </td>
                                <td class="font-semibold">৳{{ number_format($approval->total_amount, 2) }}</td>
                                <td class="text-xs">Level {{ $approval->current_level }} of {{ $approval->workflow->matrices->max('approval_level') }}</td>
                                <td class="text-xs">{{ $approval->submitter->name }}</td>
                                <td class="text-xs">{{ $approval->submitted_at->format('d M Y h:i A') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.approvals.show', $approval) }}" class="btn btn-sm btn-outline-info">Review</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No pending approvals found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
