@extends('layouts.app')

@section('title', 'Ghost Compiler - Open Source SDKs, Plugins, and Documentation')
@section('meta_description', 'Explore open-source Laravel SDKs, Plesk extensions, developer utilities, and comprehensive technical documentation by Ghost Compiler.')

@section('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'ProfilePage',
    'name' => 'Ghost Compiler Open Source Projects',
    'description' => 'Developer profile listing Laravel SDKs, Plesk extensions, and utilities.',
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'Ghost Compiler',
        'logo' => [
            '@type' => 'ImageObject',
            'url' => asset('images/logo.png')
        ]
    ],
    'mainEntity' => [
        '@type' => 'Person',
        'name' => 'ghostcompiler',
        'url' => 'https://github.com/ghostcompiler',
        'sameAs' => [
            'https://ghostcompiler.in'
        ]
    ]
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endsection

@section('content')
<div class="mx-auto max-w-[1400px]px-6 py-16 md:px-10 lg:py-24 space-y-12">
    @if(session('success'))
        <div class="rounded-xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-sm text-brand-accent">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-xl border border-rose-500/20 bg-rose-500/10 p-4 text-sm text-rose-500">
            {{ session('error') }}
        </div>
    @endif

    <!-- Hero Header Grid -->
    <div class="grid grid-cols-1 gap-12 lg:grid-cols-12 lg:items-center">
        <!-- Headline / Left Column -->
        <div class="lg:col-span-8 text-left space-y-6">
            <h1 class="text-4xl font-extrabold tracking-tight text-brand-text sm:text-5xl lg:text-6.5xl">
                Developer Documentation & SDKs
            </h1>
            <p class="max-w-2xl text-lg text-brand-muted leading-relaxed">
                Production-ready Laravel SDKs, Plesk extensions, and developer utilities. Handcrafted for maximum performance, type safety, and clean API integration.
            </p>
            
            <!-- Summary Badges -->
            <div class="flex flex-wrap gap-3 pt-2">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-brand-border bg-brand-card px-4 py-1.5 text-sm font-semibold text-brand-text shadow-sm">
                    <span class="flex h-2 w-2 rounded-full bg-brand-accent"></span>
                    {{ count($projects) }} Packages
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full border border-brand-border bg-brand-card px-4 py-1.5 text-sm font-semibold text-brand-text shadow-sm">
                    <svg class="h-4 w-4 text-amber-500 fill-current" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                    </svg>
                    {{ number_format(collect($projects)->sum('stars_count')) }} Stars
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full border border-brand-border bg-brand-card px-4 py-1.5 text-sm font-semibold text-brand-text shadow-sm">
                    <svg class="h-4 w-4 text-brand-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    {{ number_format(collect($projects)->sum('downloads_count')) }} Downloads
                </span>
            </div>
        </div>

        <!-- Brand Avatar Logo / Right Column -->
        <div class="hidden lg:block lg:col-span-4 justify-self-center">
            <div class="relative group">
                <!-- Glowing backing decoration -->
                <div class="absolute -inset-1 rounded-full bg-gradient-to-r from-brand-accent to-emerald-400 opacity-25 blur-xl group-hover:opacity-45 transition duration-500"></div>
                <img src="{{ asset('images/logo.png') }}" alt="ghostcompiler logo" class="relative h-64 w-64 rounded-full border-2 border-brand-border bg-brand-bg/50 p-4 object-contain shadow-2xl transition duration-500 group-hover:scale-103">
            </div>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="mt-16 rounded-2xl border border-brand-border bg-brand-card/30 p-8 backdrop-blur transition-colors">
        <div class="flex flex-col gap-6 md:flex-row md:items-center">
            <!-- Search Input -->
            <div class="relative flex-grow">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                    <svg class="h-5 w-5 text-brand-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" id="project-search" placeholder="Search documentation, packages, or keywords..." class="w-full rounded-xl border border-brand-border bg-brand-bg/60 py-3.5 pl-12 pr-4 text-brand-text placeholder-brand-muted outline-none ring-offset-brand-bg transition focus:border-brand-accent/50 focus:ring-2 focus:ring-brand-accent/20">
                <span class="absolute inset-y-0 right-4 hidden items-center text-xs text-brand-muted/70 sm:flex">
                    <kbd class="rounded border border-brand-border bg-brand-card px-2 py-1">⌘K</kbd>
                </span>
            </div>
            
            <!-- Language Filters -->
            <div class="flex flex-wrap gap-2.5">
                <button type="button" class="lang-filter-btn active rounded-xl border border-brand-accent/30 bg-brand-accent/10 px-5 py-3 text-sm font-semibold text-brand-accent transition" data-lang="all">
                    All
                </button>
                @foreach($languages as $lang)
                <button type="button" class="lang-filter-btn rounded-xl border border-brand-border bg-brand-card/50 px-5 py-3 text-sm font-semibold text-brand-muted transition hover:border-brand-accent hover:text-brand-text" data-lang="{{ $lang }}">
                    {{ $lang }}
                </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Projects Grid -->
    <div id="projects-container" class="mt-10 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($projects as $project)
        <div class="project-card group relative flex flex-col justify-between rounded-2xl border border-brand-border bg-brand-card/20 p-8 backdrop-blur transition-all duration-300 hover:border-brand-accent/40 hover:bg-brand-card/40 hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-brand-accent/5" data-name="{{ Str::lower($project->name) }}" data-description="{{ Str::lower($project->description) }}" data-language="{{ $project->language }}" data-topics="{{ implode(',', $project->topics ?? []) }}">
            <div>
                <!-- Card Header -->
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-brand-bg border border-brand-border px-3 py-1 text-xs font-semibold text-brand-text transition-colors">
                        <span class="h-2 w-2 rounded-full {{ $project->language === 'PHP' ? 'bg-blue-500' : ($project->language === 'TypeScript' ? 'bg-teal-400' : ($project->language === 'JavaScript' ? 'bg-yellow-400' : 'bg-brand-accent')) }}"></span>
                        {{ $project->language }}
                    </span>
                    
                    <!-- Stats Badges (Stars / Downloads) -->
                    <div class="flex items-center gap-3">
                        <!-- Downloads -->
                        @if($project->downloads_count > 0)
                        <div class="flex items-center gap-1 text-xs font-semibold text-brand-muted" title="Downloads Count">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            <span>{{ number_format($project->downloads_count) }}</span>
                        </div>
                        @endif
                        
                        <!-- Stars -->
                        <div class="flex items-center gap-1 text-xs font-semibold text-brand-muted group-hover:text-amber-500 transition-colors">
                            <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                            <span>{{ $project->stars_count }}</span>
                        </div>
                    </div>
                </div>

                <!-- Project Title -->
                <h3 class="mt-5 text-xl font-bold text-brand-text transition group-hover:text-brand-accent">
                    <a href="{{ route('projects.show', $project->slug) }}">
                        {{ ucwords(str_replace('-', ' ', $project->name)) }}
                    </a>
                </h3>

                <!-- Project Description -->
                <p class="mt-3.5 line-clamp-3 text-sm text-brand-muted leading-relaxed">
                    {{ $project->description ?? 'No description provided.' }}
                </p>

                <!-- Tags / Topics -->
                @if(!empty($project->topics))
                <div class="mt-5 flex flex-wrap gap-2">
                    @foreach(array_slice($project->topics, 0, 4) as $topic)
                    <span class="rounded bg-brand-bg border border-brand-border px-2.5 py-1 text-[11px] font-semibold text-brand-muted transition hover:border-brand-accent hover:text-brand-text">
                        {{ $topic }}
                    </span>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Card Actions -->
            <div class="mt-8 flex items-center justify-between gap-4 border-t border-brand-border pt-5 transition-colors">
                <a href="{{ route('projects.show', $project->slug) }}" class="inline-flex flex-grow items-center justify-center gap-2 rounded-xl bg-brand-accent px-4 py-2.5 text-center text-sm font-semibold text-white transition hover:bg-brand-accent-hover hover:scale-[1.02] shadow-md shadow-brand-accent/10">
                    <span>View Docs</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                <a href="{{ $project->github_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-brand-border bg-brand-card/50 text-brand-muted transition hover:border-brand-accent hover:text-brand-text" title="GitHub Repository">
                    <svg class="h-4.5 w-4.5 fill-current" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 0C3.58 0 0 3.58 0 8C0 11.54 2.29 14.53 5.47 15.59C5.87 15.66 6.02 15.42 6.02 15.21C6.02 15.02 6.01 14.39 6.01 13.72C4 14.09 3.48 13.23 3.32 12.78C3.23 12.55 2.84 11.84 2.5 11.65C2.22 11.5 1.82 11.13 2.49 11.12C3.12 11.11 3.57 11.7 3.72 11.94C4.44 13.15 5.59 12.81 6.05 12.6C6.12 12.08 6.33 11.73 6.56 11.53C4.78 11.33 2.92 10.64 2.92 7.58C2.92 6.71 3.23 5.99 3.74 5.43C3.66 5.23 3.38 4.41 3.82 3.31C3.82 3.31 4.49 3.1 6.02 4.13C6.66 3.95 7.34 3.86 8.02 3.86C8.7 3.86 9.38 3.95 10.02 4.13C11.55 3.09 12.22 3.31 12.22 3.31C12.66 4.41 12.38 5.23 12.3 5.43C12.81 5.99 13.12 6.7 13.12 7.58C13.12 10.65 11.25 11.33 9.47 11.53C9.76 11.78 10.01 12.26 10.01 13.01C10.01 14.08 10 14.94 10 15.21C10 15.42 10.15 15.67 10.55 15.59C13.71 14.53 16 11.53 16 8C16 3.58 12.42 0 8 0Z"/>
                    </svg>
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Empty State -->
    <div id="empty-state" class="hidden mt-20 text-center py-16 border border-dashed border-brand-border rounded-2xl">
        <svg class="mx-auto h-12 w-12 text-brand-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
        </svg>
        <h3 class="mt-4 text-lg font-semibold text-brand-text">No projects match your criteria</h3>
        <p class="mt-2 text-sm text-brand-muted">Try adjusting your keywords or clearing the filters.</p>
        <button type="button" id="clear-filters" class="mt-4 rounded-xl bg-brand-accent px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-accent-hover">
            Reset All Filters
        </button>
    </div>

    <!-- Community Repositories Section -->
    <div class="mt-24 border-t border-brand-border pt-16 transition-colors">
        <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-center">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-brand-text">Community Linked Repositories</h2>
                <p class="mt-2 text-sm text-brand-muted max-w-xl">
                    Open-source repositories submitted by the developer community. These projects are fully indexed and crawled.
                </p>
            </div>
            
            @auth
                <a href="{{ route('repos.link') }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-accent px-5 py-3 text-sm font-semibold text-white transition hover:bg-brand-accent-hover hover:scale-[1.02] shadow-md shadow-brand-accent/10 shrink-0">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                    <span>Link Repository</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl border border-brand-border bg-brand-card/50 px-5 py-3 text-sm font-semibold text-brand-text transition hover:border-brand-accent hover:text-brand-accent shrink-0">
                    <span>Sign in to Link Repo</span>
                </a>
            @endauth
        </div>

        @if($linkedRepos->isEmpty())
            <div class="mt-10 rounded-2xl border border-dashed border-brand-border p-12 text-center transition-colors">
                <p class="text-brand-muted">No community repositories linked yet. Be the first to link yours!</p>
            </div>
        @else
            <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($linkedRepos as $repo)
                <div class="rounded-2xl border border-brand-border bg-brand-card/20 p-6 backdrop-blur transition-all duration-300 hover:border-brand-accent/40 hover:bg-brand-card/40">
                    <div class="flex flex-col h-full justify-between">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-xs text-brand-muted">
                                <span>Linked by {{ $repo->user->name }}</span>
                                <span>{{ $repo->created_at->format('M d, Y') }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-brand-text hover:text-brand-accent transition">
                                <a href="{{ $repo->repo_url }}" target="_blank" rel="noopener noreferrer">{{ $repo->title }}</a>
                            </h3>
                            <p class="text-xs text-brand-muted leading-relaxed line-clamp-3">
                                {{ $repo->description }}
                            </p>
                        </div>
                        <div class="mt-5 pt-4 border-t border-brand-border/40 flex items-center justify-between text-xs font-semibold text-brand-muted text-brand-text">
                            <span class="truncate max-w-[160px] font-mono text-[10px] text-brand-muted/70">{{ parse_url($repo->repo_url, PHP_URL_PATH) }}</span>
                            <a href="{{ $repo->repo_url }}" target="_blank" rel="noopener noreferrer" class="text-brand-accent hover:underline flex items-center gap-1">
                                <span>GitHub</span>
                                <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 0C3.58 0 0 3.58 0 8C0 11.54 2.29 14.53 5.47 15.59C5.87 15.66 6.02 15.42 6.02 15.21C6.02 15.02 6.01 14.39 6.01 13.72C4 14.09 3.48 13.23 3.32 12.78C3.23 12.55 2.84 11.84 2.5 11.65C2.22 11.5 1.82 11.13 2.49 11.12C3.12 11.11 3.57 11.7 3.72 11.94C4.44 13.15 5.59 12.81 6.05 12.6C6.12 12.08 6.33 11.73 6.56 11.53C4.78 11.33 2.92 10.64 2.92 7.58C2.92 6.71 3.23 5.99 3.74 5.43C3.66 5.23 3.38 4.41 3.82 3.31C3.82 3.31 4.49 3.1 6.02 4.13C6.66 3.95 7.34 3.86 8.02 3.86C8.7 3.86 9.38 3.95 10.02 4.13C11.55 3.09 12.22 3.31 12.22 3.31C12.66 4.41 12.38 5.23 12.3 5.43C12.81 5.99 13.12 6.7 13.12 7.58C13.12 10.65 11.25 11.33 9.47 11.53C9.76 11.78 10.01 12.26 10.01 13.01C10.01 14.08 10 14.94 10 15.21C10 15.42 10.15 15.67 10.55 15.59C13.71 14.53 16 11.53 16 8C16 3.58 12.42 0 8 0Z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $linkedRepos->appends(request()->except('repos_page'))->links() }}
            </div>
        @endif
    </div>
