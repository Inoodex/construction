@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('content')
    <!-- Breadcrumb -->
    @if(auth()->user()->hasRole('super-admin'))
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('tyro-dashboard.index') }}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <!-- <li class="before:content-['/'] ltr:before:mr-2 rtl:before:ml-2">
                                                                                        <span>Sales</span>
                                                                                    </li> -->
        </ul>

        <div class="pt-5">
            <!-- Stats Grid -->
            <div class="mb-6 grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
                <div class="panel h-full sm:col-span-2 xl:col-span-1">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <div class="text-success">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                        stroke="currentColor" stroke-width="1.5" />
                                </svg>
                            </div>
                        </div>
                        <div class="w-full ltr:ml-3 rtl:mr-3">
                            <p class="text-xl dark:text-white-light">{{ number_format($stats['total_roles'] ?? 0) }}</p>
                            <h5 class="text-xs font-semibold text-[#506690]">Total Roles</h5>
                        </div>
                    </div>
                </div>

                <div class="panel h-full sm:col-span-2 xl:col-span-1">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <div class="text-info">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"
                                        stroke="currentColor" stroke-width="1.5" />
                                </svg>
                            </div>
                        </div>
                        <div class="w-full ltr:ml-3 rtl:mr-3">
                            <p class="text-xl dark:text-white-light">{{ number_format($stats['total_privileges'] ?? 0) }}</p>
                            <h5 class="text-xs font-semibold text-[#506690]">Total Privileges</h5>
                        </div>
                    </div>
                </div>

                <div class="panel h-full sm:col-span-2 xl:col-span-1">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <div class="text-warning">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                                    <ellipse opacity="0.5" cx="12" cy="17" rx="7" ry="4" stroke="currentColor"
                                        stroke-width="1.5" />
                                </svg>
                            </div>
                        </div>
                        <div class="w-full ltr:ml-3 rtl:mr-3">
                            <p class="text-xl dark:text-white-light">{{ number_format($stats['total_users'] ?? 0) }}</p>
                            <h5 class="text-xs font-semibold text-[#506690]">Total Users</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    </div>
@endsection