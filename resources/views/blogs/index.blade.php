@extends('layouts.app')

@section('title', 'Developer Blog - Ghost Compiler')
@section('meta_description', 'Read guides, integration tips, and programming blogs on Laravel, Plesk, API integrations and custom utilities from the Ghost Compiler community.')

@section('content')
<div class="container mx-auto px-6 py-16 md:px-10">
    <div class="flex flex-col justify-between gap-6 md:flex-row md:items-center border-b border-brand-border pb-8">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tight text-brand-text sm:text-5xl">
                Developer Blog
            </h1>
            <p class="mt-3 text-lg text-brand-muted max-w-2xl">
                Tutorials, guides, and integration walk-throughs written by the developer community.
            </p>
        </div>
        
        @auth
            <a href="{{ route('blogs.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-accent px-5 py-3 text-sm font-semibold text-white transition hover:bg-brand-accent-hover hover:scale-[1.02] shadow-md shadow-brand-accent/10 shrink-0">
                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Write a Blog</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl border border-brand-border bg-brand-card/50 px-5 py-3 text-sm font-semibold text-brand-text transition hover:border-brand-accent hover:text-brand-accent shrink-0">
                <span>Sign in to Write Blog</span>
            </a>
        @endauth
    </div>

    @if(session('success'))
        <div class="mt-8 rounded-xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-sm text-brand-accent">
            {{ session('success') }}
        </div>
    @endif

    @if($blogs->isEmpty())
        <div class="mt-12 rounded-2xl border border-dashed border-brand-border p-16 text-center">
            <svg class="mx-auto h-12 w-12 text-brand-muted/70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
            <h3 class="mt-4 text-sm font-bold text-brand-text">No blogs published yet</h3>
            <p class="mt-2 text-sm text-brand-muted">Be the first to share an integration guide!</p>
            <div class="mt-6">
                <a href="{{ route('blogs.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-accent px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-accent-hover">
                    Write Blog
                </a>
            </div>
        </div>
    @else
        <div class="mt-12 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach($blogs as $blog)
                <article class="flex flex-col justify-between rounded-2xl border border-brand-border bg-brand-card/45 p-6 shadow-sm hover:border-brand-accent/50 hover:shadow-lg transition duration-300">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 text-xs text-brand-muted">
                            <span>By {{ $blog->user->name }}</span>
                            <span class="text-brand-border">•</span>
                            <span>{{ $blog->created_at->format('M d, Y') }}</span>
                        </div>
                        
                        <h2 class="text-xl font-bold leading-snug text-brand-text hover:text-brand-accent transition">
                            <a href="{{ route('blogs.show', $blog->slug) }}">{{ $blog->title }}</a>
                        </h2>
                        
                        <p class="text-sm text-brand-muted leading-relaxed line-clamp-3">
                            {{ $blog->summary }}
                        </p>
                    </div>

                    <div class="mt-6 pt-5 border-t border-brand-border flex items-center justify-between text-xs text-brand-muted">
                        @php
                            $wordCount = str_word_count(strip_tags($blog->content));
                            $readTime = max(1, ceil($wordCount / 200));
                        @endphp
                        <span>{{ $readTime }} min read</span>
                        
                        <a href="{{ route('blogs.show', $blog->slug) }}" class="font-bold text-brand-accent hover:underline flex items-center gap-1">
                            <span>Read Article</span>
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-12">
            {{ $blogs->links() }}
        </div>
    @endif
</div>
@endsection
