@extends('errors.layout')

@section('title', 'Server Error')

@section('content')
    <div class="error-page__art order-1 md:order-2">
        <svg viewBox="0 0 420 360" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <rect x="0" y="300" width="420" height="60" fill="#e2e8f0" class="error-scene-ground" />
            <rect x="0" y="300" width="420" height="4" fill="#e2a03f" opacity="0.7" />

            {{-- Cracked foundation --}}
            <g class="error-anim-float">
                <rect x="72" y="228" width="276" height="72" rx="2" fill="#cbd5e1" stroke="#94a3b8" stroke-width="2" />
                <path d="M140 228 L128 300 M200 228 L212 300 M280 228 L268 300 M340 228 L352 300" stroke="#64748b" stroke-width="2" />
                <path d="M100 260 L320 260" stroke="#ef4444" stroke-width="2" stroke-dasharray="6 4" />
            </g>

            {{-- Leaning column --}}
            <g class="error-anim-swing" style="transform-origin: 210px 228px;">
                <rect x="192" y="128" width="36" height="100" rx="2" fill="#e2e8f0" stroke="#94a3b8" stroke-width="2" />
                <rect x="188" y="120" width="44" height="12" rx="2" fill="#94a3b8" />
            </g>

            {{-- Warning triangle --}}
            <g transform="translate(168, 88)">
                <polygon points="42,0 84,72 0,72" fill="#f59e0b" stroke="#d97706" stroke-width="2" />
                <text x="42" y="52" text-anchor="middle" fill="#1e293b" font-family="Nunito, sans-serif" font-size="22" font-weight="800">!</text>
            </g>

            {{-- 500 sign --}}
            <g>
                <rect x="156" y="168" width="108" height="28" rx="3" fill="#ef4444" />
                <text x="210" y="188" text-anchor="middle" fill="#fff" font-family="Nunito, sans-serif" font-size="14" font-weight="800">500 ERROR</text>
            </g>

            {{-- Sparks / debris --}}
            <circle cx="128" cy="248" r="3" fill="#ef4444" class="error-anim-blink" />
            <circle cx="292" cy="242" r="2.5" fill="#f59e0b" class="error-anim-blink" style="animation-delay: 0.5s;" />
            <circle cx="248" cy="256" r="2" fill="#94a3b8" class="error-anim-dust" />
            <circle cx="172" cy="252" r="2" fill="#94a3b8" class="error-anim-dust" style="animation-delay: 1s;" />

            {{-- Hard hat on ground --}}
            <g transform="translate(300, 272)">
                <path d="M4 14 C4 6 28 6 28 14 L28 18 L4 18 Z" fill="#f59e0b" />
                <rect x="2" y="16" width="28" height="4" rx="1" fill="#d97706" />
            </g>
        </svg>
    </div>

    <div class="order-2 md:order-1">
        @include('errors.partials.brand')

        <div class="error-page__code error-page__code--danger">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                <line x1="12" y1="9" x2="12" y2="13" />
                <line x1="12" y1="17" x2="12.01" y2="17" />
            </svg>
            Error 500
        </div>

        <h1 class="error-page__title">Structural failure on site</h1>

        <p class="error-page__text">
            Something went wrong on our end. The issue has been logged — please try again in a moment. If the problem persists, contact your system administrator.
        </p>

        <div class="error-page__actions">
            <button type="button" onclick="location.reload()" class="error-page__btn error-page__btn--primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <polyline points="1 4 1 10 7 10" />
                    <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10" />
                </svg>
                Try Again
            </button>
            <a href="{{ auth()->check() ? route('tyro-dashboard.index') : route('tyro-login.login') }}" class="error-page__btn error-page__btn--ghost">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                {{ auth()->check() ? 'Back to Dashboard' : 'Go to Login' }}
            </a>
        </div>

        @auth
            @include('errors.partials.quick-links')
        @endauth
    </div>
@endsection
