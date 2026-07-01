@extends('layouts.dashboard')

@section('title', 'Link Repository - Ghost Compiler')
@section('page-title', 'Link Repository')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="rounded-2xl border border-brand-border bg-brand-card/50 p-8 backdrop-blur transition-colors shadow-2xl">
        <div class="text-center space-y-2 mb-8">
            <h1 class="text-2xl font-bold text-brand-text">Link Your Repository</h1>
            <p class="text-sm text-brand-muted">Submit your GitHub project to index and make it search engine optimized.</p>
        </div>

        <form action="{{ route('repos.store') }}" method="POST" class="space-y-5">
            @csrf
            
            <div class="space-y-1.5">
                <label for="title" class="text-xs font-semibold uppercase tracking-wider text-brand-muted">Project Title</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="e.g. Laravel Hetzner Cloud SDK" required autofocus class="w-full rounded-xl border border-brand-border bg-brand-bg/60 px-4 py-3 text-brand-text placeholder-brand-muted outline-none ring-offset-brand-bg transition focus:border-brand-accent/50 focus:ring-2 focus:ring-brand-accent/20">
                @error('title')
                    <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="space-y-1.5">
                <label for="repo_url" class="text-xs font-semibold uppercase tracking-wider text-brand-muted">GitHub Repository URL</label>
                <input type="url" id="repo_url" name="repo_url" value="{{ old('repo_url') }}" placeholder="e.g. https://github.com/ghostcompiler/laravel-hetzner-cloud" required class="w-full rounded-xl border border-brand-border bg-brand-bg/60 px-4 py-3 text-brand-text placeholder-brand-muted outline-none ring-offset-brand-bg transition focus:border-brand-accent/50 focus:ring-2 focus:ring-brand-accent/20">
                @error('repo_url')
                    <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="space-y-1.5">
                <label for="description" class="text-xs font-semibold uppercase tracking-wider text-brand-muted">Project Description</label>
                <textarea id="description" name="description" rows="4" placeholder="Describe what this repository does. This description will be shown in lists and crawled by search engines." required class="w-full rounded-xl border border-brand-border bg-brand-bg/60 px-4 py-3 text-brand-text placeholder-brand-muted outline-none ring-offset-brand-bg transition focus:border-brand-accent/50 focus:ring-2 focus:ring-brand-accent/20">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-3">
                <button type="submit" class="rounded-xl bg-brand-accent px-6 py-3 text-sm font-semibold text-white transition hover:bg-brand-accent-hover hover:scale-[1.01] shadow-md shadow-brand-accent/10">
                    Link Repository
                </button>
                <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard', ['tab' => 'all-repos']) : route('dashboard', ['tab' => 'repos']) }}" class="rounded-xl border border-brand-border bg-brand-card/50 px-6 py-3 text-sm font-semibold text-brand-muted transition hover:text-brand-text hover:border-brand-accent">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
