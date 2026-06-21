@extends(auth()->check() ? 'admin.layouts.master' : 'tyro-dashboard::layouts.app')

@section('title', 'Session Expired')

@section('content')
<div class="flex min-h-[60vh] items-center justify-center px-4">
    <div class="w-full max-w-lg text-center">
        <svg class="mx-auto mb-6 h-32 w-32 text-primary/30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10" />
            <polyline points="12 6 12 12 16 14" />
            <path d="M12 2a10 10 0 0 1 10 10" />
        </svg>
        <div class="mb-2 font-bold text-primary" style="font-size: clamp(4rem, 10vw, 8rem); line-height: 1;">419</div>
        <h1 class="mb-3 text-2xl font-semibold dark:text-white-light">Session Expired</h1>
        <p class="mx-auto mb-6 max-w-md text-white-dark">
            Your session has timed out due to inactivity. Please log in again to continue where you left off.
        </p>
        <a href="{{ route('tyro-login.login') }}" class="btn btn-primary gap-2">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5m-5 5h12" />
            </svg>
            Log In Again
        </a>
    </div>
</div>
@endsection
