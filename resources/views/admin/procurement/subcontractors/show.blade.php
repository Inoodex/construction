@extends('admin.layouts.master')

@section('title', 'Subcontractor Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Subcontractor Details</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.procurement.subcontractors.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to List
            </a>
            <a href="{{ route('admin.procurement.subcontractors.edit', $subcontractor->id) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Edit
            </a>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="panel lg:col-span-2">
            <h5 class="mb-4 text-base font-semibold">General Information</h5>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="text-xs text-white-dark">Subcontractor Name</label>
                    <p class="font-semibold">{{ $subcontractor->name }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Contact Person</label>
                    <p class="font-semibold">{{ $subcontractor->contact_name ?? '—' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Email</label>
                    <p class="font-semibold">{{ $subcontractor->email ?? '—' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Phone</label>
                    <p class="font-semibold">{{ $subcontractor->phone ?? '—' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Trade Category</label>
                    <p class="font-semibold">{{ $subcontractor->trade_category ?? '—' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Specialization</label>
                    <p class="font-semibold">{{ $subcontractor->specialization ?? '—' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">License Number</label>
                    <p class="font-semibold">{{ $subcontractor->license_number ?? '—' }}</p>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Status</label>
                    <div>
                        @php
                            $statusClasses = [
                                'active' => 'badge-outline-success',
                                'inactive' => 'badge-outline-secondary',
                                'pending' => 'badge-outline-warning',
                                'approved' => 'badge-outline-info',
                                'rejected' => 'badge-outline-danger',
                                'suspended' => 'badge-outline-dark',
                            ];
                            $class = $statusClasses[$subcontractor->status] ?? 'badge-outline-secondary';
                        @endphp
                        <span class="badge {{ $class }} capitalize">{{ $subcontractor->status }}</span>
                    </div>
                </div>
                <div>
                    <label class="text-xs text-white-dark">Performance Rating</label>
                    <p class="font-semibold">
                        @if($subcontractor->performance_rating)
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="inline h-4 w-4 {{ $i <= $subcontractor->performance_rating ? 'text-warning' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        @else
                            Not Rated
                        @endif
                    </p>
                </div>
            </div>

            <div class="mt-4">
                <label class="text-xs text-white-dark">Address</label>
                <p class="font-semibold">{{ $subcontractor->address ?: '—' }}</p>
            </div>
        </div>

        <div class="panel">
            <h5 class="mb-4 text-base font-semibold">Summary</h5>
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Created</span>
                    <span class="text-xs font-semibold dark:text-white">{{ $subcontractor->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Last Updated</span>
                    <span class="text-xs font-semibold dark:text-white">{{ $subcontractor->updated_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
