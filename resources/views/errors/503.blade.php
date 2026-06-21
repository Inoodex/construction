@extends(auth()->check() ? 'admin.layouts.master' : 'tyro-dashboard::layouts.app')

@section('title', 'Under Maintenance')

@section('content')
<div class="flex min-h-[60vh] items-center justify-center px-4">
    <div class="w-full max-w-lg text-center">
        <svg class="mx-auto mb-6 h-32 w-32 text-info/30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" />
        </svg>
        <div class="mb-2 font-bold text-info" style="font-size: clamp(4rem, 10vw, 8rem); line-height: 1;">503</div>
        <h1 class="mb-3 text-2xl font-semibold dark:text-white-light">Under Maintenance</h1>
        <p class="mx-auto mb-6 max-w-md text-white-dark">
            We're performing scheduled maintenance to improve your experience. We'll be back shortly.
        </p>
        <a href="javascript:location.reload()" class="btn btn-primary gap-2">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="1 4 1 10 7 10" />
                <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10" />
            </svg>
            Refresh
        </a>
    </div>
</div>
@endsection
