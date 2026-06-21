@extends(auth()->check() ? 'admin.layouts.master' : 'tyro-dashboard::layouts.app')

@section('title', 'Server Error')

@section('content')
<div class="flex min-h-[60vh] items-center justify-center px-4">
    <div class="w-full max-w-lg text-center">
        <svg class="mx-auto mb-6 h-32 w-32 text-danger/30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
            <line x1="12" y1="9" x2="12" y2="13" stroke-width="2" />
            <line x1="12" y1="17" x2="12.01" y2="17" stroke-width="2" />
        </svg>
        <div class="mb-2 font-bold text-danger" style="font-size: clamp(4rem, 10vw, 8rem); line-height: 1;">500</div>
        <h1 class="mb-3 text-2xl font-semibold dark:text-white-light">Server Error</h1>
        <p class="mx-auto mb-6 max-w-md text-white-dark">
            Something went wrong on our end. Our team has been notified. Please try again shortly.
        </p>
        <div class="flex items-center justify-center gap-3">
            <a href="javascript:location.reload()" class="btn btn-outline-secondary gap-2">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="1 4 1 10 7 10" />
                    <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10" />
                </svg>
                Try Again
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
