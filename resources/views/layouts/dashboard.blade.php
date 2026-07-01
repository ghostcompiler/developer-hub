<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard - Ghost Compiler')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Early theme detection -->
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            if (localStorage.getItem('color-theme') !== 'light') {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex bg-brand-bg font-sans text-brand-muted antialiased">

    <!-- ─── SIDEBAR ─────────────────────────────────────────────── -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-brand-border bg-brand-card/60 backdrop-blur-md transition-transform duration-300 lg:translate-x-0 -translate-x-full">
        <!-- Logo -->
        <div class="flex h-16 items-center gap-3 border-b border-brand-border px-5">
            <a href="{{ route('projects.index') }}" class="flex items-center gap-3 hover:opacity-90 transition">
                <img src="{{ asset('images/logo.png') }}" alt="Ghost Compiler" class="h-8 w-8 rounded-lg object-contain border border-brand-border">
                <span class="font-mono text-base font-bold tracking-tight text-brand-text">ghostcompiler<span class="text-brand-accent">.in</span></span>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            @php $user = Auth::user(); @endphp

            <p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-brand-muted/60">Main</p>

            <a href="{{ $user->isAdmin() ? route('admin.dashboard') : route('dashboard') }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold transition
                      {{ ((request()->routeIs('dashboard') && !request()->has('tab')) || (request()->routeIs('admin.dashboard') && !request()->has('tab'))) ? 'bg-brand-accent/10 text-brand-accent border border-brand-accent/20' : 'text-brand-muted hover:bg-brand-bg hover:text-brand-text border border-transparent' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Dashboard
            </a>

            @if(!$user->isAdmin())
            <a href="{{ route('dashboard', ['tab' => 'repos']) }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold transition {{ (request('tab') === 'repos' || request()->routeIs('repos.link') || request()->routeIs('dashboard.repos.edit')) ? 'bg-brand-accent/10 text-brand-accent border border-brand-accent/20' : 'text-brand-muted hover:bg-brand-bg hover:text-brand-text border border-transparent' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                </svg>
                Your Repos
            </a>

            <a href="{{ route('dashboard', ['tab' => 'blogs']) }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold transition {{ (request('tab') === 'blogs' || request()->routeIs('blogs.create') || request()->routeIs('dashboard.blogs.edit')) ? 'bg-brand-accent/10 text-brand-accent border border-brand-accent/20' : 'text-brand-muted hover:bg-brand-bg hover:text-brand-text border border-transparent' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Blog Posts
            </a>

            <a href="{{ route('dashboard', ['tab' => 'tokens']) }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold transition {{ request('tab') === 'tokens' ? 'bg-brand-accent/10 text-brand-accent border border-brand-accent/20' : 'text-brand-muted hover:bg-brand-bg hover:text-brand-text border border-transparent' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                API Tokens
            </a>
            @endif

            @if($user->isAdmin())
            <p class="px-3 mt-4 mb-2 text-[10px] font-bold uppercase tracking-widest text-brand-muted/60">Admin</p>

            <a href="{{ route('admin.dashboard', ['tab' => 'moderation']) }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold transition {{ request('tab') === 'moderation' ? 'bg-brand-accent/10 text-brand-accent border border-brand-accent/20' : 'text-brand-muted hover:bg-brand-bg hover:text-brand-text border border-transparent' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Moderation
            </a>

            <a href="{{ route('admin.dashboard', ['tab' => 'users']) }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold transition {{ request('tab') === 'users' ? 'bg-brand-accent/10 text-brand-accent border border-brand-accent/20' : 'text-brand-muted hover:bg-brand-bg hover:text-brand-text border border-transparent' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Users
            </a>

            <a href="{{ route('admin.dashboard', ['tab' => 'all-blogs']) }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold transition {{ (request('tab') === 'all-blogs' || (request()->routeIs('dashboard.blogs.edit') && Auth::user()->isAdmin())) ? 'bg-brand-accent/10 text-brand-accent border border-brand-accent/20' : 'text-brand-muted hover:bg-brand-bg hover:text-brand-text border border-transparent' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Blogs
            </a>

            <a href="{{ route('admin.dashboard', ['tab' => 'all-repos']) }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold transition {{ (request('tab') === 'all-repos' || (request()->routeIs('dashboard.repos.edit') && Auth::user()->isAdmin())) ? 'bg-brand-accent/10 text-brand-accent border border-brand-accent/20' : 'text-brand-muted hover:bg-brand-bg hover:text-brand-text border border-transparent' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                </svg>
                Repos
            </a>

            <a href="{{ route('admin.dashboard', ['tab' => 'settings']) }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold transition {{ request('tab') === 'settings' ? 'bg-brand-accent/10 text-brand-accent border border-brand-accent/20' : 'text-brand-muted hover:bg-brand-bg hover:text-brand-text border border-transparent' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Site Settings
            </a>

            <a href="{{ route('admin.policies.edit') }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold transition {{ request()->routeIs('admin.policies.edit') ? 'bg-brand-accent/10 text-brand-accent border border-brand-accent/20' : 'text-brand-muted hover:bg-brand-bg hover:text-brand-text border border-transparent' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Policy Editor
            </a>
            @endif

            <p class="px-3 mt-4 mb-2 text-[10px] font-bold uppercase tracking-widest text-brand-muted/60">Account</p>

            <a href="{{ route('dashboard.two-factor') }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold transition
                      {{ request()->routeIs('dashboard.two-factor') ? 'bg-brand-accent/10 text-brand-accent border border-brand-accent/20' : 'text-brand-muted hover:bg-brand-bg hover:text-brand-text border border-transparent' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Security & 2FA
            </a>
        </nav>

        <!-- User card at bottom -->
        <div class="border-t border-brand-border p-4">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-full bg-brand-accent/15 border border-brand-accent/20 flex items-center justify-center text-xs font-bold text-brand-accent">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-brand-text truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-brand-muted truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- ─── MAIN AREA ────────────────────────────────────────────── -->
    <div class="flex flex-1 flex-col lg:ml-64">

        <!-- Top Bar -->
        <header class="sticky top-0 z-40 flex h-16 items-center justify-between border-b border-brand-border bg-brand-bg/90 backdrop-blur-sm px-6">
            <!-- Mobile menu toggle -->
            <button id="sidebar-toggle" class="lg:hidden p-2 rounded-lg border border-brand-border text-brand-muted hover:text-brand-text transition cursor-pointer">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <h1 class="text-sm font-bold text-brand-text hidden lg:block">@yield('page-title', 'Dashboard')</h1>

            <div class="flex items-center gap-3 ml-auto">
                <!-- Theme Toggle -->
                <button id="theme-toggle" type="button" class="rounded-lg border border-brand-border bg-brand-card/50 p-2 text-brand-muted transition hover:border-brand-accent hover:text-brand-text cursor-pointer" title="Toggle theme">
                    <svg id="theme-toggle-dark-icon" class="hidden h-4 w-4 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg id="theme-toggle-light-icon" class="hidden h-4 w-4 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                </button>

                <!-- User dropdown -->
                <div class="relative" id="user-menu-container">
                    <button id="user-menu-btn" class="flex items-center gap-2 rounded-lg border border-brand-border bg-brand-card/50 px-3 py-1.5 text-sm font-semibold text-brand-text hover:border-brand-accent/40 transition cursor-pointer">
                        <div class="h-6 w-6 rounded-full bg-brand-accent/15 flex items-center justify-center text-[10px] font-bold text-brand-accent">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <span class="hidden sm:inline text-xs">{{ Auth::user()->name }}</span>
                        <svg class="h-3.5 w-3.5 text-brand-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="user-dropdown" class="hidden absolute right-0 top-full mt-2 w-52 rounded-xl border border-brand-border bg-brand-card shadow-lg shadow-black/20 overflow-hidden z-50">
                        <div class="px-4 py-3 border-b border-brand-border">
                            <p class="text-xs font-bold text-brand-text">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-brand-muted truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="py-1">
                            <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard', ['tab' => 'profile']) : route('dashboard', ['tab' => 'profile']) }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-brand-muted hover:bg-brand-bg hover:text-brand-text transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                Profile
                            </a>
                            <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard', ['tab' => 'tokens']) : route('dashboard', ['tab' => 'tokens']) }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-brand-muted hover:bg-brand-bg hover:text-brand-text transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                                API Tokens
                            </a>
                            <a href="{{ route('dashboard.two-factor') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-brand-muted hover:bg-brand-bg hover:text-brand-text transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                Security & 2FA
                            </a>
                            <a href="{{ route('projects.index') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-brand-muted hover:bg-brand-bg hover:text-brand-text transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                Back to Site
                            </a>
                        </div>
                        <div class="border-t border-brand-border py-1">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-rose-500 hover:bg-rose-500/5 transition cursor-pointer">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mx-6 mt-4 rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-600 dark:text-emerald-400 animate-fade-in" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-4 rounded-xl border border-rose-500/20 bg-rose-500/10 px-4 py-3 text-sm text-rose-500 animate-fade-in" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-y-auto">
            @yield('content')
        </main>
    </div>

    <!-- Sidebar overlay for mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 z-40 bg-black/50 lg:hidden hidden"></div>

    <script>
        // Theme toggle
        const themeToggleDarkIcon  = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        if (document.documentElement.classList.contains('dark')) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        document.getElementById('theme-toggle').addEventListener('click', function() {
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        });

        // User dropdown
        const userMenuBtn = document.getElementById('user-menu-btn');
        const userDropdown = document.getElementById('user-dropdown');
        userMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
        });
        document.addEventListener('click', function() { userDropdown.classList.add('hidden'); });

        // Mobile sidebar
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const toggle  = document.getElementById('sidebar-toggle');
        if (toggle) {
            toggle.addEventListener('click', function() {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            });
            overlay.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        }
    </script>
</body>
</html>
