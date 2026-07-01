@extends('layouts.dashboard')

@section('title', 'Edit Blog - Ghost Compiler')
@section('page-title', 'Edit Blog Post')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="border-b border-brand-border pb-4 mb-6 flex justify-between items-center">
        <h1 class="text-lg font-bold text-brand-text">Edit Blog Post</h1>
        <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard', ['tab' => 'all-blogs']) : route('dashboard', ['tab' => 'blogs']) }}" class="text-xs font-semibold text-brand-muted hover:text-brand-text transition">&larr; Back to Dashboard</a>
    </div>

    @if($errors->any())
        <div class="mb-6 rounded-xl border border-rose-500/20 bg-rose-500/10 p-4 text-sm text-rose-500">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('dashboard.blogs.update', $blog->id) }}" method="POST" class="space-y-6">
        @csrf
        
        <div>
            <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-brand-muted mb-2">Blog Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $blog->title) }}" placeholder="e.g. How to use Laravel SDK" class="w-full rounded-lg border border-brand-border bg-brand-bg/60 px-3 py-2 text-sm text-brand-text outline-none focus:border-brand-accent/50 focus:ring-1 focus:ring-brand-accent/20" required>
        </div>

        <div>
            <label for="summary" class="block text-xs font-semibold uppercase tracking-wider text-brand-muted mb-2">Short Summary</label>
            <p class="text-[11px] text-brand-muted mb-2">Provide a brief 1-2 sentence overview of the post for search results.</p>
            <textarea id="summary" name="summary" rows="3" maxlength="500" placeholder="Describe what this tutorial covers..." class="w-full rounded-lg border border-brand-border bg-brand-bg/60 px-3 py-2 text-sm text-brand-text outline-none focus:border-brand-accent/50 focus:ring-1 focus:ring-brand-accent/20" required>{{ old('summary', $blog->summary) }}</textarea>
        </div>

        <div>
            <label for="content" class="block text-xs font-semibold uppercase tracking-wider text-brand-muted mb-2">Blog Content (Markdown Supported)</label>
            <textarea id="content" name="content" rows="12" placeholder="Write your blog post content here using Markdown formatting..." class="w-full rounded-lg border border-brand-border bg-brand-bg/60 px-3 py-2 text-sm text-brand-text font-mono outline-none focus:border-brand-accent/50 focus:ring-1 focus:ring-brand-accent/20" required>{{ old('content', $blog->content) }}</textarea>
        </div>

        @if(!Auth::user()->isAdmin())
            <div class="rounded-lg border border-amber-500/20 bg-amber-500/10 p-3 text-[11px] text-amber-400">
                Note: Updating this blog post will return its status to <strong>Pending Approval</strong> and it will require review by an administrator before it is published again.
            </div>
        @endif

        <div class="pt-4 border-t border-brand-border flex gap-3">
            <button type="submit" class="rounded-lg bg-brand-accent px-4 py-2 text-xs font-semibold text-white transition hover:bg-brand-accent-hover">
                Save Changes
            </button>
            <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard', ['tab' => 'all-blogs']) : route('dashboard', ['tab' => 'blogs']) }}" class="rounded-lg border border-brand-border bg-brand-card/50 px-4 py-2 text-xs font-semibold text-brand-text hover:bg-brand-bg transition">
                Cancel
            </a>
        </div>
@endsection
