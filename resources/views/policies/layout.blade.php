@extends('layouts.app')

@section('title', $title . ' — Ghost Compiler')
@section('meta_description', 'Ghost Compiler legal policies — ' . $title)

@section('content')
    {{-- Hero Banner --}}
    <div
        class="relative overflow-hidden border-b border-brand-border/60 bg-linear-to-b from-brand-card/20 to-transparent py-16">
        {{-- subtle grid pattern --}}
        <div
            class="pointer-events-none absolute inset-0 bg-[linear-gradient(rgba(16,185,129,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(16,185,129,0.03)_1px,transparent_1px)] bg-size-[60px_60px]">
        </div>
        <div class="container mx-auto px-6 md:px-10 relative">
            {{-- Breadcrumb --}}
            <nav class="mb-6 flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-brand-muted"
                aria-label="Breadcrumb">
                <a href="{{ route('projects.index') }}" class="transition hover:text-brand-accent">Home</a>
                <svg class="h-3 w-3 text-brand-muted/50" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-brand-text">{{ $title }}</span>
            </nav>

            {{-- Shield icon + title --}}
            <div class="flex items-center gap-4">
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-brand-accent/10 ring-1 ring-brand-accent/20">
                    <svg class="h-7 w-7 text-brand-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-brand-text sm:text-4xl">{{ $title }}</h1>
                    <p class="mt-1 text-sm text-brand-muted">Effective date: <span
                            class="font-semibold text-brand-text">{{ $effectiveDate ?? date('F j, Y') }}</span> ·
                        ghostcompiler.in</p>
                </div>
            </div>

            {{-- Policy switcher tabs --}}
            <div class="mt-8 flex flex-wrap gap-2">
                <a href="{{ route('policies.privacy') }}"
                    class="rounded-full px-4 py-1.5 text-xs font-bold uppercase tracking-widest transition
                          {{ request()->routeIs('policies.privacy') ? 'bg-brand-accent text-white' : 'bg-brand-card text-brand-muted hover:text-brand-text border border-brand-border/60' }}">
                    Privacy Policy
                </a>
                <a href="{{ route('policies.terms') }}"
                    class="rounded-full px-4 py-1.5 text-xs font-bold uppercase tracking-widest transition
                          {{ request()->routeIs('policies.terms') ? 'bg-brand-accent text-white' : 'bg-brand-card text-brand-muted hover:text-brand-text border border-brand-border/60' }}">
                    Terms of Service
                </a>
                <a href="{{ route('policies.conditions') }}"
                    class="rounded-full px-4 py-1.5 text-xs font-bold uppercase tracking-widest transition
                          {{ request()->routeIs('policies.conditions') ? 'bg-brand-accent text-white' : 'bg-brand-card text-brand-muted hover:text-brand-text border border-brand-border/60' }}">
                    Terms &amp; Conditions
                </a>
            </div>
        </div>
    </div>

    {{-- Main content area --}}
    <div class="container mx-auto px-6 md:px-10 py-12">
        <div class="flex flex-col lg:flex-row lg:gap-12 xl:gap-16">

            {{-- ── Policy Body ─────────────────────────────────────────────── --}}
            <article id="policy-body" class="min-w-0 flex-1">
                <div id="policy-content" class="policy-prose">
                    {!! $htmlContent !!}
                </div>

                {{-- Footer note --}}
                <div class="mt-16 rounded-2xl bg-brand-card/40 p-6 flex items-start gap-4">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-brand-accent" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-brand-text">Questions about this policy?</p>
                        <p class="mt-1 text-sm text-brand-muted">Contact us at <a href="mailto:hello@ghostcompiler.in"
                                class="text-brand-accent hover:underline font-semibold">hello@ghostcompiler.in</a> — we
                            respond within 48 hours.</p>
                    </div>
                </div>
            </article>

            {{-- ── Sticky TOC Sidebar ────────────────────────────────────────── --}}
            <aside class="hidden lg:block w-64 xl:w-72 shrink-0">
                <div class="sticky top-28 space-y-6">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-brand-muted/60 mb-3">On this page</p>
                        <nav id="toc-nav" class="space-y-1">
                            {{-- Populated by JS --}}
                        </nav>
                    </div>

                    {{-- Other policies --}}
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-brand-muted/60 mb-3">Other Policies
                        </p>
                        <nav class="space-y-1">
                            @if(!request()->routeIs('policies.privacy'))
                                <a href="{{ route('policies.privacy') }}"
                                    class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-brand-muted hover:text-brand-text hover:bg-brand-card/50 transition group">
                                    <svg class="h-3.5 w-3.5 shrink-0 text-brand-accent/60 group-hover:text-brand-accent transition"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                                    </svg>
                                    Privacy Policy
                                </a>
                            @endif
                            @if(!request()->routeIs('policies.terms'))
                                <a href="{{ route('policies.terms') }}"
                                    class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-brand-muted hover:text-brand-text hover:bg-brand-card/50 transition group">
                                    <svg class="h-3.5 w-3.5 shrink-0 text-brand-accent/60 group-hover:text-brand-accent transition"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                                    </svg>
                                    Terms of Service
                                </a>
                            @endif
                            @if(!request()->routeIs('policies.conditions'))
                                <a href="{{ route('policies.conditions') }}"
                                    class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-brand-muted hover:text-brand-text hover:bg-brand-card/50 transition group">
                                    <svg class="h-3.5 w-3.5 shrink-0 text-brand-accent/60 group-hover:text-brand-accent transition"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                                    </svg>
                                    Terms &amp; Conditions
                                </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </aside>

        </div>
    </div>

    {{-- ── Scoped Styles ─────────────────────────────────────────────────────── --}}
    <style>
        /* Policy body typography — no borders, clean and readable */
        .policy-prose {
            color: var(--color-brand-muted, #94a3b8);
            font-size: 0.9375rem;
            line-height: 1.8;
        }

        /* Section wrappers added by JS for smooth scroll target padding */
        .policy-prose .section-anchor {
            scroll-margin-top: 6rem;
        }

        /* H1 — big title with gradient accent underline */
        .policy-prose h1 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--color-brand-text, #e2e8f0);
            letter-spacing: -0.025em;
            margin: 0 0 0.375rem 0;
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .policy-prose h1::after {
            content: '';
            display: block;
            width: 3rem;
            height: 3px;
            background: linear-gradient(90deg, #10b981, transparent);
            border-radius: 2px;
            margin-top: 0.875rem;
        }

        /* Date line below h1 */
        .policy-prose h1+p em,
        .policy-prose h1+p strong {
            font-size: 0.8125rem;
            color: var(--color-brand-muted, #94a3b8);
            font-style: normal;
        }

        /* H2 — section headers */
        .policy-prose h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--color-brand-text, #e2e8f0);
            margin: 2.5rem 0 0.875rem 0;
            padding-bottom: 0.625rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            scroll-margin-top: 6rem;
        }

        .policy-prose h2::before {
            content: '';
            display: inline-block;
            width: 3px;
            height: 1.125rem;
            background: #10b981;
            border-radius: 2px;
            flex-shrink: 0;
        }

        /* H3 — subsection */
        .policy-prose h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--color-brand-text, #e2e8f0);
            margin: 1.75rem 0 0.5rem 0;
            scroll-margin-top: 6rem;
        }

        /* Paragraphs */
        .policy-prose p {
            margin: 0.875rem 0;
        }

        /* Lists */
        .policy-prose ul {
            list-style: none;
            padding: 0;
            margin: 1rem 0;
            space-y: 0.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .policy-prose ul li {
            padding-left: 1.25rem;
            position: relative;
        }

        .policy-prose ul li::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0.65em;
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #10b981;
        }

        .policy-prose ol {
            list-style: none;
            padding: 0;
            margin: 1rem 0;
            counter-reset: ol-counter;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .policy-prose ol li {
            padding-left: 2rem;
            position: relative;
            counter-increment: ol-counter;
        }

        .policy-prose ol li::before {
            content: counter(ol-counter) ".";
            position: absolute;
            left: 0;
            font-weight: 700;
            color: #10b981;
            font-size: 0.8125rem;
        }

        /* Strong */
        .policy-prose strong {
            font-weight: 700;
            color: var(--color-brand-text, #e2e8f0);
        }

        /* Links */
        .policy-prose a {
            color: #10b981;
            text-decoration: underline;
            text-underline-offset: 3px;
            transition: opacity 0.15s;
        }

        .policy-prose a:hover {
            opacity: 0.75;
        }

        /* Code */
        .policy-prose code {
            font-size: 0.8125rem;
            font-family: ui-monospace, monospace;
            background: rgba(16, 185, 129, 0.08);
            color: #6ee7b7;
            padding: 0.125rem 0.375rem;
            border-radius: 4px;
        }

        /* Blockquote */
        .policy-prose blockquote {
            border-left: 3px solid #10b981;
            padding-left: 1rem;
            margin: 1.25rem 0;
            color: var(--color-brand-muted, #94a3b8);
            font-style: italic;
        }

        /* TOC items */
        #toc-nav a {
            display: block;
            padding: 0.3rem 0.75rem;
            font-size: 0.8125rem;
            color: var(--color-brand-muted, #94a3b8);
            border-radius: 0.5rem;
            transition: all 0.15s;
            border-left: 2px solid transparent;
            text-decoration: none;
            line-height: 1.4;
        }

        #toc-nav a:hover {
            color: var(--color-brand-text, #e2e8f0);
            background: rgba(16, 185, 129, 0.06);
            border-left-color: rgba(16, 185, 129, 0.4);
        }

        #toc-nav a.toc-active {
            color: #10b981;
            background: rgba(16, 185, 129, 0.08);
            border-left-color: #10b981;
            font-weight: 600;
        }

        #toc-nav .toc-h3 {
            padding-left: 1.5rem;
            font-size: 0.75rem;
        }

        /* Tables — mobile responsive with horizontal scroll */
        .policy-prose table {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-collapse: collapse;
            margin: 1.5rem 0;
            border: 1px solid var(--color-brand-border, rgba(16, 185, 129, 0.15));
            border-radius: 8px;
        }

        .policy-prose th {
            background: rgba(16, 185, 129, 0.06);
            color: var(--color-brand-text, #e2e8f0);
            font-weight: 700;
            font-size: 0.8125rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid var(--color-brand-border, rgba(16, 185, 129, 0.15));
            white-space: nowrap;
        }

        .policy-prose td {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            color: var(--color-brand-muted, #94a3b8);
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            min-width: 140px;
        }

        .policy-prose tr:last-child td {
            border-bottom: none;
        }

        .policy-prose tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.01);
        }
    </style>

    {{-- ── TOC Builder + scroll spy ────────────────────────────────────────────── --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tocNav = document.getElementById('toc-nav');
            const content = document.getElementById('policy-content');
            if (!tocNav || !content) return;

            const headings = content.querySelectorAll('h2, h3');
            if (!headings.length) return;

            headings.forEach((h, i) => {
                // Add id for scroll target
                if (!h.id) {
                    h.id = 'section-' + i + '-' + h.textContent.trim().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
                }

                const a = document.createElement('a');
                a.href = '#' + h.id;
                a.textContent = h.textContent;
                if (h.tagName === 'H3') a.classList.add('toc-h3');
                a.addEventListener('click', e => {
                    e.preventDefault();
                    document.getElementById(h.id)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
                tocNav.appendChild(a);
            });

            // Scroll-spy
            const tocLinks = tocNav.querySelectorAll('a');
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        tocLinks.forEach(l => l.classList.remove('toc-active'));
                        const active = tocNav.querySelector(`a[href="#${entry.target.id}"]`);
                        if (active) active.classList.add('toc-active');
                    }
                });
            }, { rootMargin: '-10% 0px -80% 0px' });

            headings.forEach(h => observer.observe(h));
        });
    </script>
