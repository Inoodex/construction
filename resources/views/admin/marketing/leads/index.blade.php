@extends('admin.layouts.master')

@section('title', 'Primary Data')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Primary Data</h2>
        @can('create-lead')
        <div class="flex w-full flex-wrap items-center justify-end gap-4 sm:w-auto">
            <a href="{{ route('admin.marketing.leads.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="h-5 w-5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add Data
            </a>
        </div>
        @endcan
    </div>

    <div class="panel mt-6">
        <div class="mb-5 flex flex-col gap-5 md:flex-row md:items-center">
            <form action="{{ route('admin.marketing.leads.index') }}" method="GET" class="flex flex-1 flex-col gap-5 md:flex-row md:items-center w-full">
                <div class="relative w-full md:w-[405px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Student Name or Phone..." class="form-input ltr:pr-11 rtl:pl-11" />
                    <button type="submit" class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5" />
                            <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <div class="flex gap-2">
                    <select name="status" class="form-select w-full md:w-40 pr-10">
                        <option value="">Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="interested" {{ request('status') == 'interested' ? 'selected' : '' }}>Interested</option>
                        <option value="forwarded" {{ request('status') == 'forwarded' ? 'selected' : '' }}>Forwarded</option>
                        <option value="junk" {{ request('status') == 'junk' ? 'selected' : '' }}>Junk</option>
                    </select>
                    <!-- <select name="source" class="form-select w-full md:w-40">
                        <option value="">All Sources</option>
                        <option value="Phone" {{ request('source') == 'Phone' ? 'selected' : '' }}>Phone</option>
                        <option value="Message" {{ request('source') == 'Message' ? 'selected' : '' }}>Message</option>
                        <option value="Chat" {{ request('source') == 'Chat' ? 'selected' : '' }}>Chat</option>
                    </select> -->
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Phone</th>
                            <th>Contact Source</th>
                            <th>Follow-up Date</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leads as $lead)
                            <tr>
                                <td>
                                    <div class="font-semibold">{{ $lead->student_name }}</div>
                                    <div class="text-xs text-white-dark">{{ $lead->email ?? 'No Email' }}</div>
                                </td>
                                <td>{{ $lead->phone }}</td>
                                <td>
                                    <span class="badge badge-outline-primary">{{ $lead->source }}</span>
                                </td>
                                <td>
                                    @if($lead->next_follow_up_at)
                                        <div class="{{ $lead->next_follow_up_at->isPast() ? 'text-danger font-bold' : '' }}">
                                            {{ $lead->next_follow_up_at->format('M d, Y') }}
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <span class="badge @if($lead->status == 'pending') badge-outline-warning @elseif($lead->status == 'interested') badge-outline-success @elseif($lead->status == 'forwarded') badge-outline-info @else badge-outline-danger @endif capitalize">
                                        {{ $lead->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @can('update-lead')
                                        <a href="{{ route('admin.marketing.leads.edit', $lead->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        @endcan
                                        @can('delete-lead')
                                        <form action="{{ route('admin.marketing.leads.destroy', $lead->id) }}" method="POST" onsubmit="return confirm('Delete this lead?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $leads->links() }}
            </div>
        </div>
    </div>
@endsection
