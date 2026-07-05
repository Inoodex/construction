@extends('errors.layout')

@section('title', 'Session Expired')

@section('content')
    <div class="error-page__art order-1 md:order-2">
        <svg viewBox="0 0 420 360" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <rect x="0" y="300" width="420" height="60" fill="#e2e8f0" class="error-scene-ground" />
            <rect x="0" y="300" width="420" height="4" fill="#e2a03f" opacity="0.7" />

            {{-- Site office --}}
            <g class="error-anim-float">
                <rect x="118" y="168" width="184" height="132" rx="3" fill="#f8fafc" stroke="#94a3b8" stroke-width="2" />
                <polygon points="118,168 210,118 302,168" fill="#e2e8f0" stroke="#94a3b8" stroke-width="2" />
                <rect x="148" y="208" width="48" height="40" rx="2" fill="#dbeafe" stroke="#93c5fd" stroke-width="1.5" />
                <rect x="224" y="208" width="48" height="40" rx="2" fill="#dbeafe" stroke="#93c5fd" stroke-width="1.5" />
                <rect x="188" y="260" width="44" height="40" rx="2" fill="#cbd5e1" />
            </g>

            {{-- Large clock --}}
            <g class="error-anim-swing" style="transform-origin: 210px 100px;">
                <circle cx="210" cy="100" r="42" fill="#fff" stroke="#4361ee" stroke-width="4" />
                <circle cx="210" cy="100" r="3" fill="#4361ee" />
                <line x1="210" y1="100" x2="210" y2="72" stroke="#4361ee" stroke-width="3" stroke-linecap="round" />
                <line x1="210" y1="100" x2="232" y2="108" stroke="#e2a03f" stroke-width="2.5" stroke-linecap="round" />
                <text x="210" y="152" text-anchor="middle" fill="#4361ee" font-family="Nunito, sans-serif" font-size="11" font-weight="700">419</text>
            </g>

            {{-- ID badge on desk --}}
            <g transform="translate(156, 278)">
                <rect x="0" y="0" width="36" height="22" rx="3" fill="#4361ee" />
                <circle cx="10" cy="11" r="5" fill="#fff" opacity="0.8" />
                <rect x="18" y="7" width="14" height="3" rx="1" fill="#fff" opacity="0.7" />
                <rect x="18" y="13" width="10" height="2" rx="1" fill="#fff" opacity="0.5" />
            </g>

            {{-- Hourglass --}}
            <g transform="translate(280, 248)" class="error-anim-float" style="animation-delay: 0.5s;">
                <path d="M8 0 L24 0 L20 18 L12 18 Z" fill="#e2a03f" opacity="0.85" />
                <path d="M8 36 L24 36 L20 18 L12 18 Z" fill="#e2a03f" opacity="0.55" />
                <rect x="6" y="0" width="20" height="3" rx="1" fill="#64748b" />
                <rect x="6" y="33" width="20" height="3" rx="1" fill="#64748b" />
            </g>
        </svg>
    </div>

    <div class="order-2 md:order-1">
        @include('errors.partials.brand')

        <div class="error-page__code error-page__code--primary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <circle cx="12" cy="12" r="10" />
                <polyline points="12 6 12 12 16 14" />
            </svg>
            Error 419
        </div>

        <h1 class="error-page__title">Your shift clocked out</h1>

        <p class="error-page__text">
            Your session has expired due to inactivity. Log in again to pick up where you left off — your data is still safe.
        </p>

        <div class="error-page__actions">
            <a href="{{ route('tyro-login.login') }}" class="error-page__btn error-page__btn--primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5m-5 5h12" />
                </svg>
                Log In Again
            </a>
            <button type="button" onclick="location.reload()" class="error-page__btn error-page__btn--ghost">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <polyline points="1 4 1 10 7 10" />
                    <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10" />
                </svg>
                Retry
            </button>
        </div>
    </div>
@endsection
