<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NawaTara InternTrack')</title>
    <script>
        try {
            if (localStorage.getItem('nawantara-sidebar-collapsed') === 'true') {
                document.documentElement.classList.add('sidebar-collapsed');
            }
        } catch (error) {
            // Sidebar remains usable when browser storage is unavailable.
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        :root {
            --sidebar-expanded-width: 16rem;
            --sidebar-collapsed-width: 5rem;
        }
        body {
            font-family: 'Inter', sans-serif;
        }
        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(148, 163, 184, 0.35) transparent;
        }
        *::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }
        *::-webkit-scrollbar-track {
            background: transparent;
        }
        *::-webkit-scrollbar-thumb {
            border: 2px solid transparent;
            border-radius: 9999px;
            background-color: rgba(148, 163, 184, 0.3);
            background-clip: padding-box;
            transition: background-color 180ms ease;
        }
        *::-webkit-scrollbar-thumb:hover {
            background-color: rgba(148, 163, 184, 0.58);
        }
        *::-webkit-scrollbar-button,
        *::-webkit-scrollbar-button:single-button {
            display: none;
            width: 0;
            height: 0;
        }
        .bg-navy-dark {
            background-color: #0B132B;
        }
        .bg-navy-darker {
            background-color: #07101F;
        }
        .bg-main-content {
            background-color: #ffffff;
        }
        .text-blue {
            color: #2563EB;
        }
        .bg-blue {
            background-color: #2563EB;
        }
        .border-blue {
            border-color: #2563EB;
        }
        .hover-blue:hover {
            background-color: #1D4ED8;
        }
        [data-app-validation-invalid="true"] {
            border-color: #fb7185 !important;
            box-shadow: 0 0 0 3px rgba(244, 63, 94, 0.18) !important;
        }
        .app-validation-message {
            display: flex;
            align-items: flex-start;
            gap: 0.375rem;
            margin-top: 0.5rem;
            color: #e11d48;
            font-size: 0.75rem;
            font-weight: 500;
            line-height: 1.25rem;
        }
        .app-validation-message svg {
            width: 1rem;
            height: 1rem;
            flex: 0 0 1rem;
            margin-top: 0.125rem;
        }
        .sidebar-link {
            position: relative;
            isolation: isolate;
            cursor: pointer;
            transition:
                color 220ms ease,
                background-color 220ms ease,
                transform 220ms ease;
        }
        .sidebar-link::before,
        .sidebar-action::before {
            position: absolute;
            top: 50%;
            left: 0;
            width: 3px;
            height: 0;
            border-radius: 0 9999px 9999px 0;
            background-color: #2563EB;
            content: "";
            opacity: 0;
            transform: translateY(-50%);
            transition: height 220ms ease, opacity 220ms ease;
        }
        .sidebar-link.active {
            background-color: rgba(30, 58, 88, 0.48);
            color: #93c5fd;
            box-shadow: inset 0 0 0 1px rgba(59, 130, 246, 0.2);
        }
        .sidebar-link.active::before {
            height: 62%;
            opacity: 1;
        }
        .sidebar-link i,
        .sidebar-action svg {
            transition: color 220ms ease, transform 220ms ease;
        }
        #app-sidebar .sidebar-link > i {
            flex: 0 0 1.25rem;
            color: #cbd5e1;
            font-size: 0.95rem;
            text-align: center;
        }
        #app-sidebar .sidebar-link.active > i {
            color: #60a5fa;
        }
        .sidebar-action {
            position: relative;
            isolation: isolate;
            border-radius: 0.5rem;
            padding: 0.5rem 0.625rem;
            cursor: pointer;
            transition: color 180ms ease, background-color 180ms ease;
        }
        @media (hover: hover) and (pointer: fine) {
            .sidebar-link:hover {
                background-color: rgba(30, 58, 88, 0.72);
                color: #ffffff;
                transform: translateX(3px);
            }
            .sidebar-link:hover::before,
            .sidebar-link:focus-visible::before {
                height: 62%;
                opacity: 1;
            }
            .sidebar-action:hover {
                background-color: rgba(255, 255, 255, 0.05);
                color: #e2e8f0;
            }
            .sidebar-link.active:hover {
                background-color: rgba(30, 58, 88, 0.62);
                color: #93c5fd;
            }
            .sidebar-link:hover i {
                color: #60a5fa;
                transform: translateX(1px);
            }
            .sidebar-action:hover svg {
                color: #cbd5e1;
            }
            .sidebar-link.active:hover i {
                color: #93c5fd;
            }
        }
        .sidebar-link:focus-visible,
        .sidebar-action:focus-visible {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }
        .sidebar-link.active:focus-visible {
            background-color: rgba(30, 58, 88, 0.62);
            color: #93c5fd;
        }
        .sidebar-link.active:focus-visible::before,
        .sidebar-link:focus-visible::before {
            height: 62%;
            opacity: 1;
        }
        .sidebar-action:focus-visible {
            background-color: rgba(255, 255, 255, 0.05);
            color: #e2e8f0;
        }
        #logout-trigger svg {
            transition: transform 180ms ease;
        }
        #logout-trigger:hover svg {
            transform: translateX(1px);
        }
        #sidebar-toggle,
        #sidebar-toggle * {
            border: 0;
            outline: 0;
            box-shadow: none;
            text-decoration: none;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        #sidebar-toggle:hover,
        #sidebar-toggle:focus,
        #sidebar-toggle:focus-visible,
        #sidebar-toggle:active {
            border: 0;
            outline: 0;
            box-shadow: none;
            text-decoration: none;
        }
        #sidebar-toggle {
            appearance: none;
            -webkit-tap-highlight-color: transparent;
        }
        #app-sidebar {
            width: var(--sidebar-expanded-width);
            transition: width 300ms cubic-bezier(0.22, 1, 0.36, 1);
        }
        #main-content {
            margin-left: var(--sidebar-expanded-width);
            min-width: 0;
            max-width: 100%;
        }
        .sidebar-label,
        .sidebar-user-details {
            max-width: 14rem;
            overflow: hidden;
            opacity: 1;
            visibility: visible;
            transition:
                max-width 300ms cubic-bezier(0.22, 1, 0.36, 1),
                opacity 220ms ease,
                margin 300ms ease,
                visibility 0s linear;
        }
        html.sidebar-collapsed #app-sidebar {
            width: var(--sidebar-collapsed-width);
        }
        html.sidebar-collapsed #main-content {
            margin-left: var(--sidebar-collapsed-width);
        }
        html.sidebar-collapsed .sidebar-label,
        html.sidebar-collapsed .sidebar-user-details {
            max-width: 0;
            margin-left: 0;
            opacity: 0;
            visibility: hidden;
            transition:
                max-width 300ms cubic-bezier(0.22, 1, 0.36, 1),
                opacity 180ms ease,
                margin 300ms ease,
                visibility 0s linear 180ms;
        }
        html:not(.sidebar-collapsed) .sidebar-label,
        html:not(.sidebar-collapsed) .sidebar-user-details {
            transition:
                max-width 300ms cubic-bezier(0.22, 1, 0.36, 1),
                opacity 220ms ease 80ms,
                margin 300ms ease,
                visibility 0s linear;
        }
        html.sidebar-collapsed #sidebar-toggle {
            justify-content: center;
            padding: 0.5rem;
        }
        html.sidebar-collapsed #sidebar-brand {
            flex: 0 0 auto;
        }
        html.sidebar-collapsed #sidebar-brand-mark {
            margin-right: 0;
            height: 2rem;
            width: 2rem;
        }
        html.sidebar-collapsed #sidebar-navigation {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        html.sidebar-collapsed .sidebar-link {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }
        html.sidebar-collapsed .sidebar-link i {
            margin-left: 0;
        }
        html.sidebar-collapsed #sidebar-user-row {
            justify-content: center;
            flex-direction: column;
            gap: 0.75rem;
        }
        html.sidebar-collapsed #logout-trigger {
            padding: 0.5rem 0;
        }
        html.sidebar-collapsed #logout-trigger svg {
            margin-right: 0;
        }
        #main-content .card {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }
        #main-content .card[href],
        #main-content a.card,
        #main-content button.card,
        #main-content [role="link"].card {
            cursor: pointer;
            transition:
                transform 220ms ease,
                box-shadow 220ms ease,
                border-color 220ms ease,
                background-color 220ms ease;
        }
        #main-content .table-row {
            transition: background-color 180ms ease;
        }
        #main-content {
            color: #111827;
            background-color: #ffffff;
        }
        #main-content header {
            background-color: #ffffff;
            border-color: #e5e7eb;
        }
        #main-content .text-white {
            color: #111827 !important;
        }
        #main-content .text-gray-200 {
            color: #374151 !important;
        }
        #main-content .text-gray-300 {
            color: #4b5563 !important;
        }
        #main-content .text-gray-400 {
            color: #6b7280 !important;
        }
        #main-content .text-gray-500 {
            color: #6b7280 !important;
        }
        #main-content .text-blue {
            color: #1D4ED8 !important;
        }
        #main-content .text-yellow-400,
        #main-content .text-yellow-500 {
            color: #a16207 !important;
        }
        #main-content .text-green-400 {
            color: #15803d !important;
        }
        #main-content .text-red-400 {
            color: #b91c1c !important;
        }
        #main-content .text-blue-400 {
            color: #1d4ed8 !important;
        }
        #main-content button.text-white,
        #main-content button .text-white,
        #main-content .bg-blue.text-white,
        #main-content .bg-blue .text-white {
            color: #ffffff !important;
        }
        #main-content [class*="bg-[#"] {
            background-color: #f3f4f6 !important;
        }
        #main-content [class~="border-gray-700"],
        #main-content [class~="border-gray-800"],
        #main-content [class~="divide-gray-800"] {
            border-color: #e5e7eb;
        }
        #main-content input,
        #main-content select,
        #main-content textarea {
            color: #111827;
            background-color: #f9fafb;
            border-color: #d1d5db;
            color-scheme: light;
        }
        #main-content input::placeholder,
        #main-content textarea::placeholder {
            color: #6b7280;
        }
        #main-content a,
        #main-content button,
        #main-content select {
            -webkit-tap-highlight-color: transparent;
        }
        #main-content a,
        #main-content button:not(:disabled),
        #main-content select {
            cursor: pointer;
        }
        #main-content a:focus-visible,
        #main-content button:focus-visible,
        #main-content select:focus-visible,
        #main-content input:focus-visible,
        #main-content textarea:focus-visible {
            outline: 2px solid #0891b2;
            outline-offset: 2px;
        }
        #main-content button[class*="bg-"]:not(:disabled) {
            cursor: pointer;
            transition:
                transform 180ms ease,
                box-shadow 180ms ease,
                filter 180ms ease;
        }
        #main-content button[class*="bg-"]:not(:disabled) i,
        #main-content button[class*="bg-"]:not(:disabled) svg,
        #main-content a.card i,
        #main-content a.card svg,
        #main-content button.card i,
        #main-content button.card svg {
            transition: transform 200ms ease, color 200ms ease;
        }
        #main-content select {
            cursor: pointer;
            transition: border-color 180ms ease, box-shadow 180ms ease;
        }
        #main-content select:hover {
            border-color: #94a3b8;
        }
        @media (hover: hover) and (pointer: fine) {
            #main-content .table-row:hover {
                background-color: #f9fafb;
            }
            #main-content [class*="hover:bg-[#111c3a]"]:hover {
                background-color: #f3f4f6 !important;
            }
            #main-content .card[href]:hover,
            #main-content a.card:hover,
            #main-content button.card:hover,
            #main-content [role="link"].card:hover {
                transform: translateY(-2px);
                border-color: rgba(8, 145, 178, 0.48);
                box-shadow: 0 10px 24px rgba(15, 23, 42, 0.1);
            }
            #main-content a.card:hover i,
            #main-content a.card:hover svg,
            #main-content button.card:hover i,
            #main-content button.card:hover svg {
                transform: translateY(-1px) scale(1.04);
            }
            #main-content button[class*="bg-"]:not(:disabled):hover {
                transform: translateY(-1px);
                box-shadow: 0 5px 12px rgba(15, 23, 42, 0.12);
                filter: brightness(0.97);
            }
            #main-content button[class*="bg-"]:not(:disabled):hover i,
            #main-content button[class*="bg-"]:not(:disabled):hover svg {
                transform: translateX(1px);
            }
            #main-content a:not(.card):hover i,
            #main-content a:not(.card):hover svg {
                transform: translateX(1px);
            }
        }
        #main-content .card[href]:focus-visible,
        #main-content a.card:focus-visible,
        #main-content button.card:focus-visible,
        #main-content [role="link"].card:focus-visible {
            outline: 2px solid #0891b2;
            outline-offset: 3px;
        }
        #main-content .attendance-intro-panel {
            display: flex;
            min-height: 100%;
            flex-direction: column;
            justify-content: center;
            border: 1px solid #e5e7eb;
            border-radius: 0.875rem;
            background-color: #ffffff;
            padding: 1.5rem;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }
        #main-content .attendance-intro-panel .text-white {
            color: #111827 !important;
        }
        #main-content .attendance-intro-panel .text-gray-400 {
            color: #4b5563 !important;
        }
        #main-content .attendance-intro-panel .text-blue {
            color: #1D4ED8 !important;
        }
        #main-content .attendance-form-panel {
            border-color: #e5e7eb;
            background-color: #ffffff;
        }
        #main-content .attendance-clock-panel {
            border-color: #e5e7eb;
            background-color: #f9fafb;
        }
        @media (prefers-reduced-motion: reduce) {
            #app-sidebar,
            #main-content,
            .sidebar-label,
            .sidebar-user-details,
            .sidebar-link,
            .sidebar-link::before,
            .sidebar-link i,
            .sidebar-action,
            .sidebar-action::before,
            .sidebar-action svg,
            #main-content .card[href],
            #main-content a.card,
            #main-content button.card,
            #main-content [role="link"].card,
            #main-content .intern-attendance-stat,
            #main-content .intern-stat-card,
            #main-content .weekly-activity-card,
            #main-content .table-row,
            #main-content button[class*="bg-"],
            #main-content button[class*="bg-"] i,
            #main-content button[class*="bg-"] svg,
            #main-content a.card i,
            #main-content a.card svg,
            #main-content button.card i,
            #main-content button.card svg,
            #main-content select {
                transition: none;
            }
            #main-content .card[href]:hover,
            #main-content a.card:hover,
            #main-content button.card:hover,
            #main-content [role="link"].card:hover,
            #main-content .intern-attendance-stat:hover,
            #main-content .intern-stat-card:hover,
            #main-content .weekly-activity-card:hover,
            #main-content button[class*="bg-"]:not(:disabled):hover,
            #main-content button[class*="bg-"]:not(:disabled):hover i,
            #main-content button[class*="bg-"]:not(:disabled):hover svg,
            #main-content a.card:hover i,
            #main-content a.card:hover svg,
            #main-content button.card:hover i,
            #main-content button.card:hover svg {
                transform: none;
            }
        }
    </style>
