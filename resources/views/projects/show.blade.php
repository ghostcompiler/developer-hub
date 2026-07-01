@extends('layouts.app')

@section('title', ucwords(str_replace('-', ' ', $project->name)) . ' - Documentation & Integration Guide')
@section('meta_description', 'Complete integration and documentation for ' . ucwords(str_replace('-', ' ', $project->name)) . '. Learn how to install, configure, and use this package in production.')

@section('meta_keywords')
@php
    $name = $project->name;
    $spaceName = str_replace(['-', '_'], ' ', $name);
    $togetherName = str_replace(['-', '_'], '', $name);
    $lang = $project->language;
    
    $keywords = [
        $name,
        $spaceName,
        $togetherName,
        "ghostcompiler",
        "ghost compiler",
        "ghostcompiler " . $name,
        "ghost compiler " . $spaceName,
        $name . " documentation",
        $spaceName . " tutorial",
        $name . " github",
        $name . " " . $lang,
        "open source " . $spaceName
    ];
    
    if ($project->topics && is_array($project->topics)) {
        $keywords = array_merge($keywords, $project->topics);
    }
    
    if (isset($activePage)) {
        $keywords[] = $activePage->title;
        $keywords[] = $activePage->title . " " . $spaceName;
    }
    
    echo implode(', ', array_unique(array_filter(array_map('trim', $keywords))));
@endphp
@endsection

@section('schema')
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context' => 'https://schema.org',
    '@type' => ['SoftwareApplication', 'CodeRepository'],
    'name' => ucwords(str_replace('-', ' ', $project->name)),
    'description' => $project->description,
    'applicationCategory' => 'DeveloperApplication',
    'operatingSystem' => 'All',
    'programmingLanguage' => $project->language,
    'codeRepository' => $project->github_url,
    'url' => $project->homepage_url ?: null,
    'downloadUrl' => "https://github.com/ghostcompiler/{$project->name}/archive/refs/heads/{$project->default_branch}.zip",
    'author' => [
        '@type' => 'Person',
        'name' => 'ghostcompiler'
    ],
    'starRating' => [
        '@type' => 'Rating',
        'ratingValue' => (string)$project->stars_count,
        'bestRating' => '5',
        'worstRating' => '1',
        'ratingCount' => (string)$project->stars_count
    ]
]), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endsection

