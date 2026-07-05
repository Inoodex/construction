@extends('errors.layout')

@section('title', 'Access Forbidden')

@section('content')
    <div class="error-page__art order-1 md:order-2">
        <svg viewBox="0 0 420 360" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <rect x="0" y="300" width="420" height="60" fill="#e2e8f0" class="error-scene-ground" />
            <rect x="0" y="300" width="420" height="4" fill="#e2a03f" opacity="0.7" />

            {{-- Site fence --}}
            <g class="error-anim-float">
                <rect x="88" y="188" width="244" height="112" rx="2" fill="none" stroke="#94a3b8" stroke-width="2" stroke-dasharray="8 6" />
                <line x1="88" y1="220" x2="332" y2="220" stroke="#cbd5e1" stroke-width="2" />
                <line x1="88" y1="252" x2="332" y2="252" stroke="#cbd5e1" stroke-width="2" />
                <line x1="88" y1="284" x2="332" y2="284" stroke="#cbd5e1" stroke-width="2" />
                <line x1="130" y1="188" x2="130" y2="300" stroke="#cbd5e1" stroke-width="2" />
                <line x1="172" y1="188" x2="172" y2="300" stroke="#cbd5e1" stroke-width="2" />
                <line x1="214" y1="188" x2="214" y2="300" stroke="#cbd5e1" stroke-width="2" />
                <line x1="256" y1="188" x2="256" y2="300" stroke="#cbd5e1" stroke-width="2" />
                <line x1="298" y1="188" x2="298" y2="300" stroke="#cbd5e1" stroke-width="2" />
            </g>

            {{-- Gate with padlock --}}
            <g class="error-anim-float" style="animation-delay: 0.4s;">
                <rect x="178" y="220" width="64" height="80" rx="2" fill="#f1f5f9" stroke="#64748b" stroke-width="2" />
                <rect x="190" y="236" width="40" height="48" rx="2" fill="#e2e8f0" stroke="#94a3b8" stroke-width="1.5" />
                <path d="M206 248 C206 242 218 242 218 248 L218 258 L206 258 Z" fill="#ef4444" />
                <circle cx="212" cy="264" r="5" fill="#ef4444" />
                <rect x="210" y="264" width="4" height="10" rx="1" fill="#b91c1c" />
            </g>

            {{-- 403 sign --}}
            <g>
                <rect x="152" y="148" width="116" height="32" rx="4" fill="#ef4444" stroke="#b91c1c" stroke-width="2" />
                <text x="210" y="170" text-anchor="middle" fill="#fff" font-family="Nunito, sans-serif" font-size="15" font-weight="800">403</text>
                <rect x="204" y="180" width="12" height="8" fill="#b91c1c" />
            </g>

            {{-- Warning cones --}}
            <g transform="translate(108, 268)">
                <polygon points="12,0 24,36 0,36" fill="#f59e0b" stroke="#d97706" stroke-width="1.5" />
                <rect x="0" y="36" width="24" height="5" rx="1" fill="#1e293b" />
                <rect x="6" y="12" width="12" height="4" fill="#fff" opacity="0.9" />
            </g>
            <g transform="translate(288, 268)">
                <polygon points="12,0 24,36 0,36" fill="#f59e0b" stroke="#d97706" stroke-width="1.5" />
                <rect x="0" y="36" width="24" height="5" rx="1" fill="#1e293b" />
                <rect x="6" y="12" width="12" height="4" fill="#fff" opacity="0.9" />
            </g>

            {{-- Security light --}}
            <circle cx="338" cy="120" r="9" fill="#ef4444" class="error-anim-blink" />
            <rect x="334" y="129" width="8" height="16" fill="#64748b" />
        </svg>
    </div>

    <div class="order-2 md:order-1">
        @include('errors.partials.brand')

        <div class="error-page__code error-page__code--danger">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <rect x="3" y="11" width="18" height="11" rx="2" />
                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
            </svg>
            Error 403
        </div>

        <h1 class="error-page__title">Restricted site — no entry</h1>

        <p class="error-page__text">
            You don't have permission to access this area. If you believe this is a mistake, contact your administrator to request the required role or permission.
        </p>

        <div class="error-page__actions">
            <a href="{{ auth()->check() ? route('tyro-dashboard.index') : route('tyro-login.login') }}" class="error-page__btn error-page__btn--primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                {{ auth()->check() ? 'Back to Dashboard' : 'Go to Login' }}
            </a>
            <button type="button" onclick="history.back()" class="error-page__btn error-page__btn--ghost">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M19 12H5m7-7l-7 7 7 7" />
                </svg>
                Go Back
            </button>
        </div>

        @auth
            @include('errors.partials.quick-links')
        @endauth
    </div>
@endsection
