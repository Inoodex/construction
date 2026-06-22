@extends('admin.layouts.master')

@section('title', 'Bills Payable')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Bills Payable</h2>
        <a href="{{ route('admin.finance.bills.create') }}" class="btn btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            New Bill
        </a>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.finance.bills.index') }}" method="GET" class="flex items-center gap-3 w-full">
                <select name="project_id" class="form-select flex-1">
                    <option value="">Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
                <select name="vendor_id" class="form-select flex-1">
                    <option value="">Vendor</option>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select flex-1">
                    <option value="">Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['project_id', 'vendor_id', 'status']))
                    <a href="{{ route('admin.finance.bills.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Bill #</th>
                            <th>Title</th>
                            <th>Vendor</th>
                            <th>Project</th>
                            <th>Bill Date</th>
                            <th>Due Date</th>
                            <th>Total</th>
                            <th>Due</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bills as $bill)
                            <tr>
                                <td><span class="font-mono text-xs font-semibold text-primary">{{ $bill->bill_number }}</span></td>
                                <td class="font-semibold">{{ $bill->title }}</td>
                                <td>{{ $bill->vendor->name ?? 'N/A' }}</td>
                                <td>{{ $bill->project->name ?? 'N/A' }}</td>
                                <td>{{ $bill->bill_date->format('d/m/Y') }}</td>
                                <td {{ $bill->due_amount > 0 && $bill->due_date?->isPast() ? 'text-danger' : '' }}">{{ $bill->due_date->format('d/m/Y') }}</td>
                                <td class="font-semibold">{{ number_format($bill->total_amount) }}</td>
                                <td class="font-semibold {{ $bill->due_amount > 0 ? 'text-danger' : 'text-success' }}">{{ number_format($bill->due_amount) }}</td>
                                <td>
                                    @php $sc = ['draft' => 'badge-outline-secondary', 'approved' => 'badge-outline-primary', 'paid' => 'badge-outline-success', 'overdue' => 'badge-outline-danger', 'cancelled' => 'badge-outline-dark']; @endphp
                                    <span class="badge {{ $sc[$bill->status] ?? 'badge-outline-secondary' }} capitalize">{{ $bill->status }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.finance.bills.show', $bill->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.finance.bills.edit', $bill->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                        <form action="{{ route('admin.finance.bills.destroy', $bill->id) }}" method="POST" onsubmit="return confirm('Delete this bill?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="text-center">No bills found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $bills->links() }}</div>
        </div>
    </div>
@endsection