</head>
<body class="min-h-screen overflow-x-hidden bg-navy-dark">
    <div class="flex min-h-screen w-full max-w-full overflow-x-hidden">
        <!-- Sidebar -->
        <aside id="app-sidebar" class="overflow-x-hidden bg-navy-darker border-r border-gray-800 flex flex-col fixed h-full z-30">
            <!-- Logo -->
            <button id="sidebar-toggle" type="button" aria-label="Ciutkan sidebar" aria-expanded="true" title="Ciutkan sidebar" class="flex w-full items-center justify-between border-0 bg-transparent p-5 text-left shadow-none outline-none ring-0">
                <div id="sidebar-brand" class="flex min-w-0 items-center">
                    <span id="sidebar-brand-mark" class="mr-3 flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-500/15 text-sm font-bold text-blue">NT</span>
                    <span class="sidebar-label min-w-0">
                        <span class="block whitespace-nowrap text-lg font-bold text-white">NawaTara</span>
                        <span class="block whitespace-nowrap text-xs font-light text-blue">InternTrack</span>
                    </span>
                </div>
            </button>

            <!-- Navigation -->
            <nav id="sidebar-navigation" class="flex-1 p-4 space-y-2 overflow-y-auto">
                @if (auth()->user()->isSupervisor())
                    <a href="{{ route('supervisor.dashboard') }}" title="Dashboard" class="sidebar-link flex items-center px-4 py-3 text-gray-300 rounded-lg {{ request()->routeIs('supervisor.dashboard') ? 'active text-blue' : '' }}">
                        <i class="fas fa-gauge-high w-5"></i>
                        <span class="sidebar-label ml-3 whitespace-nowrap">Dashboard</span>
                    </a>
                    <a href="{{ route('supervisor.tasks') }}" title="Task Management" class="sidebar-link flex items-center px-4 py-3 text-gray-300 rounded-lg {{ request()->routeIs('supervisor.tasks*') ? 'active text-blue' : '' }}">
                        <i class="fas fa-list-check w-5"></i>
                        <span class="sidebar-label ml-3 whitespace-nowrap">Task Management</span>
                    </a>
                    <a href="{{ route('supervisor.interns') }}" title="Data Intern" class="sidebar-link flex items-center px-4 py-3 text-gray-300 rounded-lg {{ request()->routeIs('supervisor.interns*') ? 'active text-blue' : '' }}">
                        <i class="fas fa-users w-5"></i>
                        <span class="sidebar-label ml-3 whitespace-nowrap">Data Intern</span>
                    </a>
                    <a href="{{ route('supervisor.attendance') }}" title="Presensi Intern" class="sidebar-link flex items-center px-4 py-3 text-gray-300 rounded-lg {{ request()->routeIs('supervisor.attendance*') ? 'active text-blue' : '' }}">
                        <i class="fas fa-calendar-check w-5"></i>
                        <span class="sidebar-label ml-3 whitespace-nowrap">Presensi Intern</span>
                    </a>
                    <a href="{{ route('supervisor.reviews') }}" title="Review Pekerjaan" class="sidebar-link flex items-center px-4 py-3 text-gray-300 rounded-lg {{ request()->routeIs('supervisor.reviews*') ? 'active text-blue' : '' }}">
                        <i class="fas fa-clipboard-check w-5"></i>
                        <span class="sidebar-label ml-3 whitespace-nowrap">Review Pekerjaan</span>
                    </a>
                    <a href="{{ route('supervisor.performance') }}" title="Performa Intern" class="sidebar-link flex items-center px-4 py-3 text-gray-300 rounded-lg {{ request()->routeIs('supervisor.performance*') ? 'active text-blue' : '' }}">
                        <i class="fas fa-chart-column w-5"></i>
                        <span class="sidebar-label ml-3 whitespace-nowrap">Performa Intern</span>
                    </a>
                @else
                    <a href="{{ route('intern.dashboard') }}" title="Dashboard" class="sidebar-link flex items-center px-4 py-3 text-gray-300 rounded-lg {{ request()->routeIs('intern.dashboard') ? 'active text-blue' : '' }}">
                        <i class="fas fa-gauge-high w-5"></i>
                        <span class="sidebar-label ml-3 whitespace-nowrap">Dashboard</span>
                    </a>
                    <a href="{{ route('intern.tasks') }}" title="Task Saya" class="sidebar-link flex items-center px-4 py-3 text-gray-300 rounded-lg {{ request()->routeIs('intern.tasks*') ? 'active text-blue' : '' }}">
                        <i class="fas fa-clipboard-check w-5"></i>
                        <span class="sidebar-label ml-3 whitespace-nowrap">Task Saya</span>
                    </a>
                    <a href="{{ route('intern.progress') }}" title="Progress Tugas" class="sidebar-link flex items-center px-4 py-3 text-gray-300 rounded-lg {{ request()->routeIs('intern.progress*') ? 'active text-blue' : '' }}">
                        <i class="fas fa-chart-line w-5"></i>
                        <span class="sidebar-label ml-3 whitespace-nowrap">Progress Tugas</span>
                    </a>
                    <a href="{{ route('intern.attendance') }}" title="Presensi" class="sidebar-link flex items-center px-4 py-3 text-gray-300 rounded-lg {{ request()->routeIs('intern.attendance*') ? 'active text-blue' : '' }}">
                        <i class="fas fa-calendar-check w-5"></i>
                        <span class="sidebar-label ml-3 whitespace-nowrap">Presensi</span>
                    </a>
                @endif
            </nav>

            <!-- User Info -->
            <div class="border-t border-gray-800 p-4">
                <div id="sidebar-user-row" class="flex items-center">
                    <div class="w-10 h-10 bg-blue rounded-full flex items-center justify-center text-white font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="sidebar-user-details ml-3 flex-1">
                        <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400">{{ auth()->user()->role }}</p>
                    </div>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button id="logout-trigger" type="button" aria-label="Log Out" title="Log Out" aria-haspopup="dialog" aria-controls="logout-modal" class="sidebar-action flex items-center gap-2 text-sm text-gray-400">
                            <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M10 17l5-5-5-5"></path>
                                <path d="M15 12H3"></path>
                                <path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"></path>
                            </svg>
                            <span class="sidebar-label">Log Out</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main id="main-content" class="bg-main-content min-w-0 max-w-full flex-1 transition-all duration-300 ease-in-out">
            <!-- Header -->
            <header class="bg-navy-darker border-b border-gray-800 px-8 py-4 sticky top-0 z-20">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-semibold text-white">@yield('header')</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-400 text-sm">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</span>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="p-8">
                @yield('content')
            </div>
        </main>
    </div>

    <div id="logout-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 p-4" role="dialog" aria-modal="true" aria-labelledby="logout-modal-title" aria-describedby="logout-modal-description" aria-hidden="true">
        <div class="w-full max-w-md rounded-xl border border-gray-700 bg-[#0F1829] p-6 shadow-2xl">
            <div class="mb-5 flex items-start gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-red-500/15 text-red-400">
                    <i class="fas fa-right-from-bracket" aria-hidden="true"></i>
                </div>
                <div>
                    <h2 id="logout-modal-title" class="text-lg font-semibold text-white">Konfirmasi Keluar</h2>
                    <p id="logout-modal-description" class="mt-1 text-sm leading-6 text-gray-300">Apakah Anda yakin ingin keluar dari aplikasi?</p>
                </div>
            </div>
            <div class="flex justify-end gap-3">
                <button id="logout-cancel" type="button" class="rounded-lg border border-gray-600 px-4 py-2 text-sm font-medium text-gray-200 transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400">
                    Batal
                </button>
                <button type="submit" form="logout-form" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-400">
                    Ya, Keluar
                </button>
            </div>
        </div>
    </div>

    <script>
        const validationControlSelector = 'input[required], select[required], textarea[required], input[aria-required="true"], select[aria-required="true"], textarea[aria-required="true"]';
        const validationId = (control) => control.id || `required-field-${Math.random().toString(36).slice(2)}`;

        function getRequiredFieldLabel(control) {
            if (control.labels && control.labels.length) {
                return control.labels[0].textContent.trim().replace(/\s+/g, ' ');
            }

            let ancestor = control.parentElement;
            while (ancestor && ancestor !== control.form) {
                const label = ancestor.querySelector(':scope > label');
                if (label) return label.textContent.trim().replace(/\s+/g, ' ');
                ancestor = ancestor.parentElement;
            }

            return control.name
                ? control.name.replace(/[_\[\]-]+/g, ' ').replace(/\s+/g, ' ').trim()
                : 'Field ini';
        }

        function getRequiredFieldError(control) {
            const validity = control.validity;
            const label = getRequiredFieldLabel(control);

            if (validity.valueMissing) {
                if (control.type === 'checkbox') return `${label} wajib dicentang.`;
                if (control.type === 'radio') return `${label} wajib dipilih.`;
                if (control.tagName === 'SELECT') return `${label} wajib dipilih.`;
                return `${label} wajib diisi.`;
            }
            if (validity.customError) return control.validationMessage || `${label} belum valid.`;
            if (validity.typeMismatch) return `${label} memiliki format yang tidak valid.`;
            if (validity.patternMismatch) return `Format ${label} belum sesuai.`;
            if (validity.rangeUnderflow) return `${label} berada di bawah batas minimum.`;
            if (validity.rangeOverflow) return `${label} melewati batas maksimum.`;
            if (validity.tooShort) return `${label} terlalu pendek.`;
            if (validity.tooLong) return `${label} terlalu panjang.`;
            if (validity.stepMismatch) return `${label} tidak sesuai dengan interval yang ditentukan.`;

            return '';
        }

        function clearRequiredFieldError(control) {
            const message = control.parentElement
                ? Array.from(control.parentElement.querySelectorAll('[data-app-validation-message]'))
                    .find((item) => item.dataset.appValidationMessage === control.id)
                : null;
            control.removeAttribute('data-app-validation-invalid');
            control.removeAttribute('aria-invalid');
            if (message) message.remove();

            const describedBy = (control.getAttribute('aria-describedby') || '')
                .split(/\s+/)
                .filter((id) => id && (!message || id !== message.id));
            if (describedBy.length) {
                control.setAttribute('aria-describedby', describedBy.join(' '));
            } else {
                control.removeAttribute('aria-describedby');
            }
        }

        function showRequiredFieldError(control, text) {
            const id = validationId(control);
            control.id = id;
            control.setAttribute('data-app-validation-invalid', 'true');
            control.setAttribute('aria-invalid', 'true');

            let message = Array.from(control.parentElement.querySelectorAll('[data-app-validation-message]'))
                .find((item) => item.dataset.appValidationMessage === id);
            if (!message) {
                message = document.createElement('p');
                message.className = 'app-validation-message';
                message.setAttribute('data-app-validation-message', id);
                message.id = `${id}-error`;
                message.setAttribute('role', 'alert');
                message.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="9"></circle><path stroke-linecap="round" d="M12 8v5m0 3h.01"></path></svg><span></span>';
                control.insertAdjacentElement('afterend', message);
            }
            message.querySelector('span').textContent = text;

            const describedBy = new Set((control.getAttribute('aria-describedby') || '').split(/\s+/).filter(Boolean));
            describedBy.add(message.id);
            control.setAttribute('aria-describedby', Array.from(describedBy).join(' '));
        }

        function validateRequiredControl(control) {
            if (control.disabled || !control.willValidate) return true;

            const error = getRequiredFieldError(control);
            if (!error) {
                clearRequiredFieldError(control);
                return true;
            }

            showRequiredFieldError(control, error);
            return false;
        }

        document.querySelectorAll('form').forEach((form) => {
            form.noValidate = true;
        });

        document.addEventListener('submit', (event) => {
            const form = event.target;
            if (!(form instanceof HTMLFormElement)) return;

            form.noValidate = true;
            const controls = Array.from(form.querySelectorAll(validationControlSelector));
            let firstInvalidControl = null;
            controls.forEach((control) => {
                if (!validateRequiredControl(control) && firstInvalidControl === null) {
                    firstInvalidControl = control;
                }
            });

            if (firstInvalidControl) {
                event.preventDefault();
                firstInvalidControl.focus();
            }
        }, true);

        document.addEventListener('input', (event) => {
            const control = event.target;
            if (control.matches && control.matches(validationControlSelector) && control.hasAttribute('data-app-validation-invalid')) {
                validateRequiredControl(control);
            }
        });

        document.addEventListener('change', (event) => {
            const control = event.target;
            if (control.matches && control.matches(validationControlSelector) && control.hasAttribute('data-app-validation-invalid')) {
                validateRequiredControl(control);
            }
        });

        document.addEventListener('reset', (event) => {
            window.setTimeout(() => {
                event.target.querySelectorAll('[data-app-validation-invalid="true"]').forEach(clearRequiredFieldError);
            });
        });

        const sidebarToggle = document.getElementById('sidebar-toggle');

        function updateSidebarToggle() {
            const collapsed = document.documentElement.classList.contains('sidebar-collapsed');
            const label = collapsed ? 'Perluas sidebar' : 'Ciutkan sidebar';
            sidebarToggle.setAttribute('aria-expanded', String(!collapsed));
            sidebarToggle.setAttribute('aria-label', label);
            sidebarToggle.title = label;
        }

        updateSidebarToggle();
        sidebarToggle.addEventListener('click', () => {
            const collapsed = document.documentElement.classList.toggle('sidebar-collapsed');
            updateSidebarToggle();

            try {
                localStorage.setItem('nawantara-sidebar-collapsed', String(collapsed));
            } catch (error) {
                // Current-page toggle remains functional when browser storage is unavailable.
            }
        });

        const logoutModal = document.getElementById('logout-modal');
        const logoutTrigger = document.getElementById('logout-trigger');
        const logoutCancel = document.getElementById('logout-cancel');

        function closeLogoutModal() {
            logoutModal.classList.add('hidden');
            logoutModal.classList.remove('flex');
            logoutModal.setAttribute('aria-hidden', 'true');
            logoutTrigger.focus();
        }

        logoutTrigger.addEventListener('click', () => {
            logoutModal.classList.remove('hidden');
            logoutModal.classList.add('flex');
            logoutModal.setAttribute('aria-hidden', 'false');
            logoutCancel.focus();
        });

        logoutCancel.addEventListener('click', closeLogoutModal);

        logoutModal.addEventListener('click', (event) => {
            if (event.target === logoutModal) {
                closeLogoutModal();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !logoutModal.classList.contains('hidden')) {
                closeLogoutModal();
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