@section('content')
<!-- Hero Repository Banner -->
<div class="border-b border-brand-border bg-brand-card/10 py-12 backdrop-blur transition-colors">
    <div class="container mx-auto px-6 md:px-6">
        <!-- Breadcrumbs -->
        <nav class="flex text-xs font-bold uppercase tracking-wider text-brand-muted" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('projects.index') }}" class="transition hover:text-brand-accent">Projects</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="h-3 w-3 text-brand-muted/70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                        <span class="ml-2 text-brand-text">{{ ucwords(str_replace('-', ' ', $project->name)) }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header Content Grid -->
        <div class="mt-6 flex flex-col justify-between gap-8 lg:flex-row lg:items-center">
            <div class="space-y-3">
                <div class="flex flex-wrap items-center gap-2.5">
                    <h1 class="text-3xl font-extrabold tracking-tight text-brand-text sm:text-4.5xl">
                        {{ ucwords(str_replace('-', ' ', $project->name)) }}
                    </h1>
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-brand-bg border border-brand-border px-3 py-1 text-xs font-semibold text-brand-text">
                        {{ $project->language }}
                    </span>
                    @if($project->license_name)
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-brand-bg border border-brand-border px-3 py-1 text-xs font-semibold text-brand-text">
                        {{ $project->license_name }}
                    </span>
                    @endif
                </div>
                <p class="max-w-4xl text-sm md:text-base text-brand-muted leading-relaxed">
                    {{ $project->description }}
                </p>
            </div>

            <!-- Header Actions -->
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ $project->github_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-xl border border-brand-border bg-brand-card/50 px-5 py-3 text-sm font-semibold text-brand-text transition hover:border-brand-accent hover:text-brand-accent">
                    <svg class="h-4.5 w-4.5 fill-current" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 0C3.58 0 0 3.58 0 8C0 11.54 2.29 14.53 5.47 15.59C5.87 15.66 6.02 15.42 6.02 15.21C6.02 15.02 6.01 14.39 6.01 13.72C4 14.09 3.48 13.23 3.32 12.78C3.23 12.55 2.84 11.84 2.5 11.65C2.22 11.5 1.82 11.13 2.49 11.12C3.12 11.11 3.57 11.7 3.72 11.94C4.44 13.15 5.59 12.81 6.05 12.6C6.12 12.08 6.33 11.73 6.56 11.53C4.78 11.33 2.92 10.64 2.92 7.58C2.92 6.71 3.23 5.99 3.74 5.43C3.66 5.23 3.38 4.41 3.82 3.31C3.82 3.31 4.49 3.1 6.02 4.13C6.66 3.95 7.34 3.86 8.02 3.86C8.7 3.86 9.38 3.95 10.02 4.13C11.55 3.09 12.22 3.31 12.22 3.31C12.66 4.41 12.38 5.23 12.3 5.43C12.81 5.99 13.12 6.7 13.12 7.58C13.12 10.65 11.25 11.33 9.47 11.53C9.76 11.78 10.01 12.26 10.01 13.01C10.01 14.08 10 14.94 10 15.21C10 15.42 10.15 15.67 10.55 15.59C13.71 14.53 16 11.53 16 8C16 3.58 12.42 0 8 0Z"/>
                    </svg>
                    <span>GitHub</span>
                </a>
                
                @php
                    $zipUrl = "https://github.com/ghostcompiler/{$project->name}/archive/refs/heads/{$project->default_branch}.zip";
                @endphp
                <a href="{{ $zipUrl }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-accent px-5 py-3 text-sm font-semibold text-white transition hover:bg-brand-accent-hover hover:scale-[1.02] shadow-md shadow-brand-accent/10">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>Download ZIP</span>
                </a>
            </div>
        </div>

        <!-- Horizontal Stats Panel (No more right side congestion!) -->
        <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-4 border-t border-brand-border pt-8 transition-colors">
            <!-- Stars -->
            <div class="rounded-xl border border-brand-border bg-brand-card/45 p-4.5 flex items-center gap-3.5 shadow-sm">
                <div class="rounded-lg bg-amber-500/10 p-2 text-amber-500">
                    <svg class="h-5.5 w-5.5 fill-current" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                    </svg>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-brand-muted uppercase tracking-wider">Stars</dt>
                    <dd class="text-xl font-bold text-brand-text">{{ $project->stars_count }}</dd>
                </div>
            </div>
            <!-- Forks -->
            <div class="rounded-xl border border-brand-border bg-brand-card/45 p-4.5 flex items-center gap-3.5 shadow-sm">
                <div class="rounded-lg bg-blue-500/10 p-2 text-blue-500">
                    <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                    </svg>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-brand-muted uppercase tracking-wider">Forks</dt>
                    <dd class="text-xl font-bold text-brand-text">{{ $project->forks_count }}</dd>
                </div>
            </div>
            <!-- Downloads -->
            <div class="rounded-xl border border-brand-border bg-brand-card/45 p-4.5 flex items-center gap-3.5 shadow-sm">
                <div class="rounded-lg bg-brand-accent/10 p-2 text-brand-accent">
                    <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-brand-muted uppercase tracking-wider">Downloads</dt>
                    <dd class="text-xl font-bold text-brand-text">
                        {{ $project->downloads_count > 0 ? number_format($project->downloads_count) : 'N/A' }}
                    </dd>
                </div>
            </div>
            <!-- Open Issues -->
            <div class="rounded-xl border border-brand-border bg-brand-card/45 p-4.5 flex items-center gap-3.5 shadow-sm">
                <div class="rounded-lg bg-rose-500/10 p-2 text-rose-500">
                    <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-brand-muted uppercase tracking-wider">Open Issues</dt>
                    <dd class="text-xl font-bold text-brand-text">{{ $project->open_issues_count }}</dd>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Split Container (Spacious Layout) -->
<div id="project-main-container" class="container mx-auto px-6 py-12 md:px-6">
    <div id="project-content-layout" class="flex flex-col lg:flex-row lg:gap-8 xl:gap-12 2xl:gap-16">
        
        <!-- Left Sidebar: Packages Navigation -->
        <aside id="docs-nav-sidebar" class="hidden w-64 shrink-0 lg:block border-r border-brand-border pr-6 xl:w-72 xl:pr-10 2xl:pr-12 transition-colors">
            @if($project->pages->isNotEmpty())
            <div class="mb-8">
                <h2 class="text-xs font-bold uppercase tracking-wider text-brand-text">Documentation</h2>
                <nav class="mt-4 space-y-1.5">
                    <a href="{{ route('projects.show', $project->slug) }}" class="group flex items-center justify-between rounded-xl px-4 py-3 text-sm font-semibold transition {{ !isset($activePage) ? 'bg-brand-card text-brand-text border-brand-border' : 'text-brand-muted hover:bg-brand-card hover:text-brand-text border-transparent' }} border">
                        <span>Overview</span>
                    </a>
                    @foreach($project->pages as $p)
                    <a href="{{ route('projects.page', [$project->slug, $p->slug]) }}" class="group flex items-center justify-between rounded-xl px-4 py-3 text-sm font-semibold transition {{ (isset($activePage) && $activePage->id === $p->id) ? 'bg-brand-card text-brand-text border-brand-border' : 'text-brand-muted hover:bg-brand-card hover:text-brand-text border-transparent' }} border">
                        <span class="truncate pr-2">{{ $p->title }}</span>
                    </a>
                    @endforeach
                </nav>
            </div>
            @endif

            <h2 class="text-xs font-bold uppercase tracking-wider text-brand-text">Other Packages</h2>
            <nav class="mt-6 space-y-1.5">
                @foreach($otherProjects as $other)
                <a href="{{ route('projects.show', $other->slug) }}" class="group flex items-center justify-between rounded-xl px-4 py-3 text-sm font-semibold text-brand-muted transition hover:bg-brand-card hover:text-brand-text border border-transparent hover:border-brand-border">
                    <span class="truncate pr-2">{{ ucwords(str_replace('-', ' ', $other->name)) }}</span>
                    <span class="inline-flex items-center gap-0.5 text-xs text-brand-muted/70 group-hover:text-amber-500">
                        <svg class="h-3 w-3 fill-current" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                        </svg>
                        {{ $other->stars_count }}
                    </span>
                </a>
                @endforeach
            </nav>
        </aside>

        <!-- Center Column: Docs & Code Explorer tabs -->
        <article id="project-main-article" class="grow min-w-0 py-2 space-y-6">
            @if(!isset($activePage))
            <!-- Tabs Header -->
            <div class="flex border-b border-brand-border pb-px">
                <button type="button" id="tab-btn-docs" onclick="toggleMainTab('docs')" class="border-b-2 {{ isset($activeFilePath) ? 'border-transparent font-semibold text-brand-muted hover:text-brand-text' : 'border-brand-accent font-bold text-brand-text' }} px-5 py-3 text-sm transition cursor-pointer">
                    Documentation
                </button>
                <button type="button" id="tab-btn-code" onclick="toggleMainTab('code')" class="border-b-2 {{ isset($activeFilePath) ? 'border-brand-accent font-bold text-brand-text' : 'border-transparent font-semibold text-brand-muted hover:text-brand-text' }} px-5 py-3 text-sm transition cursor-pointer">
                    Code Explorer
                </button>
            </div>
            @endif

            <!-- 1. Documentation View -->
            <div id="main-tab-docs" class="tab-pane {{ isset($activeFilePath) ? 'hidden' : '' }}">
                @if(isset($activePage))
                    <div class="prose prose-invert max-w-none markdown-body transition-colors">
                        {!! $activePage->formatted_content_html !!}
                    </div>
                @else
                    @if($project->readme_html)
                        <div class="prose prose-invert max-w-none markdown-body transition-colors">
                            {!! $project->formatted_readme_html !!}
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-brand-border p-12 text-center transition-colors">
                            <p class="text-brand-muted">Documentation README couldn't be loaded from GitHub. Please check back later.</p>
                        </div>
                    @endif
                @endif
            </div>

            <!-- 2. Code Explorer View -->
            @if(!isset($activePage))
            <div id="main-tab-code" class="tab-pane {{ isset($activeFilePath) ? '' : 'hidden' }} space-y-4">
                <div class="rounded-2xl border border-brand-border bg-brand-card/35 overflow-hidden">
                    <div class="flex flex-col gap-3 border-b border-brand-border bg-brand-bg/40 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-2 text-xs text-brand-muted">
                            <span class="rounded-md border border-brand-border bg-brand-card/60 px-2 py-1 font-semibold text-brand-text">Files</span>
                            <span id="repo-branch-label" class="rounded-md border border-brand-border bg-brand-card/30 px-2 py-1 font-mono">main</span>
                        </div>
                        <div class="w-full sm:max-w-xs">
                            <input
                                id="file-search-input"
                                type="search"
                                placeholder="Go to file..."
                                class="w-full rounded-lg border border-brand-border bg-brand-bg px-3 py-2 text-xs text-brand-text outline-none transition focus:border-brand-accent"
                            >
                        </div>
                    </div>

                    <div class="grid h-[calc(100vh-14rem)] min-h-[640px] grid-cols-1 lg:grid-cols-12">
                    <!-- Left Folder Tree Panel -->
                    <div class="lg:col-span-4 xl:col-span-3 border-r border-brand-border bg-brand-bg/20 p-4 overflow-y-auto">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-brand-text mb-3">Repository Files</h4>
                        <div id="file-search-results" class="mb-3 hidden rounded-lg border border-brand-border bg-brand-bg/80 p-2 text-xs"></div>
                        <div id="files-tree" class="space-y-1 text-xs text-brand-muted">
                            <div class="py-2 text-center text-brand-muted/70">Loading file structure...</div>
                        </div>
                    </div>
                    
                    <!-- Right Code Preview Panel -->
                    <div class="lg:col-span-8 xl:col-span-9 p-4 bg-brand-bg/40 overflow-y-auto flex flex-col min-h-0">
                        <div id="code-content-placeholder" class="{{ isset($fileContent) ? 'hidden' : '' }} text-center py-12 text-brand-muted/70 space-y-2">
                            <svg class="mx-auto h-8 w-8 text-brand-muted/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />
                            </svg>
                            <p class="text-sm font-semibold">Select a file from the repository tree to inspect its code.</p>
                        </div>
                        <div id="code-content-viewer" class="{{ isset($fileContent) ? 'flex' : 'hidden' }} min-h-0 flex-1 flex-col">
                            <div class="flex items-center justify-between border-b border-brand-border/40 pb-2 mb-3 text-xs text-brand-muted">
                                <span id="current-filename" class="font-mono font-semibold text-brand-text">{{ $activeFilePath ?? '' }}</span>
                                <button type="button" onclick="copyCurrentCode()" class="font-bold text-brand-accent hover:underline cursor-pointer">Copy Code</button>
                            </div>
                            <pre class="flex-1 min-h-0 rounded-xl overflow-auto border border-brand-border/60 bg-brand-bg/70 p-4 text-[12px] leading-relaxed"><code id="code-block" class="hljs block min-w-max whitespace-pre {{ isset($activeFilePath) ? pathinfo($activeFilePath, PATHINFO_EXTENSION) : '' }}">{{ $fileContent ?? '' }}</code></pre>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            @endif
        </article>

        <!-- Right Sidebar: TOC & Installation (Sticky on scroll) -->
        <aside id="meta-sidebar" class="hidden w-64 shrink-0 xl:block border-l border-brand-border pl-6 2xl:w-72 2xl:pl-12 transition-colors">
            <div class="sticky top-28 space-y-8">
                <!-- Table of Contents -->
                @if(!empty($toc))
                <div class="space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-brand-text">On This Page</h3>
                    <nav class="toc-nav space-y-1.5 text-sm border-l-2 border-brand-border/30 pl-3 transition-colors">
                        @foreach($toc as $item)
                            <a href="#{{ $item['anchor'] }}" data-toc-link="{{ $item['anchor'] }}" class="toc-link block rounded-r-lg border-l-2 border-transparent py-1.5 pl-3 pr-3 transition hover:border-brand-accent hover:bg-brand-card/40 hover:text-brand-accent @if($item['level'] === 3) ml-3 text-xs @else font-semibold @endif">
                                {{ $item['text'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>
                @endif
            </div>
        </aside>

    </div>
</div>

@if(!isset($activePage))
<script>
    const initialFilePath = @json($activeFilePath ?? null);
    let treeLoaded = false;
    let currentCodeText = '';
    let allRepoItems = [];
    let currentBranch = 'main';

    function toggleMainTab(tabName) {
        const docsSidebar = document.getElementById('docs-nav-sidebar');
        const metaSidebar = document.getElementById('meta-sidebar');
        const mainContainer = document.getElementById('project-main-container');

        document.querySelectorAll('.tab-pane').forEach(el => el.classList.add('hidden'));
        document.getElementById('main-tab-' + tabName).classList.remove('hidden');
        
        // Reset buttons
        document.getElementById('tab-btn-docs').className = "border-b-2 px-5 py-3 text-sm transition cursor-pointer " + 
            (tabName === 'docs' ? 'border-brand-accent font-bold text-brand-text' : 'border-transparent font-semibold text-brand-muted hover:text-brand-text');
        document.getElementById('tab-btn-code').className = "border-b-2 px-5 py-3 text-sm transition cursor-pointer " + 
            (tabName === 'code' ? 'border-brand-accent font-bold text-brand-text' : 'border-transparent font-semibold text-brand-muted hover:text-brand-text');

        // Full-width workspace feel for the code explorer tab
        if (tabName === 'code') {
            if (docsSidebar) docsSidebar.style.display = 'none';
            if (metaSidebar) metaSidebar.style.display = 'none';
            if (mainContainer) mainContainer.style.maxWidth = 'none';
        } else {
            if (docsSidebar) docsSidebar.style.display = '';
            if (metaSidebar) metaSidebar.style.display = '';
            if (mainContainer) mainContainer.style.maxWidth = '';
        }
        
        if (tabName === 'code' && !treeLoaded) {
            loadRepoTree();
        }
    }

    function loadRepoTree() {
        const treeContainer = document.getElementById('files-tree');
        treeContainer.innerHTML = '<div class="py-2 text-center text-brand-muted/70 animate-pulse">Loading file structure...</div>';
        
        fetch('{{ route("projects.tree", $project->slug) }}')
            .then(res => {
                if (!res.ok) throw new Error('API limit reached or token not configured.');
                return res.json();
            })
            .then(data => {
                treeLoaded = true;
                allRepoItems = data.tree || [];
                currentBranch = data.default_branch || 'main';
                const branchLabel = document.getElementById('repo-branch-label');
                if (branchLabel) branchLabel.textContent = currentBranch;
                renderTree(data.tree, data.default_branch);
                initFileSearch();
            })
            .catch(err => {
                treeContainer.innerHTML = `<div class="py-2 text-center text-rose-500 font-semibold">${err.message}</div>`;
            });
    }

    function getRawFileUrl(path) {
        return `https://raw.githubusercontent.com/ghostcompiler/{{ $project->name }}/${currentBranch}/${path}`;
    }

    function initFileSearch() {
        const searchInput = document.getElementById('file-search-input');
        if (!searchInput || searchInput.dataset.bound === '1') return;

        searchInput.dataset.bound = '1';
        searchInput.addEventListener('input', () => {
            updateFileSearchResults(searchInput.value.trim().toLowerCase());
        });
    }

    function updateFileSearchResults(query) {
        const resultsBox = document.getElementById('file-search-results');
        if (!resultsBox) return;

        if (!query) {
            resultsBox.classList.add('hidden');
            resultsBox.innerHTML = '';
            return;
        }

        const matches = allRepoItems
            .filter(item => item.type === 'blob' && item.path.toLowerCase().includes(query))
            .slice(0, 40);

        resultsBox.classList.remove('hidden');

        if (!matches.length) {
            resultsBox.innerHTML = '<p class="px-2 py-1 text-brand-muted">No matching files.</p>';
            return;
        }

        const list = document.createElement('ul');
        list.className = 'space-y-1';

        matches.forEach(item => {
            const li = document.createElement('li');
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'w-full rounded px-2 py-1 text-left font-mono text-[11px] text-brand-muted hover:bg-brand-card/60 hover:text-brand-text';
            btn.textContent = item.path;
            btn.addEventListener('click', () => {
                viewFileCode(getRawFileUrl(item.path), item.path);
            });
            li.appendChild(btn);
            list.appendChild(li);
        });

        resultsBox.innerHTML = '';
        resultsBox.appendChild(list);
    }

    function renderTree(items, branch) {
        const treeContainer = document.getElementById('files-tree');
        treeContainer.innerHTML = '';
        
        if (items.length === 0) {
            treeContainer.innerHTML = '<div class="py-2 text-center text-brand-muted/70">No files found.</div>';
            return;
        }
        
        // Sort: folders first, then alphabetical
        items.sort((a, b) => {
            if (a.type !== b.type) {
                return a.type === 'tree' ? -1 : 1;
            }
            return a.path.localeCompare(b.path);
        });

        // Helper: safely escape a string for use in a DOM id attribute
        function safeDomId(str) {
            return str.replace(/[^a-zA-Z0-9-_]/g, '-');
        }

        const rootList = document.createElement('ul');
        rootList.className = "space-y-1.5 pl-1";
        
        const folders = {};
        
        items.forEach(item => {
            const parts = item.path.split('/');
            const fileName = parts.pop();
            const parentPath = parts.join('/');
            
            const li = document.createElement('li');
            li.className = "py-0.5";
            
            if (item.type === 'tree') {
                // --- Folder node (safe DOM construction) ---
                const div = document.createElement('div');
                div.className = "flex items-center gap-1.5 font-semibold text-brand-text select-none cursor-pointer hover:text-brand-accent";

                const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                svg.setAttribute('class', 'h-4 w-4 shrink-0 text-amber-500 fill-current');
                svg.setAttribute('viewBox', '0 0 20 20');
                const pathEl = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                pathEl.setAttribute('d', 'M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z');
                svg.appendChild(pathEl);
                div.appendChild(svg);

                const nameSpan = document.createElement('span');
                nameSpan.textContent = fileName;  // Safe — no innerHTML
                div.appendChild(nameSpan);

                const subUl = document.createElement('ul');
                subUl.className = "pl-4 mt-1.5 space-y-1.5 " + (initialFilePath && initialFilePath.startsWith(item.path + '/') ? "" : "hidden");
                subUl.id = "folder-" + safeDomId(item.path) + "-list";

                div.addEventListener('click', () => {
                    subUl.classList.toggle('hidden');
                });

                li.appendChild(div);
                li.appendChild(subUl);
                folders[item.path] = subUl;
            } else {
                // --- File node (safe DOM construction — no onclick injection) ---
                const rawUrl = `https://raw.githubusercontent.com/ghostcompiler/{{ $project->name }}/${branch}/${item.path}`;
                
                const a = document.createElement('a');
                a.href = `/projects/{{ $project->slug }}/files/${item.path}`;
                a.className = "flex items-center gap-1.5 " + (initialFilePath && initialFilePath === item.path ? "text-brand-accent font-bold" : "text-brand-muted") + " hover:text-brand-accent cursor-pointer truncate";
                // Store data safely in dataset — no inline onclick
                a.dataset.rawUrl = rawUrl;
                a.dataset.filePath = item.path;
                a.addEventListener('click', function (e) {
                    e.preventDefault();
                    history.pushState(null, '', this.href);
                    viewFileCode(this.dataset.rawUrl, this.dataset.filePath);
                });

                const fileSvg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                fileSvg.setAttribute('class', 'h-4 w-4 shrink-0 text-brand-muted/70');
                fileSvg.setAttribute('fill', 'none');
                fileSvg.setAttribute('viewBox', '0 0 24 24');
                fileSvg.setAttribute('stroke', 'currentColor');
                fileSvg.setAttribute('stroke-width', '2');
                const filePathEl = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                filePathEl.setAttribute('stroke-linecap', 'round');
                filePathEl.setAttribute('stroke-linejoin', 'round');
                filePathEl.setAttribute('d', 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z');
                fileSvg.appendChild(filePathEl);
                a.appendChild(fileSvg);

                const fileSpan = document.createElement('span');
                fileSpan.textContent = fileName;  // Safe — no innerHTML
                a.appendChild(fileSpan);

                li.appendChild(a);
            }
            
            if (parentPath === '') {
                rootList.appendChild(li);
            } else {
                const parentList = folders[parentPath];
                if (parentList) {
                    parentList.appendChild(li);
                } else {
                    rootList.appendChild(li);
                }
            }
        });
        
        treeContainer.appendChild(rootList);
    }

    function viewFileCode(url, path) {
        const placeholder = document.getElementById('code-content-placeholder');
        const viewer = document.getElementById('code-content-viewer');
        const filenameLabel = document.getElementById('current-filename');
        const codeBlock = document.getElementById('code-block');
        
        placeholder.classList.add('hidden');
        viewer.classList.remove('hidden');
        viewer.classList.add('flex');
        filenameLabel.textContent = path;
        codeBlock.textContent = 'Loading file contents...';
        if (typeof hljs !== 'undefined') {
            codeBlock.removeAttribute('data-highlighted');
            hljs.highlightElement(codeBlock);
        }
        
        fetch(url)
            .then(res => {
                if (!res.ok) throw new Error('Could not fetch file contents.');
                return res.text();
            })
            .then(text => {
                currentCodeText = text;
                codeBlock.textContent = text;
                
                // Auto detect language
                const ext = path.split('.').pop().toLowerCase();
                codeBlock.className = 'hljs block min-w-max whitespace-pre ' + ext;
                if (typeof hljs !== 'undefined') {
                    codeBlock.removeAttribute('data-highlighted');
                    hljs.highlightElement(codeBlock);
                }
            })
            .catch(err => {
                codeBlock.textContent = 'Error loading file: ' + err.message;
                if (typeof hljs !== 'undefined') {
                    codeBlock.removeAttribute('data-highlighted');
                    hljs.highlightElement(codeBlock);
                }
            });
    }

    function copyCurrentCode() {
        if (!currentCodeText) return;
        navigator.clipboard.writeText(currentCodeText)
            .then(() => alert('Code copied to clipboard!'))
            .catch(() => alert('Failed to copy code.'));
    }

    document.addEventListener('DOMContentLoaded', () => {
        // If initialFilePath is set, switch to 'code' tab automatically!
        if (initialFilePath) {
            toggleMainTab('code');
            const codeBlock = document.getElementById('code-block');
            if (codeBlock) {
                currentCodeText = codeBlock.textContent;
            }
        }

        const tocLinks = Array.from(document.querySelectorAll('[data-toc-link]'));
        if (!tocLinks.length) return;

        const headings = tocLinks
            .map(link => document.getElementById(link.dataset.tocLink))
            .filter(Boolean);

        function setActiveToc(id) {
            tocLinks.forEach(link => {
                const isActive = link.dataset.tocLink === id;
                link.classList.toggle('toc-link-active', isActive);
                link.setAttribute('aria-current', isActive ? 'true' : 'false');
            });
        }

        const observer = new IntersectionObserver((entries) => {
            const visible = entries
                .filter(entry => entry.isIntersecting)
                .sort((a, b) => a.boundingClientRect.top - b.boundingClientRect.top)[0];

            if (visible) {
                setActiveToc(visible.target.id);
            }
        }, {
            rootMargin: '-96px 0px -65% 0px',
            threshold: 0.01,
        });

        headings.forEach(heading => observer.observe(heading));
        if (headings[0]) setActiveToc(headings[0].id);
    });
</script>
@endif
@endsection

@section('mobile_sidebar')
<!-- Documentation & Packages Navigation (visible inside mobile drawer) -->
<div class="space-y-6 pt-4 border-t border-brand-border/60">
    @if($project->pages->isNotEmpty())
    <div>
        <p class="text-[10px] font-bold uppercase tracking-widest text-brand-muted/50 mb-3 px-3">Documentation</p>
        <nav class="space-y-1">
            <a href="{{ route('projects.show', $project->slug) }}" class="block rounded-lg px-3 py-2 text-xs font-semibold {{ !isset($activePage) ? 'bg-brand-card text-brand-text font-bold' : 'text-brand-muted hover:text-brand-text hover:bg-brand-card/30' }} transition">
                Overview
            </a>
            @foreach($project->pages as $p)
            <a href="{{ route('projects.page', [$project->slug, $p->slug]) }}" class="block rounded-lg px-3 py-2 text-xs font-semibold {{ (isset($activePage) && $activePage->id === $p->id) ? 'bg-brand-card text-brand-text font-bold' : 'text-brand-muted hover:text-brand-text hover:bg-brand-card/30' }} transition">
                {{ $p->title }}
            </a>
            @endforeach
        </nav>
    </div>
    @endif

    @if(!empty($toc))
    <div>
        <p class="text-[10px] font-bold uppercase tracking-widest text-brand-muted/50 mb-3 px-3">On This Page</p>
        <nav class="toc-nav space-y-1 border-l-2 border-brand-border/30 pl-3">
            @foreach($toc as $item)
            <a href="#{{ $item['anchor'] }}" data-toc-link="{{ $item['anchor'] }}" class="toc-link block rounded-r-lg border-l-2 border-transparent py-1.5 pl-3 pr-3 text-xs transition hover:border-brand-accent hover:bg-brand-card/40 hover:text-brand-accent @if($item['level'] === 3) ml-2 @else font-semibold @endif">
                {{ $item['text'] }}
            </a>
            @endforeach
        </nav>
    </div>
    @endif

    <div>
        <p class="text-[10px] font-bold uppercase tracking-widest text-brand-muted/50 mb-3 px-3">Other Packages</p>
        <nav class="space-y-1">
            @foreach($otherProjects as $other)
            <a href="{{ route('projects.show', $other->slug) }}" class="block rounded-lg px-3 py-2 text-xs text-brand-muted hover:text-brand-text hover:bg-brand-card/30 transition">
                {{ ucwords(str_replace('-', ' ', $other->name)) }}
            </a>
            @endforeach
        </nav>
    </div>
    <!-- SEO File Indexing List (Crawlable by Search Engines) -->
    <div style="display:none;" aria-hidden="true" class="sr-only">
        <h3>{{ ucwords(str_replace('-', ' ', $project->name)) }} Source Code Directory</h3>
        <ul>
            @if(isset($files) && count($files) > 0)
                @foreach($files as $file)
                    @if($file['type'] === 'blob')
                        <li>
                            <a href="{{ route('projects.file', [$project->slug, $file['path']]) }}">
                                {{ $file['path'] }}
                            </a>
                        </li>
                    @endif
                @endforeach
            @endif
        </ul>
    </div>
</div>
@endsection
