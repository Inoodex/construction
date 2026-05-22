@extends('admin.layouts.master')

@section('title', 'Vendors')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Vendors</h2>
        <div class="flex w-full flex-wrap items-center justify-end gap-4 sm:w-auto">
            <a href="{{ route('admin.procurement.vendors.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add Vendor
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.procurement.vendors.index') }}" method="GET" class="flex items-center gap-3 w-full">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email or phone..." class="form-input ltr:pr-11 rtl:pl-11 w-full" />
                    <button type="submit" class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5" />
                            <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <select name="status" class="form-select flex-1">
                    <option value="">Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="blacklisted" {{ request('status') == 'blacklisted' ? 'selected' : '' }}>Blacklisted</option>
                </select>
                <select name="trade_category" class="form-select flex-1">
                    <option value="">Category</option>
                    <option value="Electrical" {{ request('trade_category') == 'Electrical' ? 'selected' : '' }}>Electrical</option>
                    <option value="Plumbing" {{ request('trade_category') == 'Plumbing' ? 'selected' : '' }}>Plumbing</option>
                    <option value="Structural" {{ request('trade_category') == 'Structural' ? 'selected' : '' }}>Structural</option>
                    <option value="Finishing" {{ request('trade_category') == 'Finishing' ? 'selected' : '' }}>Finishing</option>
                    <option value="HVAC" {{ request('trade_category') == 'HVAC' ? 'selected' : '' }}>HVAC</option>
                    <option value="General" {{ request('trade_category') == 'General' ? 'selected' : '' }}>General</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['search', 'status', 'trade_category']))
                    <a href="{{ route('admin.procurement.vendors.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Trade Category</th>
                            <th>Credit Limit</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendors as $vendor)
                            <tr>
                                <td>
                                    <div class="font-semibold">{{ $vendor->name }}</div>
                                    <div class="text-xs text-white-dark">{{ $vendor->contact_name ?? '—' }}</div>
                                </td>
                                <td>
                                    <div class="text-xs">{{ $vendor->email ?? '—' }}</div>
                                    <div class="text-xs text-white-dark">{{ $vendor->phone ?? '—' }}</div>
                                </td>
                                <td>
                                    @if($vendor->trade_category)
                                        <span class="badge badge-outline-primary">{{ $vendor->trade_category }}</span>
                                    @else
                                        <span class="text-white-dark">—</span>
                                    @endif
                                </td>
                                <td class="text-xs font-semibold">
                                    @if($vendor->credit_limit)
                                        ৳{{ number_format($vendor->credit_limit) }}
                                    @else
                                        <span class="text-white-dark">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($vendor->performance_rating)
                                        <div class="flex items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="h-3.5 w-3.5 {{ $i <= $vendor->performance_rating ? 'text-warning' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                    @else
                                        <span class="text-white-dark text-xs">—</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusClasses = [
                                            'active' => 'badge-outline-success',
                                            'inactive' => 'badge-outline-secondary',
                                            'pending' => 'badge-outline-warning',
                                            'approved' => 'badge-outline-info',
                                            'rejected' => 'badge-outline-danger',
                                            'blacklisted' => 'badge-outline-dark',
                                        ];
                                        $class = $statusClasses[$vendor->status] ?? 'badge-outline-secondary';
                                    @endphp
                                    <span class="badge {{ $class }} capitalize">{{ $vendor->status }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.procurement.vendors.show', $vendor->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.procurement.vendors.edit', $vendor->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.procurement.vendors.destroy', $vendor->id) }}" method="POST" onsubmit="return confirm('Delete this vendor?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No vendors found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $vendors->links() }}
            </div>
        </div>
    </div>
@endsection
