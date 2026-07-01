@extends('layouts.dashboard')

@section('title', 'Write Blog - Ghost Compiler')
@section('page-title', 'Write Blog Article')

@section('content')
<div class="space-y-6">
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
                    <span class="ml-2 text-brand-text">Write Article</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="mt-8 grid grid-cols-1 gap-12 lg:grid-cols-12">
        <!-- Main Form Column -->
        <div class="lg:col-span-8 space-y-6">
            <div class="border-b border-brand-border pb-5">
                <h1 class="text-3xl font-extrabold tracking-tight text-brand-text sm:text-4xl">
                    Write an Article
                </h1>
                <p class="mt-2 text-sm text-brand-muted">
                    Share tutorials, tips, or configuration steps with other developers.
                </p>
            </div>

            <form action="{{ route('blogs.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-1.5">
                    <label for="title" class="text-xs font-semibold uppercase tracking-wider text-brand-muted">Article Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="e.g. How to set up Laravel Hetzner Storagebox SDK" required class="w-full rounded-xl border border-brand-border bg-brand-bg/60 px-4 py-3 text-brand-text placeholder-brand-muted outline-none ring-offset-brand-bg transition focus:border-brand-accent/50 focus:ring-2 focus:ring-brand-accent/20">
                    @error('title')
                        <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="summary" class="text-xs font-semibold uppercase tracking-wider text-brand-muted">Short Summary</label>
                    <textarea id="summary" name="summary" rows="2" placeholder="Briefly describe the article. This snippet shows up on feed listings for search engines." required class="w-full rounded-xl border border-brand-border bg-brand-bg/60 px-4 py-3 text-brand-text placeholder-brand-muted outline-none ring-offset-brand-bg transition focus:border-brand-accent/50 focus:ring-2 focus:ring-brand-accent/20">{{ old('summary') }}</textarea>
                    @error('summary')
                        <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="content" class="text-xs font-semibold uppercase tracking-wider text-brand-muted">Content (Markdown Supported)</label>
                        <span class="text-[11px] text-brand-accent font-semibold">MD Supported</span>
                    </div>
                    <textarea id="content" name="content" rows="12" placeholder="Write your core article here using Markdown style syntax. Include code blocks, links, lists, etc." required class="w-full rounded-xl border border-brand-border bg-brand-bg/60 p-4 text-brand-text placeholder-brand-muted outline-none ring-offset-brand-bg transition focus:border-brand-accent/50 focus:ring-2 focus:ring-brand-accent/20 font-mono text-sm leading-relaxed">{{ old('content') }}</textarea>
                    @error('content')
                        <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-3">
                    <button type="submit" class="rounded-xl bg-brand-accent px-6 py-3 text-sm font-semibold text-white transition hover:bg-brand-accent-hover hover:scale-[1.01] shadow-md shadow-brand-accent/10">
                        @if(Auth::user()->isAdmin())
                            Publish Article
                        @else
                            Submit for Approval
                        @endif
                    </button>
                    <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard', ['tab' => 'all-blogs']) : route('dashboard', ['tab' => 'blogs']) }}" class="rounded-xl border border-brand-border bg-brand-card/50 px-6 py-3 text-sm font-semibold text-brand-muted transition hover:text-brand-text hover:border-brand-accent">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Sidebar Guidelines Column -->
        <div class="lg:col-span-4 space-y-6">
            <div class="rounded-xl border border-brand-border bg-brand-card/45 p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-brand-text">Markdown Formatting Cheat-sheet</h3>
                <div class="space-y-3.5 text-xs text-brand-muted leading-relaxed">
                    <div>
                        <p class="font-semibold text-brand-text">Headings</p>
                        <code class="block bg-brand-bg border border-brand-border rounded p-1.5 mt-1">## Heading 2<br>### Heading 3</code>
                    </div>
                    <div>
                        <p class="font-semibold text-brand-text">Code Blocks</p>
                        <code class="block bg-brand-bg border border-brand-border rounded p-1.5 mt-1">```php<br>echo "hello world";<br>```</code>
                    </div>
                    <div>
                        <p class="font-semibold text-brand-text">Links & Emphasis</p>
                        <code class="block bg-brand-bg border border-brand-border rounded p-1.5 mt-1">[Link Text](url)<br>**Bold Text**<br>*Italic Text*</code>
                    </div>
                </div>
            </div>

            @if(!Auth::user()->isAdmin())
            <div class="rounded-xl border border-amber-500/20 bg-amber-500/5 p-6 shadow-sm space-y-2">
                <h3 class="text-xs font-bold uppercase tracking-wider text-amber-500">Moderation policy</h3>
                <p class="text-xs text-brand-muted leading-relaxed">
                    To maintain SEO and code-quality standards on ghostcompiler.in, all community articles are reviewed by an administrator before becoming publicly visible. Approval usually takes under 24 hours.
                </p>
            </div>
            @endif
@endsection
