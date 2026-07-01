<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index, follow">

    <!-- SEO Meta Tags -->
    <title>
        @if(trim($__env->yieldContent('title')))
            {{ str_ends_with(strtolower(trim($__env->yieldContent('title'))), 'ghost compiler') ? trim($__env->yieldContent('title')) : trim($__env->yieldContent('title')) . ' - Ghost Compiler' }}
        @else
            Ghost Compiler - Open Source SDKs, Plugins, and Documentation
        @endif
    </title>
    <meta name="description" content="@yield('meta_description', 'Explore open-source Laravel SDKs, Plesk extensions, developer utilities, and comprehensive technical documentation by Ghost Compiler.')">
    <meta name="keywords" content="@yield('meta_keywords', 'ghostcompiler, open source, laravel sdk, plesk extension, developer tools, documentation')">
    <link rel="canonical" href="@yield('canonical_url', url()->current())">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Ghost Compiler - Open Source SDKs, Plugins, and Documentation')">
    <meta property="og:description" content="@yield('meta_description', 'Explore open-source Laravel SDKs, Plesk extensions, developer utilities, and comprehensive technical documentation by Ghost Compiler.')">
    <meta property="og:image" content="@yield('og_image', asset('images/logo.png'))">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', 'Ghost Compiler - Open Source SDKs, Plugins, and Documentation')">
    <meta property="twitter:description" content="@yield('meta_description', 'Explore open-source Laravel SDKs, Plesk extensions, developer utilities, and comprehensive technical documentation by Ghost Compiler.')">
    <meta property="twitter:image" content="@yield('og_image', asset('images/logo.png'))">

    <!-- Icons & Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Early Theme Selector Injection to prevent flashing -->
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            // Default to dark mode if not explicitly set to light
            if (localStorage.getItem('color-theme') === 'light') {
                document.documentElement.classList.remove('dark');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        }
    </script>

    <!-- Fonts CDN -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    <!-- Vite Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Highlight.js Syntax Highlighting Style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">

    <!-- Structured Data (JSON-LD) -->
    @yield('schema')
