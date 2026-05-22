@extends('admin.layouts.master')

@section('title', 'Invoices')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Invoices</h2>
        <a href="{{ route('admin.finance.invoices.create') }}" class="btn btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            New Invoice
        </a>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.finance.invoices.index') }}" method="GET" class="flex items-center gap-3 w-full">
                <select name="project_id" class="form-select flex-1">
                    <option value="">Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select flex-1">
                    <option value="">Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="partially_paid" {{ request('status') == 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['project_id', 'status']))
                    <a href="{{ route('admin.finance.invoices.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Title</th>
                            <th>Project</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Total</th>
                            <th>Due</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $inv)
                            <tr>
                                <td><span class="font-mono text-xs font-semibold text-primary">{{ $inv->invoice_number }}</span></td>
                                <td class="font-semibold">{{ $inv->title }}</td>
                                <td class="text-xs">{{ $inv->project->name ?? 'N/A' }}</td>
                                <td class="text-xs">{{ $inv->issue_date->format('d M Y') }}</td>
                                <td class="text-xs">{{ $inv->due_date->format('d M Y') }}</td>
                                <td class="font-semibold">৳{{ number_format($inv->total_amount) }}</td>
                                <td class="font-semibold {{ $inv->due_amount > 0 ? 'text-danger' : 'text-success' }}">৳{{ number_format($inv->due_amount) }}</td>
                                <td>
                                    @php $sc = ['draft' => 'badge-outline-secondary', 'sent' => 'badge-outline-info', 'partially_paid' => 'badge-outline-warning', 'paid' => 'badge-outline-success', 'overdue' => 'badge-outline-danger', 'cancelled' => 'badge-outline-dark']; @endphp
                                    <span class="badge {{ $sc[$inv->status] ?? 'badge-outline-secondary' }} capitalize">{{ str_replace('_', ' ', $inv->status) }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.finance.invoices.show', $inv->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.finance.invoices.edit', $inv->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                        <form action="{{ route('admin.finance.invoices.destroy', $inv->id) }}" method="POST" onsubmit="return confirm('Delete this invoice?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center">No invoices found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $invoices->links() }}</div>
        </div>
    </div>
@endsection
