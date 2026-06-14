@extends(auth()->check() ? 'admin.layouts.master' : 'tyro-dashboard::layouts.app')

@section('title', 'Session Expired')

@section('content')
<div class="flex min-h-[60vh] items-center justify-center">
    <div class="text-center">
        <div class="mb-5 font-bold text-primary" style="font-size: clamp(4rem, 10vw, 10rem); line-height: 1;">419</div>
        <h1 class="mb-3 text-2xl font-semibold dark:text-white-light">Session Expired</h1>
        <p class="mx-auto mb-6 max-w-md text-white-dark">
            Your session has expired due to inactivity. Please log in again to continue.
        </p>
        <a href="{{ route('tyro-login.login') }}"
           class="btn btn-primary inline-flex items-center gap-2">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5m-5 5h12" />
            </svg>
            Go to Login
        </a>
    </div>
</div>
@endsection