</head>
<body class="flex min-h-full flex-col bg-brand-bg font-sans text-brand-muted">
    <!-- Glow Effects (Only visible in dark mode) -->
    <div class="pointer-events-none absolute top-0 left-1/2 -z-10 h-[600px] w-full max-w-7xl -translate-x-1/2 bg-[radial-gradient(ellipse_at_top,rgba(16,185,129,0.06),transparent_50%)] dark:bg-[radial-gradient(ellipse_at_top,rgba(16,185,129,0.12),transparent_50%)]"></div>

    <!-- Header / Navigation -->
    <header class="sticky top-0 z-40 w-full border-b border-brand-border bg-brand-bg/85 backdrop-blur-md transition-colors">
        <div class="container mx-auto flex h-20 items-center justify-between px-6 md:px-10">
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <a href="{{ route('projects.index') }}" class="flex items-center gap-3 transition hover:opacity-90">
                    <img src="{{ asset('images/logo.png') }}" alt="ghostcompiler avatar" class="h-10 w-10 rounded-xl object-contain border border-brand-border shadow-lg shadow-emerald-500/10 dark:shadow-emerald-500/20">
                    <span class="hidden font-mono text-xl font-bold tracking-tight text-brand-text md:block">
                        ghostcompiler<span class="text-brand-accent">.in</span>
                    </span>
                </a>

                <nav class="hidden md:flex items-center gap-6">
                    <a href="{{ route('projects.index') }}" class="text-sm font-semibold {{ request()->routeIs('projects.*') ? 'text-brand-accent' : 'text-brand-muted hover:text-brand-text' }} transition">Projects</a>
                    <a href="{{ route('blogs.index') }}" class="text-sm font-semibold {{ request()->routeIs('blogs.*') ? 'text-brand-accent' : 'text-brand-muted hover:text-brand-text' }} transition">Blogs</a>
                </nav>
            </div>

            <!-- Nav Links / Actions -->
            <div class="flex items-center gap-3">
                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-brand-accent hover:underline transition">Dashboard</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-brand-accent hover:underline transition">Dashboard</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="hidden md:inline">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-brand-muted hover:text-rose-500 transition cursor-pointer">Sign Out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-brand-muted hover:text-brand-text transition">Sign In</a>
                    <a href="{{ route('register') }}" class="hidden md:inline-block text-xs font-semibold text-brand-accent border border-brand-accent/20 hover:bg-brand-accent/10 px-3.5 py-2 rounded-xl transition">Register</a>
                @endauth

                <!-- Theme Toggle Button -->
                <button id="theme-toggle" type="button" class="rounded-xl border border-brand-border bg-brand-card/50 p-2.5 text-brand-muted transition hover:border-brand-accent hover:text-brand-text cursor-pointer" title="Toggle theme">
                    <!-- Moon Icon (Visible in dark mode) -->
                    <svg id="theme-toggle-dark-icon" class="hidden h-5 w-5 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <!-- Sun Icon (Visible in light mode) -->
                    <svg id="theme-toggle-light-icon" class="hidden h-5 w-5 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                    </svg>
                </button>

                <a href="https://github.com/ghostcompiler" target="_blank" rel="noopener noreferrer" class="hidden md:flex items-center gap-2 rounded-xl border border-brand-border bg-brand-card/50 px-4 py-2.5 text-sm font-semibold text-brand-text transition hover:border-brand-accent hover:text-brand-accent">
                    <svg class="h-4 w-4 fill-current" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 0C3.58 0 0 3.58 0 8C0 11.54 2.29 14.53 5.47 15.59C5.87 15.66 6.02 15.42 6.02 15.21C6.02 15.02 6.01 14.39 6.01 13.72C4 14.09 3.48 13.23 3.32 12.78C3.23 12.55 2.84 11.84 2.5 11.65C2.22 11.5 1.82 11.13 2.49 11.12C3.12 11.11 3.57 11.7 3.72 11.94C4.44 13.15 5.59 12.81 6.05 12.6C6.12 12.08 6.33 11.73 6.56 11.53C4.78 11.33 2.92 10.64 2.92 7.58C2.92 6.71 3.23 5.99 3.74 5.43C3.66 5.23 3.38 4.41 3.82 3.31C3.82 3.31 4.49 3.1 6.02 4.13C6.66 3.95 7.34 3.86 8.02 3.86C8.7 3.86 9.38 3.95 10.02 4.13C11.55 3.09 12.22 3.31 12.22 3.31C12.66 4.41 12.38 5.23 12.3 5.43C12.81 5.99 13.12 6.7 13.12 7.58C13.12 10.65 11.25 11.33 9.47 11.53C9.76 11.78 10.01 12.26 10.01 13.01C10.01 14.08 10 14.94 10 15.21C10 15.42 10.15 15.67 10.55 15.59C13.71 14.53 16 11.53 16 8C16 3.58 12.42 0 8 0Z"/>
                    </svg>
                    <span>GitHub</span>
                </a>

                <!-- Mobile Menu Button -->
                <button id="mobile-sidebar-toggle" type="button" class="md:hidden rounded-xl border border-brand-border bg-brand-card/50 p-2.5 text-brand-muted transition hover:border-brand-accent hover:text-brand-text cursor-pointer" title="Open Menu" aria-controls="mobile-sidebar" aria-expanded="false">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto border-t border-brand-border bg-brand-bg py-10 text-center text-sm text-brand-muted transition-colors">
        <div class="container mx-auto px-6 md:px-10">
            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                <p>&copy; {{ date('Y') }} ghostcompiler.in. All rights reserved.</p>
                <div class="flex flex-wrap justify-center gap-6">
                    <a href="{{ route('policies.privacy') }}" class="transition hover:text-brand-text">Privacy Policy</a>
                    <a href="{{ route('policies.terms') }}" class="transition hover:text-brand-text">Terms of Service</a>
                    <a href="{{ route('policies.conditions') }}" class="transition hover:text-brand-text">Terms & Conditions</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Theme Toggle Scripts -->
    <script>
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Change the icons inside the button based on current settings
        if (document.documentElement.classList.contains('dark')) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        const themeToggleBtn = document.getElementById('theme-toggle');

        themeToggleBtn.addEventListener('click', function() {
            // toggle icons inside button
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            // if set via local storage previously
            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }
        });
    </script>

    <!-- Mobile Sidebar Drawer -->
    <div id="mobile-sidebar" class="fixed inset-0 z-50 hidden">
        <!-- Backdrop -->
        <div id="mobile-sidebar-backdrop" class="fixed inset-0 bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-300 ease-in-out"></div>
        <!-- Content drawer -->
        <div class="fixed inset-y-0 left-0 w-80 max-w-[85vw] bg-brand-bg border-r border-brand-border p-6 shadow-2xl flex flex-col justify-between transition-transform duration-300 ease-in-out -translate-x-full z-50">
            <div class="space-y-8 overflow-y-auto max-h-[85vh] pr-2">
                <!-- Header: Logo & Close Button -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('projects.index') }}" class="flex items-center gap-3 transition hover:opacity-90">
                        <img src="{{ asset('images/logo.png') }}" alt="logo" class="h-8 w-8 rounded-lg object-contain border border-brand-border">
                        <span class="font-mono text-base font-bold text-brand-text">
                            ghostcompiler<span class="text-brand-accent">.in</span>
                        </span>
                    </a>
                    <button id="mobile-sidebar-close" type="button" class="rounded-lg p-1.5 text-brand-muted hover:text-brand-text hover:bg-brand-card transition cursor-pointer">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="space-y-1">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-brand-muted/50 mb-3 px-3">Navigation</p>
                    <a href="{{ route('projects.index') }}" class="block rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs('projects.*') ? 'bg-brand-card text-brand-text' : 'text-brand-muted hover:text-brand-text hover:bg-brand-card/30' }} transition">Projects</a>
                    <a href="{{ route('blogs.index') }}" class="block rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs('blogs.*') ? 'bg-brand-card text-brand-text' : 'text-brand-muted hover:text-brand-text hover:bg-brand-card/30' }} transition">Blogs</a>
                    
                    @guest
                        <a href="{{ route('register') }}" class="block rounded-lg px-3 py-2 text-sm font-semibold text-brand-accent hover:bg-brand-accent/5 transition">Register</a>
                    @endguest

                    <!-- GitHub link -->
                    <a href="https://github.com/ghostcompiler" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-brand-muted hover:text-brand-text hover:bg-brand-card/30 transition">
                        <svg class="h-4 w-4 fill-current" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 0C3.58 0 0 3.58 0 8C0 11.54 2.29 14.53 5.47 15.59C5.87 15.66 6.02 15.42 6.02 15.21C6.02 15.02 6.01 14.39 6.01 13.72C4 14.09 3.48 13.23 3.32 12.78C3.23 12.55 2.84 11.84 2.5 11.65C2.22 11.5 1.82 11.13 2.49 11.12C3.12 11.11 3.57 11.7 3.72 11.94C4.44 13.15 5.59 12.81 6.05 12.6C6.12 12.08 6.33 11.73 6.56 11.53C4.78 11.33 2.92 10.64 2.92 7.58C2.92 6.71 3.23 5.99 3.74 5.43C3.66 5.23 3.38 4.41 3.82 3.31C3.82 3.31 4.49 3.1 6.02 4.13C6.66 3.95 7.34 3.86 8.02 3.86C8.7 3.86 9.38 3.95 10.02 4.13C11.55 3.09 12.22 3.31 12.22 3.31C12.66 4.41 12.38 5.23 12.3 5.43C12.81 5.99 13.12 6.7 13.12 7.58C13.12 10.65 11.25 11.33 9.47 11.53C9.76 11.78 10.01 12.26 10.01 13.01C10.01 14.08 10 14.94 10 15.21C10 15.42 10.15 15.67 10.55 15.59C13.71 14.53 16 11.53 16 8C16 3.58 12.42 0 8 0Z"/>
                        </svg>
                        <span>GitHub</span>
                    </a>

                    @auth
                        <form action="{{ route('logout') }}" method="POST" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left rounded-lg px-3 py-2 text-sm font-semibold text-rose-500 hover:bg-rose-500/5 transition cursor-pointer">Sign Out</button>
                        </form>
                    @endauth
                </nav>

                <!-- Page-Specific Mobile Sidebar Contents -->
                @yield('mobile_sidebar')
            </div>

            <!-- Footer in Drawer -->
            <div class="border-t border-brand-border/60 pt-4 text-[10px] text-brand-muted flex flex-wrap gap-2 justify-center">
                <a href="{{ route('policies.privacy') }}" class="hover:underline">Privacy Policy</a>
                <span>&bull;</span>
                <a href="{{ route('policies.terms') }}" class="hover:underline">Terms of Service</a>
                <span>&bull;</span>
                <a href="{{ route('policies.conditions') }}" class="hover:underline">T&amp;C</a>
            </div>
        </div>
    </div>

    <!-- Highlight.js Syntax Highlighting Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Remove any dangling GitHub loading loaders/spinners
            document.querySelectorAll('.js-render-enrichment-loader').forEach(loader => loader.remove());

            // Check for mermaid code blocks
            const mermaidTargets = document.querySelectorAll('pre[lang="mermaid"], pre code.language-mermaid, .highlight-source-mermaid pre, pre code.mermaid, code.language-mermaid');
            if (mermaidTargets.length > 0) {
                // Replace them with div.mermaid synchronously so highlight.js doesn't highlight them
                mermaidTargets.forEach((el, index) => {
                    let code = el.textContent || el.innerText;
                    const container = document.createElement('div');
                    container.className = 'mermaid flex justify-center w-full my-6 p-4 bg-brand-card/25 rounded-2xl border border-brand-border/40 overflow-x-auto';
                    container.id = 'mermaid-diagram-' + index;
                    container.textContent = code.trim();
                    
                    let outer = el;
                    if (el.tagName === 'CODE' && el.parentElement && el.parentElement.tagName === 'PRE') {
                        outer = el.parentElement;
                    }
                    if (outer.parentElement && outer.parentElement.classList.contains('highlight-source-mermaid')) {
                        outer = outer.parentElement;
                    }
                    // Handle GitHub-style render-viewer wrapper
                    let githubWrapper = outer.closest('.render-viewer, .js-render-enrichment-target');
                    if (githubWrapper) {
                        outer = githubWrapper;
                    }
                    
                    if (outer && outer.parentNode) {
                        outer.parentNode.replaceChild(container, outer);
                    }
                });

                // Dynamically load Mermaid.js and initialize/run
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js';
                script.onload = () => {
                    try {
                        const isDark = document.documentElement.classList.contains('dark');
                        mermaid.initialize({
                            startOnLoad: false,
                            theme: isDark ? 'dark' : 'default',
                            securityLevel: 'loose'
                        });
                        mermaid.run();
                    } catch (e) {
                        console.error("Mermaid initialization or rendering failed:", e);
                    }
                };
                document.body.appendChild(script);
            }

            // Initialize Syntax Highlighting
            if (typeof hljs !== 'undefined') {
                hljs.highlightAll();
            }

            // Mobile Sidebar elements
            const sidebar = document.getElementById('mobile-sidebar');
            if (sidebar) {
                const sidebarDrawer = sidebar.querySelector('.fixed.inset-y-0.left-0');
                const sidebarBackdrop = document.getElementById('mobile-sidebar-backdrop');
                const toggleBtn = document.getElementById('mobile-sidebar-toggle');
                const closeBtn = document.getElementById('mobile-sidebar-close');

                function openSidebar() {
                    sidebar.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                    if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'true');
                    setTimeout(() => {
                        sidebarDrawer.classList.remove('-translate-x-full');
                        sidebarDrawer.classList.add('translate-x-0');
                        sidebarBackdrop.classList.remove('opacity-0');
                        sidebarBackdrop.classList.add('opacity-100');
                    }, 20);
                }

                function closeSidebar() {
                    if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'false');
                    sidebarDrawer.classList.remove('translate-x-0');
                    sidebarDrawer.classList.add('-translate-x-full');
                    sidebarBackdrop.classList.remove('opacity-100');
                    sidebarBackdrop.classList.add('opacity-0');
                    setTimeout(() => {
                        sidebar.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                    }, 300);
                }

                if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
                if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
                if (sidebarBackdrop) sidebarBackdrop.addEventListener('click', closeSidebar);
                sidebar.querySelectorAll('a').forEach(link => link.addEventListener('click', closeSidebar));
            }
        });
    </script>
</body>
</html>
