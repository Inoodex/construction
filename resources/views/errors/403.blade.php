@extends(auth()->check() ? 'admin.layouts.master' : 'tyro-dashboard::layouts.app')

@section('title', 'Forbidden')

@section('content')
<div class="flex min-h-[60vh] items-center justify-center">
    <div class="text-center">
        <div class="mb-5 font-bold text-danger" style="font-size: clamp(4rem, 10vw, 10rem); line-height: 1;">403</div>
        <h1 class="mb-3 text-2xl font-semibold dark:text-white-light">Access Forbidden</h1>
        <p class="mx-auto mb-6 max-w-md text-white-dark">
            You do not have permission to access this page. Please contact your administrator.
        </p>
        <a href="{{ auth()->check() ? route('tyro-dashboard.index') : route('tyro-login.login') }}"
           class="btn btn-primary inline-flex items-center gap-2">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Go Home
        </a>
    </div>
</div>
@endsection
