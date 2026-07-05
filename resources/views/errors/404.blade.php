@extends('errors.layout')

@section('title', 'Page Not Found')

@section('content')
    {{-- Illustration --}}
    <div class="error-page__art order-1 md:order-2">
        <svg viewBox="0 0 420 360" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            {{-- Ground --}}
            <rect x="0" y="300" width="420" height="60" fill="#e2e8f0" class="error-scene-ground" />
            <rect x="0" y="300" width="420" height="4" fill="#e2a03f" opacity="0.7" />

            {{-- Building frame --}}
            <g class="error-anim-float">
                <rect x="52" y="148" width="96" height="152" rx="2" fill="#e2e8f0" stroke="#94a3b8" stroke-width="2" />
                <rect x="68" y="168" width="24" height="28" fill="#cbd5e1" />
                <rect x="108" y="168" width="24" height="28" fill="#cbd5e1" />
                <rect x="68" y="212" width="24" height="28" fill="#cbd5e1" />
                <rect x="108" y="212" width="24" height="28" fill="#cbd5e1" />
                <rect x="68" y="256" width="64" height="44" fill="#cbd5e1" />
            </g>

            {{-- Crane --}}
            <g class="error-anim-swing">
                <rect x="248" y="248" width="10" height="52" fill="#64748b" />
                <rect x="220" y="72" width="66" height="8" rx="2" fill="#475569" />
                <path d="M254 80 L254 248" stroke="#475569" stroke-width="5" stroke-linecap="round" />
                <path d="M286 80 L286 118" stroke="#64748b" stroke-width="2" />
                <rect x="278" y="118" width="16" height="12" rx="2" fill="#e2a03f" />
                <line x1="286" y1="130" x2="286" y2="150" stroke="#64748b" stroke-width="2" stroke-dasharray="3 3" />
            </g>

            {{-- Road barrier + 404 sign --}}
            <g class="error-anim-float" style="animation-delay: 0.6s;">
                <rect x="168" y="236" width="88" height="10" rx="2" fill="#1e293b" />
                <rect x="176" y="214" width="72" height="22" rx="3" fill="#f59e0b" stroke="#d97706" stroke-width="2" />
                <text x="212" y="230" text-anchor="middle" fill="#1e293b" font-family="Nunito, sans-serif" font-size="16" font-weight="800">404</text>
                <rect x="178" y="246" width="10" height="54" fill="#f8fafc" stroke="#cbd5e1" stroke-width="1.5" />
                <rect x="224" y="246" width="10" height="54" fill="#f8fafc" stroke="#cbd5e1" stroke-width="1.5" />
                <rect x="176" y="252" width="72" height="8" fill="#ef4444" />
                <rect x="176" y="268" width="72" height="8" fill="#f8fafc" />
                <rect x="176" y="284" width="72" height="8" fill="#ef4444" />
            </g>

            {{-- Warning beacon --}}
            <circle cx="318" cy="108" r="10" fill="#ef4444" class="error-anim-blink" />
            <rect x="314" y="118" width="8" height="18" fill="#64748b" />

            {{-- Hard hat worker --}}
            <g transform="translate(320, 252)">
                <circle cx="18" cy="14" r="10" fill="#fcd9b6" />
                <path d="M6 14 C6 6 30 6 30 14 L30 18 L6 18 Z" fill="#f59e0b" />
                <rect x="10" y="24" width="16" height="22" rx="3" fill="#4361ee" />
                <rect x="6" y="46" width="8" height="18" rx="2" fill="#334155" />
                <rect x="22" y="46" width="8" height="18" rx="2" fill="#334155" />
            </g>

            {{-- Blueprint scroll --}}
            <g opacity="0.85">
                <rect x="34" y="248" width="54" height="38" rx="3" fill="#eff6ff" stroke="#4361ee" stroke-width="1.5" />
                <line x1="42" y1="260" x2="80" y2="260" stroke="#93c5fd" stroke-width="2" />
                <line x1="42" y1="270" x2="72" y2="270" stroke="#93c5fd" stroke-width="2" />
                <line x1="42" y1="280" x2="76" y2="280" stroke="#93c5fd" stroke-width="2" />
                <text x="61" y="252" text-anchor="middle" fill="#4361ee" font-family="Nunito, sans-serif" font-size="8" font-weight="700">PLAN</text>
            </g>

            {{-- Dust particles --}}
            <circle cx="150" cy="292" r="2" fill="#94a3b8" class="error-anim-dust" />
            <circle cx="158" cy="296" r="1.5" fill="#94a3b8" class="error-anim-dust" style="animation-delay: 0.8s;" />
            <circle cx="145" cy="298" r="1.5" fill="#94a3b8" class="error-anim-dust" style="animation-delay: 1.4s;" />
        </svg>
    </div>

    {{-- Copy + actions --}}
    <div class="order-2 md:order-1">
        @include('errors.partials.brand')

        <div class="error-page__code">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
            </svg>
            Error 404
        </div>

        <h1 class="error-page__title">This page isn't on the blueprint</h1>

        <p class="error-page__text">
            The URL you followed doesn't match any route in the system. It may have been moved, renamed, or never existed.
        </p>

        @if(request()->path() && request()->path() !== '/')
            <div class="error-page__path" title="Requested path">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                </svg>
                /{{ request()->path() }}
            </div>
        @endif

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