@endsection

@section('mobile_sidebar')
    <!-- Legal Policies Navigation (visible inside mobile drawer) -->
    <div class="space-y-4 pt-4 border-t border-brand-border/60">
        <p class="text-[10px] font-bold uppercase tracking-widest text-brand-muted/50 mb-3 px-3">Legal Policies</p>
        <nav class="space-y-1">
            <a href="{{ route('policies.privacy') }}"
                class="block rounded-lg px-3 py-2 text-xs font-semibold {{ request()->routeIs('policies.privacy') ? 'bg-brand-card text-brand-text font-bold' : 'text-brand-muted hover:text-brand-text hover:bg-brand-card/30' }} transition">
                Privacy Policy
            </a>
            <a href="{{ route('policies.terms') }}"
                class="block rounded-lg px-3 py-2 text-xs font-semibold {{ request()->routeIs('policies.terms') ? 'bg-brand-card text-brand-text font-bold' : 'text-brand-muted hover:text-brand-text hover:bg-brand-card/30' }} transition">
                Terms of Service
            </a>
            <a href="{{ route('policies.conditions') }}"
                class="block rounded-lg px-3 py-2 text-xs font-semibold {{ request()->routeIs('policies.conditions') ? 'bg-brand-card text-brand-text font-bold' : 'text-brand-muted hover:text-brand-text hover:bg-brand-card/30' }} transition">
                Terms &amp; Conditions
            </a>
        </nav>
    </div>
@endsection