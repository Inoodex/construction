@extends('admin.layouts.master')

@section('title', 'Expenses')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Expenses</h2>
        <a href="{{ route('admin.finance.expenses.create') }}" class="btn btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            New Expense
        </a>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.finance.expenses.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full">
                <select name="category_id" class="form-select flex-1">
                    <option value="">Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->label }}</option>
                    @endforeach
                </select>
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
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
                <input type="date" name="from" class="form-input flex-1" value="{{ request('from') }}" placeholder="From" />
                <input type="date" name="to" class="form-input flex-1" value="{{ request('to') }}" placeholder="To" />
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['category_id', 'project_id', 'vendor_id', 'status', 'from', 'to']))
                    <a href="{{ route('admin.finance.expenses.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Vendor</th>
                            <th>Project</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                            <tr>
                                <td class="font-semibold">{{ $expense->title }}</td>
                                <td>{{ $expense->category->label ?? 'N/A' }}</td>
                                <td>{{ $expense->vendor->name ?? '-' }}</td>
                                <td>{{ $expense->project->name ?? '-' }}</td>
                                <td>{{ $expense->expense_date->format('d/m/Y') }}</td>
                                <td class="font-semibold">{{ number_format($expense->total_amount) }}</td>
                                <td>
                                    @php $sc = ['draft' => 'badge-outline-secondary', 'paid' => 'badge-outline-success']; @endphp
                                    <span class="badge {{ $sc[$expense->status] ?? 'badge-outline-secondary' }} capitalize">{{ $expense->status }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.finance.expenses.show', $expense->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.finance.expenses.edit', $expense->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                        <form action="{{ route('admin.finance.expenses.destroy', $expense->id) }}" method="POST" onsubmit="return confirm('Delete this expense?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="text-center">No expenses found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $expenses->links() }}</div>
        </div>
    </div>
@endsection
