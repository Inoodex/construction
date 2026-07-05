@extends('errors.layout')

@section('title', 'Under Maintenance')

@section('content')
    <div class="error-page__art order-1 md:order-2">
        <svg viewBox="0 0 420 360" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <rect x="0" y="300" width="420" height="60" fill="#e2e8f0" class="error-scene-ground" />
            <rect x="0" y="300" width="420" height="4" fill="#e2a03f" opacity="0.7" />

            {{-- Site entrance arch --}}
            <g class="error-anim-float">
                <rect x="108" y="200" width="16" height="100" fill="#94a3b8" />
                <rect x="296" y="200" width="16" height="100" fill="#94a3b8" />
                <rect x="108" y="188" width="204" height="16" rx="2" fill="#64748b" />
                <text x="210" y="200" text-anchor="middle" fill="#f8fafc" font-family="Nunito, sans-serif" font-size="9" font-weight="700">CONSTRUCTION SITE</text>
            </g>

            {{-- Closed barrier --}}
            <g transform="translate(148, 232)" class="error-anim-swing" style="transform-origin: 0px 40px;">
                <rect x="0" y="36" width="124" height="8" rx="2" fill="#1e293b" />
                <rect x="0" y="0" width="124" height="36" rx="3" fill="#f59e0b" stroke="#d97706" stroke-width="2" />
                <text x="62" y="24" text-anchor="middle" fill="#1e293b" font-family="Nunito, sans-serif" font-size="13" font-weight="800">CLOSED</text>
                <rect x="58" y="44" width="8" height="56" fill="#cbd5e1" stroke="#94a3b8" stroke-width="1.5" />
            </g>

            {{-- Traffic cones --}}
            <g transform="translate(88, 264)">
                <polygon points="10,0 20,30 0,30" fill="#f59e0b" stroke="#d97706" stroke-width="1.5" />
                <rect x="0" y="30" width="20" height="4" rx="1" fill="#1e293b" />
            </g>
            <g transform="translate(312, 264)">
                <polygon points="10,0 20,30 0,30" fill="#f59e0b" stroke="#d97706" stroke-width="1.5" />
                <rect x="0" y="30" width="20" height="4" rx="1" fill="#1e293b" />
            </g>

            {{-- Wrench & gear (maintenance) --}}
            <g transform="translate(318, 108)" class="error-anim-float" style="animation-delay: 0.3s;">
                <circle cx="20" cy="20" r="18" fill="none" stroke="#4361ee" stroke-width="4" stroke-dasharray="8 6" />
                <circle cx="20" cy="20" r="8" fill="#4361ee" opacity="0.2" />
                <path d="M20 4 L22 10 L28 8 L24 14 L30 16 L24 18 L26 24 L20 20 L14 24 L16 18 L10 16 L16 14 L12 8 L18 10 Z" fill="#4361ee" opacity="0.5" transform="scale(0.55) translate(16,16)" />
            </g>

            {{-- 503 badge --}}
            <g>
                <rect x="168" y="128" width="84" height="28" rx="14" fill="#0284c7" />
                <text x="210" y="147" text-anchor="middle" fill="#fff" font-family="Nunito, sans-serif" font-size="13" font-weight="800">503</text>
            </g>

            {{-- Dust --}}
            <circle cx="180" cy="292" r="2" fill="#94a3b8" class="error-anim-dust" />
            <circle cx="240" cy="296" r="1.5" fill="#94a3b8" class="error-anim-dust" style="animation-delay: 0.7s;" />
        </svg>
    </div>

    <div class="order-2 md:order-1">
        @include('errors.partials.brand')

        <div class="error-page__code error-page__code--info">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" />
            </svg>
            Error 503
        </div>

        <h1 class="error-page__title">Site closed for maintenance</h1>

        <p class="error-page__text">
            We're performing scheduled upgrades to improve your experience. The site will be back online shortly — thank you for your patience.
        </p>

        <div class="error-page__actions">
            <button type="button" onclick="location.reload()" class="error-page__btn error-page__btn--primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <polyline points="1 4 1 10 7 10" />
                    <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10" />
                </svg>
                Check Again
            </button>
            <a href="{{ auth()->check() ? route('tyro-dashboard.index') : route('tyro-login.login') }}" class="error-page__btn error-page__btn--ghost">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                {{ auth()->check() ? 'Back to Dashboard' : 'Go to Login' }}
            </a>
        </div>
    </div>
@endsection