</div>
</div>

<!-- Client-side Interactive Search & Filter Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('project-search');
    const filterButtons = document.querySelectorAll('.lang-filter-btn');
    const projectCards = document.querySelectorAll('.project-card');
    const emptyState = document.getElementById('empty-state');
    const clearFiltersBtn = document.getElementById('clear-filters');
    
    let activeLang = 'all';
    let searchQuery = '';

    // Search input handler
    searchInput.addEventListener('input', function(e) {
        searchQuery = e.target.value.toLowerCase().trim();
        filterProjects();
    });

    // Keyboard shortcut (CMD/CTRL + K to focus search)
    document.addEventListener('keydown', function(e) {
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
        }
    });

    // Filter buttons click handler
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active state classes
            filterButtons.forEach(b => {
                b.classList.remove('active', 'border-brand-accent/30', 'bg-brand-accent/10', 'text-brand-accent');
                b.classList.add('border-brand-border', 'bg-brand-card/50', 'text-brand-muted');
            });
            
            this.classList.remove('border-brand-border', 'bg-brand-card/50', 'text-brand-muted');
            this.classList.add('active', 'border-brand-accent/30', 'bg-brand-accent/10', 'text-brand-accent');
            
            activeLang = this.getAttribute('data-lang');
            filterProjects();
        });
    });

    // Clear filters handler
    clearFiltersBtn.addEventListener('click', function() {
        searchInput.value = '';
        searchQuery = '';
        filterButtons[0].click(); // trigger 'all' click
    });

    // Filter projects function
    function filterProjects() {
        let visibleCount = 0;

        projectCards.forEach(card => {
            const name = card.getAttribute('data-name');
            const description = card.getAttribute('data-description');
            const language = card.getAttribute('data-language');
            const topics = card.getAttribute('data-topics');
            
            // Checks
            const matchesSearch = searchQuery === '' || 
                name.includes(searchQuery) || 
                description.includes(searchQuery) ||
                topics.includes(searchQuery);
                
            const matchesLang = activeLang === 'all' || language === activeLang;

            if (matchesSearch && matchesLang) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Show/hide empty state
        if (visibleCount === 0) {
            emptyState.classList.remove('hidden');
        } else {
            emptyState.classList.add('hidden');
        }
    }
});
</script>
@endsection
