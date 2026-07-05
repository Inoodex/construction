<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title') | {{ get_setting('app_name', config('app.name')) }}</title>
    <link rel="icon" type="image/x-icon"
        href="{{ get_setting('app_favicon') ? asset('storage/' . get_setting('app_favicon')) : asset('favicon.ico') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    @stack('styles')
    <style>
        :root {
            --error-primary: #4361ee;
            --error-warning: #e2a03f;
            --error-surface: #f8fafc;
            --error-ink: #0e1726;
            --error-muted: #64748b;
        }

        .dark {
            --error-surface: #0e1726;
            --error-ink: #e0e6ed;
            --error-muted: #888ea8;
        }

        body.error-page {
            font-family: 'Nunito', sans-serif;
            min-height: 100vh;
            margin: 0;
            color: var(--error-ink);
            background: var(--error-surface);
            overflow-x: hidden;
        }

        .error-page__bg {
            position: fixed;
            inset: 0;
            pointer-events: none;
            background:
                radial-gradient(ellipse 80% 50% at 50% -10%, rgba(67, 97, 238, 0.12), transparent),
                radial-gradient(ellipse 60% 40% at 100% 100%, rgba(226, 160, 63, 0.08), transparent);
        }

        .error-page__grid {
            position: fixed;
            inset: 0;
            pointer-events: none;
            opacity: 0.35;
            background-image:
                linear-gradient(rgba(67, 97, 238, 0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(67, 97, 238, 0.06) 1px, transparent 1px);
            background-size: 48px 48px;
            mask-image: radial-gradient(ellipse 70% 70% at 50% 40%, black 20%, transparent 75%);
        }

        .dark .error-page__grid {
            opacity: 0.2;
            background-image:
                linear-gradient(rgba(136, 142, 168, 0.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(136, 142, 168, 0.08) 1px, transparent 1px);
        }

        .error-page__shell {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.25rem;
        }

        .error-page__card {
            width: 100%;
            max-width: 56rem;
            display: grid;
            grid-template-columns: 1fr;
            gap: 2.5rem;
            align-items: center;
        }

        @media (min-width: 768px) {
            .error-page__card {
                grid-template-columns: 1.05fr 0.95fr;
                gap: 3rem;
            }
        }

        .error-page__brand {
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            margin-bottom: 1.5rem;
            text-decoration: none;
            color: inherit;
        }

        .error-page__brand img {
            height: 2rem;
            width: auto;
        }

        .error-page__brand span {
            font-size: 1.125rem;
            font-weight: 700;
        }

        .error-page__code {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            background: rgba(226, 160, 63, 0.15);
            color: #b45309;
        }

        .error-page__code--danger {
            background: rgba(239, 68, 68, 0.12);
            color: #dc2626;
        }

        .error-page__code--primary {
            background: rgba(67, 97, 238, 0.12);
            color: #4361ee;
        }

        .error-page__code--info {
            background: rgba(33, 150, 243, 0.12);
            color: #0284c7;
        }

        .dark .error-page__code--danger { color: #f87171; }
        .dark .error-page__code--primary { color: #8da2fb; }
        .dark .error-page__code--info { color: #38bdf8; }

        .dark .error-page__code {
            background: rgba(226, 160, 63, 0.12);
            color: #fbbf24;
        }

        .error-page__title {
            margin: 0 0 0.75rem;
            font-size: clamp(1.75rem, 4vw, 2.25rem);
            font-weight: 800;
            line-height: 1.2;
        }

        .error-page__text {
            margin: 0 0 1.5rem;
            max-width: 28rem;
            font-size: 1rem;
            line-height: 1.7;
            color: var(--error-muted);
        }

        .error-page__path {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.75rem;
            padding: 0.625rem 0.875rem;
            border-radius: 0.625rem;
            font-size: 0.8125rem;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            word-break: break-all;
            background: rgba(67, 97, 238, 0.06);
            border: 1px solid rgba(67, 97, 238, 0.12);
            color: var(--error-muted);
        }

        .dark .error-page__path {
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.08);
        }

        .error-page__actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .error-page__btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.7rem 1.25rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
        }

        .error-page__btn:hover {
            transform: translateY(-1px);
        }

        .error-page__btn--primary {
            background: var(--error-primary);
            color: #fff;
            box-shadow: 0 10px 25px -10px rgba(67, 97, 238, 0.65);
        }

        .error-page__btn--primary:hover {
            box-shadow: 0 14px 28px -10px rgba(67, 97, 238, 0.75);
        }

        .error-page__btn--ghost {
            background: transparent;
            color: var(--error-ink);
            border: 1px solid rgba(100, 116, 139, 0.25);
            cursor: pointer;
            font-family: inherit;
        }

        .dark .error-page__btn--ghost {
            border-color: rgba(255, 255, 255, 0.12);
        }

        .error-page__btn--ghost:hover {
            background: rgba(67, 97, 238, 0.06);
        }

        .error-page__links {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem 1rem;
        }

        .error-page__links a {
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--error-primary);
            text-decoration: none;
        }

        .error-page__links a:hover {
            text-decoration: underline;
        }

        .error-page__art {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .error-page__art svg {
            width: 100%;
            max-width: 26rem;
            height: auto;
        }

        @keyframes error-float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        @keyframes error-swing {
            0%, 100% { transform: rotate(-2deg); }
            50% { transform: rotate(2deg); }
        }

        @keyframes error-blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.35; }
        }

        @keyframes error-dust {
            0% { transform: translateX(0); opacity: 0.5; }
            100% { transform: translateX(-12px); opacity: 0; }
        }

        .error-anim-float { animation: error-float 4s ease-in-out infinite; }
        .error-anim-swing { transform-origin: 180px 72px; animation: error-swing 5s ease-in-out infinite; }
        .error-anim-blink { animation: error-blink 1.4s ease-in-out infinite; }
        .error-anim-dust { animation: error-dust 2.5s ease-out infinite; }

        .dark .error-scene-ground { fill: #1e293b; }
    </style>
</head>
<body class="error-page">
    <div class="error-page__bg"></div>
    <div class="error-page__grid"></div>

    <div class="error-page__shell">
        <div class="error-page__card">
            @yield('content')
        </div>
    </div>

    <script>
        (function () {
            try {
                const raw = localStorage.getItem('_x_theme');
                const theme = raw ? JSON.parse(raw) : 'light';
                const isDark = theme === 'dark' || (
                    theme === 'system' &&
                    window.matchMedia('(prefers-color-scheme: dark)').matches
                );

                if (isDark) {
                    document.body.classList.add('dark');
                }
            } catch (e) {
                /* ignore */
            }
        })();
    </script>
    @stack('scripts')
</body>
</html>
