@extends('layouts.dashboard')

@section('title', 'Edit Repository Link - Ghost Compiler')
@section('page-title', 'Edit Repository Link')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="border-b border-brand-border pb-4 mb-6 flex justify-between items-center">
        <h1 class="text-lg font-bold text-brand-text">Edit Repository Link</h1>
        <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard', ['tab' => 'all-repos']) : route('dashboard', ['tab' => 'repos']) }}" class="text-xs font-semibold text-brand-muted hover:text-brand-text transition">&larr; Back to Dashboard</a>
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

    <form action="{{ route('dashboard.repos.update', $repo->id) }}" method="POST" class="space-y-6">
        @csrf
        
        <div>
            <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-brand-muted mb-2">Repository Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $repo->title) }}" placeholder="e.g. laravel-stripe-sdk" class="w-full rounded-lg border border-brand-border bg-brand-bg/60 px-3 py-2 text-sm text-brand-text outline-none focus:border-brand-accent/50 focus:ring-1 focus:ring-brand-accent/20" required>
        </div>

        <div>
            <label for="repo_url" class="block text-xs font-semibold uppercase tracking-wider text-brand-muted mb-2">GitHub Repository URL</label>
            <input type="url" id="repo_url" name="repo_url" value="{{ old('repo_url', $repo->repo_url) }}" placeholder="e.g. https://github.com/username/repository" class="w-full rounded-lg border border-brand-border bg-brand-bg/60 px-3 py-2 text-sm text-brand-text outline-none focus:border-brand-accent/50 focus:ring-1 focus:ring-brand-accent/20" required>
        </div>

        <div>
            <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-brand-muted mb-2">Description</label>
            <p class="text-[11px] text-brand-muted mb-2">Briefly describe what this repository is for and how it benefits developers.</p>
            <textarea id="description" name="description" rows="5" maxlength="1000" placeholder="A brief description of your package..." class="w-full rounded-lg border border-brand-border bg-brand-bg/60 px-3 py-2 text-sm text-brand-text outline-none focus:border-brand-accent/50 focus:ring-1 focus:ring-brand-accent/20" required>{{ old('description', $repo->description) }}</textarea>
        </div>

        @if(!Auth::user()->isAdmin())
            <div class="rounded-lg border border-amber-500/20 bg-amber-500/10 p-3 text-[11px] text-amber-400">
                Note: Updating this repository will return its status to <strong>Pending Approval</strong> and it will require review by an administrator before it is visible on the site.
            </div>
        @endif

        <div class="pt-4 border-t border-brand-border flex gap-3">
            <button type="submit" class="rounded-lg bg-brand-accent px-4 py-2 text-xs font-semibold text-white transition hover:bg-brand-accent-hover">
                Save Changes
            </button>
            <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard', ['tab' => 'all-repos']) : route('dashboard', ['tab' => 'repos']) }}" class="rounded-lg border border-brand-border bg-brand-card/50 px-4 py-2 text-xs font-semibold text-brand-text hover:bg-brand-bg transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
