@extends(auth()->check() ? 'admin.layouts.master' : 'tyro-dashboard::layouts.app')

@section('title', 'Access Forbidden')

@section('content')
<div class="flex min-h-[60vh] items-center justify-center px-4">
    <div class="w-full max-w-lg text-center">
        <svg class="mx-auto mb-6 h-32 w-32 text-danger/30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
            <circle cx="12" cy="16" r="1.5" fill="currentColor" stroke="none" />
            <line x1="12" y1="16" x2="12" y2="13" stroke-width="2" />
        </svg>
        <div class="mb-2 font-bold text-danger" style="font-size: clamp(4rem, 10vw, 8rem); line-height: 1;">403</div>
        <h1 class="mb-3 text-2xl font-semibold dark:text-white-light">Access Forbidden</h1>
        <p class="mx-auto mb-6 max-w-md text-white-dark">
            You don't have permission to access this page. If you believe this is a mistake, please contact your administrator.
        </p>
        <div class="flex items-center justify-center gap-3">
            <a href="javascript:history.back()" class="btn btn-outline-secondary gap-2">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5m7-7l-7 7 7 7" />
                </svg>
                Go Back
            </a>
            <a href="{{ auth()->check() ? route('tyro-dashboard.index') : route('tyro-login.login') }}" class="btn btn-primary gap-2">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Go Home
            </a>
        </div>
    </div>
</div>
@endsection
