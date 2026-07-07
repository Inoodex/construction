<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title') | {{ get_setting('app_name', config('app.name')) }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/x-icon"
        href="{{ get_setting('app_favicon') ? asset('storage/' . get_setting('app_favicon')) : asset('favicon.ico') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/perfect-scrollbar.min.css') }}" />
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/style.css') }}" />
    <link defer rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/animate.css') }}" />
    @stack('styles')
    <style>
        [x-cloak] {
            display: none !important;
        }
        :root {
            --sidebar-width: 200px;
        }
        .sidebar {
            width: var(--sidebar-width) !important;
        }
        .main-container .main-content {
            margin-left: var(--sidebar-width);
        }
        .toggle-sidebar .main-container .main-content {
            margin-left: 0;
        }
        .vertical .sidebar {
            left: calc(-1 * var(--sidebar-width)) !important;
        }
        @media (min-width: 1024px) {
            .vertical .sidebar {
                left: 0 !important;
            }
            .main-container .main-content:where([dir=ltr], [dir=ltr] *) {
                margin-left: var(--sidebar-width) !important;
            }
            .main-container .main-content:where([dir=rtl], [dir=rtl] *) {
                margin-right: var(--sidebar-width) !important;
            }
            .vertical.toggle-sidebar .sidebar {
                left: calc(-1 * var(--sidebar-width)) !important;
            }
            .collapsible-vertical .sidebar {
                width: 70px !important;
            }
            .collapsible-vertical .sidebar:hover {
                width: var(--sidebar-width) !important;
            }
            .collapsible-vertical.toggle-sidebar .sidebar {
                width: var(--sidebar-width) !important;
            }
            .collapsible-vertical .main-content {
                width: calc(100% - 70px) !important;
                margin-left: 70px !important;
            }
            .collapsible-vertical.toggle-sidebar .main-content {
                width: calc(100% - var(--sidebar-width)) !important;
                margin-left: var(--sidebar-width) !important;
            }
        }
        .horizontal .sidebar {
            left: calc(-1 * var(--sidebar-width)) !important;
            right: auto !important;
        }
        @media (min-width: 1024px) {
            .horizontal.toggle-sidebar .sidebar {
                left: calc(-1 * var(--sidebar-width)) !important;
            }
        }
        .sidebar {
            overflow-x: hidden !important;
        }
        .sidebar .perfect-scrollbar {
            overflow-y: auto !important;
            overflow-x: hidden !important;
        }
        .main-content {
            min-width: 0 !important;
            overflow-x: auto !important;
        }
        .horizontal-menu .sub-sub-menu {
            display: none;
            position: absolute;
            left: 100%;
            top: 0;
            background: #fff;
            border: 1px solid #e0e6ed;
            border-radius: 6px;
            padding: 8px 0;
            min-width: 200px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            z-index: 50;
        }
        .horizontal-menu li.relative:hover > .sub-sub-menu {
            display: block;
        }
        .horizontal-menu .sub-sub-menu li a {
            padding: 6px 16px;
            font-size: 0.8rem;
            font-weight: 500;
            display: block;
            white-space: nowrap;
        }
        .horizontal-menu .sub-sub-menu li a:hover {
            color: #4361ee;
        }
        :is(.dark) .horizontal-menu .sub-sub-menu {
            border-color: #191e3a;
            background: #0e1726;
        }
        :is(.dark) .horizontal-menu .sub-sub-menu li a {
            color: #888ea8;
        }
        :is(.dark) .horizontal-menu .sub-sub-menu li a:hover {
            color: #4361ee;
        }
    </style>
</head>

<body x-data="main" class="relative overflow-x-hidden font-nunito text-sm font-normal antialiased"
    :class="[ $store.app.sidebar ? 'toggle-sidebar' : '', $store.app.theme === 'dark' || $store.app.isDarkMode ?  'dark' : '', $store.app.menu, $store.app.layout,$store.app.rtlClass]">

    @include('admin.layouts.partials.loader')
    @include('admin.layouts.partials.scroll-to-top')

    <div class="main-container min-h-screen text-black dark:text-white-dark" :class="[$store.app.navbar]">
        @include('admin.layouts.partials.customizer')
        @include('admin.layouts.sidebar')

        <div class="main-content flex min-h-screen flex-col">
            @include('admin.layouts.header')

            <div class="animate__animated p-6" :class="[$store.app.animation]">
                @include('admin.layouts.partials.flash-messages')
                @yield('content')
            </div>

            @include('admin.layouts.footer')
        </div>
    </div>

    @include('admin.layouts.scripts')
    @stack('scripts')
</body>

</html>