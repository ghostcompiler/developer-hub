@extends('layouts.app')

@section('title', $blog->title . ' - Blog')
@section('meta_description', $blog->summary)

@section('meta_keywords')
@php
    $title = $blog->title;
    $keywords = [
        $title,
        "ghostcompiler",
        "ghost compiler",
        "ghostcompiler blog",
        "ghost compiler blog",
        "developer blog",
        "software engineering"
    ];
    echo implode(', ', array_unique(array_filter(array_map('trim', $keywords))));
@endphp
@endsection

@section('content')
<!-- Blog Header Banner -->
<div class="border-b border-brand-border bg-brand-card/10 py-12 backdrop-blur transition-colors">
    <div class="container mx-auto px-6 md:px-10">
        <!-- Breadcrumbs -->
        <nav class="flex text-xs font-bold uppercase tracking-wider text-brand-muted" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('blogs.index') }}" class="transition hover:text-brand-accent">Blog</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="h-3 w-3 text-brand-muted/70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                        <span class="ml-2 text-brand-text truncate max-w-xs sm:max-w-sm">{{ $blog->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="mt-6 space-y-4">
            <div class="flex items-center gap-3 text-sm text-brand-muted">
                <span>By <span class="font-semibold text-brand-text">{{ $blog->user->name }}</span></span>
                <span>•</span>
                <span>{{ $blog->created_at->format('M d, Y') }}</span>
                @if($blog->status !== 'approved')
                    <span class="rounded bg-amber-500/10 px-2 py-0.5 text-xs font-semibold text-amber-500 border border-amber-500/20">Pending Admin Approval</span>
                @endif
            </div>

            <h1 class="text-3xl font-extrabold tracking-tight text-brand-text sm:text-4.5xl leading-tight max-w-4xl">
                {{ $blog->title }}
            </h1>
            <p class="max-w-3xl text-base md:text-lg text-brand-muted leading-relaxed">
                {{ $blog->summary }}
            </p>
        </div>
    </div>
</div>

<!-- Main Split  max-w-[1400px]-->
<div class="container mx-auto px-6 py-12 md:px-10">
    <div class="flex flex-col lg:flex-row lg:gap-12 xl:gap-16">
        
        <!-- Left: Blog Body -->
        <article class="flex-grow min-w-0 py-2">
            <div class="prose prose-invert max-w-none markdown-body transition-colors">
                {!! $blog->formatted_content !!}
            </div>
        </article>

        <!-- Right Sidebar: Recent Posts -->
        @if($recentBlogs->isNotEmpty())
        <aside class="hidden w-80 shrink-0 lg:block border-l border-brand-border pl-8 xl:pl-12 transition-colors">
            <div class="sticky top-28 space-y-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-brand-text">Recent Articles</h3>
                <nav class="space-y-4">
                    @foreach($recentBlogs as $recent)
                        <div class="space-y-1">
                            <span class="text-[10px] uppercase font-bold tracking-wider text-brand-muted/70">{{ $recent->created_at->format('M d, Y') }}</span>
                            <a href="{{ route('blogs.show', $recent->slug) }}" class="block text-sm font-semibold text-brand-text hover:text-brand-accent transition leading-snug">
                                {{ $recent->title }}
                            </a>
                        </div>
                    @endforeach
                </nav>
            </div>
        </aside>
        @endif

    </div>
</div>
@endsection
