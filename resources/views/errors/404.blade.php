@extends(auth()->check() ? 'admin.layouts.master' : 'tyro-dashboard::layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="flex min-h-[60vh] items-center justify-center px-4">
    <div class="w-full max-w-lg text-center">
        <svg class="mx-auto mb-6 h-32 w-32 text-warning/30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8" />
            <path d="M21 21l-4.35-4.35" />
            <line x1="8" y1="11" x2="14" y2="11" stroke-width="2" />
            <line x1="11" y1="8" x2="11" y2="14" stroke-width="2" />
        </svg>
        <div class="mb-2 font-bold text-warning" style="font-size: clamp(4rem, 10vw, 8rem); line-height: 1;">404</div>
        <h1 class="mb-3 text-2xl font-semibold dark:text-white-light">Page Not Found</h1>
        <p class="mx-auto mb-6 max-w-md text-white-dark">
            The page you're looking for doesn't exist or has been moved. Check the URL or navigate back.
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
