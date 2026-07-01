@extends('layouts.dashboard')

@section('title', 'Admin Command Center - Ghost Compiler')
@section('page-title', 'Admin Command Center')

@section('content')
<div class="space-y-6">

    @if($tab === 'overview')
        {{-- Stats Row --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
            <div class="rounded-xl border border-brand-border bg-brand-card/40 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-brand-muted">Total Users</p>
                <p class="text-2xl font-extrabold text-brand-text mt-1">{{ $users->count() }}</p>
                <p class="text-[10px] text-brand-muted mt-1">{{ $users->where('status','active')->count() }} active</p>
            </div>
            <div class="rounded-xl border border-amber-500/20 bg-amber-500/5 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-amber-500">Pending Review</p>
                <p class="text-2xl font-extrabold text-amber-500 mt-1">{{ $pendingBlogs->count() + $pendingRepos->count() }}</p>
                <p class="text-[10px] text-brand-muted mt-1">need moderation</p>
            </div>
            <div class="rounded-xl border border-brand-border bg-brand-card/40 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-brand-muted">Total Blogs</p>
                <p class="text-2xl font-extrabold text-brand-text mt-1">{{ $allBlogs->count() }}</p>
                <p class="text-[10px] text-brand-muted mt-1">{{ $allBlogs->where('status','approved')->count() }} published</p>
            </div>
            <div class="rounded-xl border border-brand-border bg-brand-card/40 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-brand-muted">Linked Repos</p>
                <p class="text-2xl font-extrabold text-brand-text mt-1">{{ $allRepos->count() }}</p>
                <p class="text-[10px] text-brand-muted mt-1">{{ $allRepos->where('status','approved')->count() }} indexed</p>
            </div>
        </div>

        {{-- Welcome & Quick Actions Card --}}
        <div class="rounded-xl border border-brand-border bg-gradient-to-r from-brand-card/30 to-brand-accent/5 p-6 space-y-4">
            <h2 class="text-base font-bold text-brand-text">Admin Command Center</h2>
            <p class="text-xs text-brand-muted leading-relaxed max-w-2xl">
                Manage all site operations. Approve or reject repository links and blog posts, activate or deactivate user accounts, generate admin security tokens, and configure global registration controls.
            </p>
            <div class="flex flex-wrap gap-3 pt-2">
                <a href="{{ route('admin.dashboard', ['tab' => 'moderation']) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-accent px-3.5 py-2 text-xs font-bold text-white hover:bg-brand-accent-hover transition shadow-sm cursor-pointer">
                    Moderate Submissions ({{ $pendingBlogs->count() + $pendingRepos->count() }})
                </a>
                <a href="{{ route('admin.dashboard', ['tab' => 'settings']) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-brand-border bg-brand-card/60 px-3.5 py-2 text-xs font-bold text-brand-text hover:border-brand-accent/40 transition cursor-pointer">
                    Site Settings
                </a>
                <a href="{{ route('admin.dashboard', ['tab' => 'users']) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-brand-border bg-brand-card/60 px-3.5 py-2 text-xs font-bold text-brand-text hover:border-brand-accent/40 transition cursor-pointer">
                    User Directory
                </a>
            </div>
        </div>

        {{-- Side-by-Side Recent Moderation & Recent Users --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Pending Moderation Queue --}}
            <div class="rounded-xl border border-brand-border bg-brand-card/20 p-5 space-y-4">
                <div class="flex items-center justify-between border-b border-brand-border/40 pb-3">
                    <h3 class="text-xs font-bold text-brand-text uppercase tracking-wider">Moderation Queue (Recent Pending)</h3>
                    <a href="{{ route('admin.dashboard', ['tab' => 'moderation']) }}" class="text-[10px] font-bold text-brand-accent hover:underline">View All Queue →</a>
                </div>
                @if($pendingBlogs->isEmpty() && $pendingRepos->isEmpty())
                    <p class="text-xs text-brand-muted py-2">No pending reviews. You're all caught up!</p>
                @else
                    <div class="space-y-3">
                        @foreach($pendingBlogs->take(2) as $blog)
                            <div class="flex items-center justify-between border-b border-brand-border/20 pb-2 last:border-0 last:pb-0">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-brand-text truncate">{{ $blog->title }}</p>
                                    <p class="text-[10px] text-brand-muted truncate">Blog post by {{ $blog->user->name ?? 'Unknown' }}</p>
                                </div>
                                <span class="rounded bg-amber-500/10 border border-amber-500/20 px-2 py-0.5 text-[9px] font-bold text-amber-500 shrink-0">Pending</span>
                            </div>
                        @endforeach
                        @foreach($pendingRepos->take(2) as $repo)
                            <div class="flex items-center justify-between border-b border-brand-border/20 pb-2 last:border-0 last:pb-0">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-brand-text truncate">{{ $repo->title }}</p>
                                    <p class="text-[10px] text-brand-muted truncate">GitHub Link by {{ $repo->user->name ?? 'Unknown' }}</p>
                                </div>
                                <span class="rounded bg-amber-500/10 border border-amber-500/20 px-2 py-0.5 text-[9px] font-bold text-amber-500 shrink-0">Pending</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Recent Users --}}
            <div class="rounded-xl border border-brand-border bg-brand-card/20 p-5 space-y-4">
                <div class="flex items-center justify-between border-b border-brand-border/40 pb-3">
                    <h3 class="text-xs font-bold text-brand-text uppercase tracking-wider">Recent Registered Users</h3>
                    <a href="{{ route('admin.dashboard', ['tab' => 'users']) }}" class="text-[10px] font-bold text-brand-accent hover:underline">View User Directory →</a>
                </div>
                @if($users->isEmpty())
                    <p class="text-xs text-brand-muted py-2">No users registered yet.</p>
                @else
                    <div class="space-y-3">
                        @foreach($users->take(4) as $u)
                            <div class="flex items-center justify-between border-b border-brand-border/20 pb-2 last:border-0 last:pb-0">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-brand-text truncate">{{ $u->name }}</p>
                                    <p class="text-[10px] font-mono text-brand-muted truncate">{{ $u->email }}</p>
                                </div>
                                <span class="inline-flex rounded-full px-2 py-0.5 text-[9px] font-bold border shrink-0
                                    {{ $u->isActive() ? 'bg-emerald-500/10 text-brand-accent border-emerald-500/20' : 'bg-rose-500/10 text-rose-500 border-rose-500/20' }}">
                                    {{ $u->status }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    @elseif($tab === 'moderation')
        {{-- Moderation Queue --}}
        <div class="space-y-8">
            {{-- Pending Blogs --}}
            <div>
                <h3 class="text-sm font-bold text-brand-text border-b border-brand-border pb-3 mb-4">Pending Blog Posts ({{ $pendingBlogs->count() }})</h3>
                @forelse($pendingBlogs as $blog)
                <div class="rounded-xl border border-brand-border bg-brand-card/20 p-4 mb-3 space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <p class="font-semibold text-sm text-brand-text">{{ $blog->title }}</p>
                            <p class="text-[11px] text-brand-muted mt-0.5">by {{ $blog->user->name }} &middot; {{ $blog->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="flex gap-2 shrink-0">
                            <form action="{{ route('dashboard.admin.blogs.approve', $blog->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="rounded-lg bg-brand-accent px-3 py-1.5 text-xs font-bold text-white hover:bg-brand-accent-hover transition cursor-pointer">Approve</button>
                            </form>
                            <form action="{{ route('dashboard.blogs.delete', $blog->id) }}" method="POST" onsubmit="return confirm('Reject and delete?');">
                                @csrf
                                <button type="submit" class="rounded-lg border border-rose-500/20 bg-rose-500/10 px-3 py-1.5 text-xs font-bold text-rose-500 hover:bg-rose-500/20 transition cursor-pointer">Reject</button>
                            </form>
                        </div>
                    </div>
                    <div class="rounded-lg bg-brand-bg border border-brand-border/50 p-3 text-[11px] font-mono text-brand-muted leading-relaxed max-h-28 overflow-y-auto">
                        <span class="font-bold text-brand-text">Summary: </span>{{ $blog->summary }}<br>
                        <span class="font-bold text-brand-text">Preview: </span>{{ substr($blog->content, 0, 250) }}...
                    </div>
                </div>
                @empty
                <div class="rounded-xl border border-dashed border-brand-border bg-brand-card/10 p-8 text-center">
                    <p class="text-sm text-brand-muted">No pending blogs. You're all caught up!</p>
                </div>
                @endforelse
            </div>

            {{-- Pending Repos --}}
            <div>
                <h3 class="text-sm font-bold text-brand-text border-b border-brand-border pb-3 mb-4">Pending Repositories ({{ $pendingRepos->count() }})</h3>
                @forelse($pendingRepos as $repo)
                <div class="rounded-xl border border-brand-border bg-brand-card/20 p-4 mb-3 space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <p class="font-semibold text-sm text-brand-text">{{ $repo->title }}</p>
                            <p class="text-[11px] text-brand-muted mt-0.5">by {{ $repo->user->name }} &middot; {{ $repo->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="flex gap-2 shrink-0">
                            <form action="{{ route('dashboard.admin.repos.approve', $repo->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="rounded-lg bg-brand-accent px-3 py-1.5 text-xs font-bold text-white hover:bg-brand-accent-hover transition cursor-pointer">Approve</button>
                            </form>
                            <form action="{{ route('dashboard.repos.delete', $repo->id) }}" method="POST" onsubmit="return confirm('Reject and delete?');">
                                @csrf
                                <button type="submit" class="rounded-lg border border-rose-500/20 bg-rose-500/10 px-3 py-1.5 text-xs font-bold text-rose-500 hover:bg-rose-500/20 transition cursor-pointer">Reject</button>
                            </form>
                        </div>
                    </div>
                    <div class="rounded-lg bg-brand-bg border border-brand-border/50 p-3 text-[11px] leading-relaxed">
                        <a href="{{ $repo->repo_url }}" target="_blank" rel="noopener noreferrer" class="font-mono text-brand-accent hover:underline text-xs">{{ $repo->repo_url }}</a>
                        <p class="text-brand-muted mt-1">{{ $repo->description }}</p>
                    </div>
                </div>
                @empty
                <div class="rounded-xl border border-dashed border-brand-border bg-brand-card/10 p-8 text-center">
                    <p class="text-sm text-brand-muted">No pending repositories.</p>
                </div>
                @endforelse
            </div>
        </div>

    @elseif($tab === 'users')
        {{-- User Directory --}}
        <div class="space-y-4">
            <h3 class="text-sm font-bold text-brand-text">User Directory</h3>
            <div class="rounded-xl border border-brand-border overflow-hidden">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-brand-border bg-brand-card/40">
                            <th class="px-4 py-3 font-bold text-brand-text uppercase tracking-wider text-[10px]">User</th>
                            <th class="px-4 py-3 font-bold text-brand-text uppercase tracking-wider text-[10px]">Role</th>
                            <th class="px-4 py-3 font-bold text-brand-text uppercase tracking-wider text-[10px]">Registered</th>
                            <th class="px-4 py-3 font-bold text-brand-text uppercase tracking-wider text-[10px]">Status</th>
                            <th class="px-4 py-3 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-border/50">
                        @foreach($users as $u)
                        <tr class="hover:bg-brand-card/20 transition">
                            <td class="px-4 py-3">
                                <p class="font-semibold text-brand-text">{{ $u->name }}</p>
                                <p class="text-[10px] font-mono text-brand-muted">{{ $u->email }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-[10px] uppercase font-bold {{ $u->isAdmin() ? 'text-brand-accent' : 'text-brand-muted' }}">{{ $u->role }}</span>
                            </td>
                            <td class="px-4 py-3 text-brand-muted">{{ $u->created_at->format('Y-m-d') }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-bold border
                                    {{ $u->isActive() ? 'bg-emerald-500/10 text-brand-accent border-emerald-500/20' : 'bg-rose-500/10 text-rose-500 border-rose-500/20' }}">
                                    {{ $u->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                @if($u->id !== Auth::id())
                                <form action="{{ route('dashboard.admin.users.toggle', $u->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-bold cursor-pointer
                                        {{ $u->isActive() ? 'text-rose-500 hover:underline' : 'text-brand-accent hover:underline' }}">
                                        {{ $u->isActive() ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @elseif($tab === 'all-blogs')
        {{-- All Blogs --}}
        <div class="space-y-4">
            <h3 class="text-sm font-bold text-brand-text">All Blogs</h3>
            <div class="rounded-xl border border-brand-border overflow-hidden">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-brand-border bg-brand-card/40">
                            <th class="px-4 py-3 font-bold text-brand-text uppercase tracking-wider text-[10px]">Title</th>
                            <th class="px-4 py-3 font-bold text-brand-text uppercase tracking-wider text-[10px]">Author</th>
                            <th class="px-4 py-3 font-bold text-brand-text uppercase tracking-wider text-[10px]">Status</th>
                            <th class="px-4 py-3 font-bold text-brand-text uppercase tracking-wider text-[10px]">Date</th>
                            <th class="px-4 py-3 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-border/50">
                        @foreach($allBlogs as $blog)
                        <tr class="hover:bg-brand-card/20 transition">
                            <td class="px-4 py-3 font-semibold text-brand-text">
                                <a href="{{ route('blogs.show', $blog->slug) }}" target="_blank" rel="noopener noreferrer" class="hover:underline hover:text-brand-accent transition">{{ $blog->title }}</a>
                            </td>
                            <td class="px-4 py-3 text-brand-muted">{{ $blog->user->name ?? 'Unknown' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-bold border
                                    {{ $blog->status === 'approved' ? 'bg-emerald-500/10 text-brand-accent border-emerald-500/20' : 'bg-amber-500/10 text-amber-500 border-amber-500/20' }}">
                                    {{ $blog->status === 'approved' ? 'Published' : 'Pending' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-brand-muted">{{ $blog->created_at->format('Y-m-d') }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('dashboard.blogs.edit', $blog->id) }}" class="text-[10px] font-bold text-brand-accent hover:underline">Edit</a>
                                    <form action="{{ route('dashboard.blogs.delete', $blog->id) }}" method="POST" onsubmit="return confirm('Delete this blog post?');">
                                        @csrf
                                        <button type="submit" class="text-[10px] font-bold text-rose-500 hover:underline cursor-pointer">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @elseif($tab === 'all-repos')
        {{-- All Repos --}}
        <div class="space-y-4">
            <h3 class="text-sm font-bold text-brand-text">All Repositories</h3>
            <div class="rounded-xl border border-brand-border overflow-hidden">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-brand-border bg-brand-card/40">
                            <th class="px-4 py-3 font-bold text-brand-text uppercase tracking-wider text-[10px]">Title</th>
                            <th class="px-4 py-3 font-bold text-brand-text uppercase tracking-wider text-[10px]">Submitter</th>
                            <th class="px-4 py-3 font-bold text-brand-text uppercase tracking-wider text-[10px]">Status</th>
                            <th class="px-4 py-3 font-bold text-brand-text uppercase tracking-wider text-[10px]">Date</th>
                            <th class="px-4 py-3 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-border/50">
                        @foreach($allRepos as $repo)
                        <tr class="hover:bg-brand-card/20 transition">
                            <td class="px-4 py-3">
                                <p class="font-semibold text-brand-text">{{ $repo->title }}</p>
                                <a href="{{ $repo->repo_url }}" target="_blank" rel="noopener noreferrer" class="text-[10px] font-mono text-brand-muted hover:text-brand-accent transition">{{ parse_url($repo->repo_url, PHP_URL_PATH) }}</a>
                            </td>
                            <td class="px-4 py-3 text-brand-muted">{{ $repo->user->name ?? 'Unknown' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-bold border
                                    {{ $repo->status === 'approved' ? 'bg-emerald-500/10 text-brand-accent border-emerald-500/20' : 'bg-amber-500/10 text-amber-500 border-amber-500/20' }}">
                                    {{ $repo->status === 'approved' ? 'Approved' : 'Pending' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-brand-muted">{{ $repo->created_at->format('Y-m-d') }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('dashboard.repos.edit', $repo->id) }}" class="text-[10px] font-bold text-brand-accent hover:underline">Edit</a>
                                    <form action="{{ route('dashboard.repos.delete', $repo->id) }}" method="POST" onsubmit="return confirm('Delete this repository?');">
                                        @csrf
                                        <button type="submit" class="text-[10px] font-bold text-rose-500 hover:underline cursor-pointer">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @elseif($tab === 'tokens')
        {{-- Admin API Tokens --}}
        <div class="space-y-6">
            <div>
                <h2 class="text-sm font-bold text-brand-text">Admin API Tokens</h2>
                <p class="text-xs text-brand-muted mt-1">Generate API tokens for automated scripts and commands.</p>
            </div>

            @if(session('new_token'))
                <div class="rounded-xl border border-amber-500/20 bg-amber-500/10 p-4">
                    <p class="text-xs font-bold text-amber-600 dark:text-amber-400 mb-2">⚠ Copy your token now — it won't be shown again.</p>
                    <code class="block w-full rounded-lg border border-brand-border bg-brand-bg px-4 py-3 font-mono text-xs text-brand-text break-all select-all">{{ session('new_token') }}</code>
                </div>
            @endif

            <div class="rounded-xl border border-brand-border bg-brand-card/40 p-5">
                <h3 class="text-xs font-bold text-brand-text mb-4">Create New Token</h3>
                <form action="{{ route('dashboard.tokens.create') }}" method="POST" class="flex items-end gap-3">
                    @csrf
                    <div class="flex-1 space-y-1.5">
                        <label for="token_name" class="text-xs font-semibold text-brand-muted">Token Name</label>
                        <input type="text" id="token_name" name="token_name" placeholder="e.g. Admin Script, CI" required
                               class="w-full rounded-lg border border-brand-border bg-brand-bg/40 px-3.5 py-2 text-xs text-brand-text outline-none focus:border-brand-accent/50 focus:ring-1 focus:ring-brand-accent/20">
                    </div>
                    <button type="submit" class="rounded-lg bg-brand-accent px-4 py-2 text-xs font-bold text-white hover:bg-brand-accent-hover transition cursor-pointer shrink-0">
                        Create
                    </button>
                </form>
            </div>

            @if($tokens->isNotEmpty())
                <div class="rounded-xl border border-brand-border overflow-hidden">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-brand-border bg-brand-card/40">
                                <th class="px-4 py-3 font-bold text-brand-text uppercase tracking-wider text-[10px]">Name</th>
                                <th class="px-4 py-3 font-bold text-brand-text uppercase tracking-wider text-[10px]">Created</th>
                                <th class="px-4 py-3 font-bold text-brand-text uppercase tracking-wider text-[10px]">Last Used</th>
                                <th class="px-4 py-3 text-right"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-brand-border/50">
                            @foreach($tokens as $token)
                            <tr class="hover:bg-brand-card/20 transition">
                                <td class="px-4 py-3 font-semibold text-brand-text">{{ $token->name }}</td>
                                <td class="px-4 py-3 text-brand-muted">{{ $token->created_at->format('M d, Y') }}</td>
                                <td class="px-4 py-3 text-brand-muted">{{ $token->last_used_at ? $token->last_used_at->diffForHumans() : 'Never' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <form action="{{ route('dashboard.tokens.delete', $token->id) }}" method="POST" onsubmit="return confirm('Revoke this token?');">
                                        @csrf
                                        <button type="submit" class="text-[10px] font-bold text-rose-500 hover:underline cursor-pointer">Revoke</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    @elseif($tab === 'profile')
        {{-- Profile Settings --}}
        <div class="space-y-6">
            <h2 class="text-sm font-bold text-brand-text">Profile Settings</h2>

            <div class="rounded-xl border border-brand-border bg-brand-card/40 p-5 max-w-lg">
                <form action="{{ route('dashboard.profile.update') }}" method="POST" class="space-y-5">
                    @csrf

                    @if($errors->any())
                        <div class="rounded-lg border border-rose-500/20 bg-rose-500/10 p-3 text-xs text-rose-500">
                            @foreach($errors->all() as $error) <p>{{ $error }}</p> @endforeach
                        </div>
                    @endif

                    <div class="space-y-1.5">
                        <label for="profile_name" class="text-xs font-semibold text-brand-muted">Full Name</label>
                        <input type="text" id="profile_name" name="name" value="{{ Auth::user()->name }}" required
                               class="w-full rounded-lg border border-brand-border bg-brand-bg/40 px-3.5 py-2 text-xs text-brand-text outline-none focus:border-brand-accent/50 focus:ring-1 focus:ring-brand-accent/20">
                    </div>

                    <div class="space-y-1.5">
                        <label for="profile_email" class="text-xs font-semibold text-brand-muted">Email Address</label>
                        <input type="email" id="profile_email" name="email" value="{{ Auth::user()->email }}" required
                               class="w-full rounded-lg border border-brand-border bg-brand-bg/40 px-3.5 py-2 text-xs text-brand-text outline-none focus:border-brand-accent/50 focus:ring-1 focus:ring-brand-accent/20">
                    </div>

                    <div class="border-t border-brand-border/40 pt-5 space-y-4">
                        <div>
                            <h4 class="text-xs font-bold text-brand-text">Change Password</h4>
                            <p class="text-[10px] text-brand-muted mt-0.5">Leave blank to keep your current password.</p>
                        </div>

                        <div class="space-y-1.5">
                            <label for="current_password" class="text-xs font-semibold text-brand-muted">Current Password</label>
                            <input type="password" id="current_password" name="current_password"
                                   class="w-full rounded-lg border border-brand-border bg-brand-bg/40 px-3.5 py-2 text-xs text-brand-text outline-none focus:border-brand-accent/50 focus:ring-1 focus:ring-brand-accent/20">
                        </div>

                        <div class="space-y-1.5">
                            <label for="profile_password" class="text-xs font-semibold text-brand-muted">New Password</label>
                            <input type="password" id="profile_password" name="password" placeholder="Min. 8 characters"
                                   class="w-full rounded-lg border border-brand-border bg-brand-bg/40 px-3.5 py-2 text-xs text-brand-text outline-none focus:border-brand-accent/50 focus:ring-1 focus:ring-brand-accent/20">
                        </div>

                        <div class="space-y-1.5">
                            <label for="profile_password_confirmation" class="text-xs font-semibold text-brand-muted">Confirm New Password</label>
                            <input type="password" id="profile_password_confirmation" name="password_confirmation" placeholder="Repeat new password"
                                   class="w-full rounded-lg border border-brand-border bg-brand-bg/40 px-3.5 py-2 text-xs text-brand-text outline-none focus:border-brand-accent/50 focus:ring-1 focus:ring-brand-accent/20">
                        </div>
                    </div>

                    <button type="submit" class="rounded-lg bg-brand-accent px-4 py-2.5 text-xs font-bold text-white hover:bg-brand-accent-hover transition shadow-md shadow-brand-accent/15 cursor-pointer">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>

    @elseif($tab === 'settings')
        {{-- Site Settings --}}
        <div class="space-y-6">
            <h3 class="text-sm font-bold text-brand-text">Site Configuration</h3>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Configuration Forms -->
                <div class="lg:col-span-2 space-y-6">
                    <form action="{{ route('dashboard.admin.settings.save') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- General Settings Box -->
                        <div class="rounded-xl border border-brand-border bg-brand-card/40 p-5 space-y-4">
                            <h4 class="text-xs font-bold text-brand-text border-b border-brand-border/40 pb-2">General Configuration</h4>
                            
                            <div class="space-y-3">
                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input type="checkbox" name="registration_enabled" value="1"
                                           {{ $registrationEnabled === '1' ? 'checked' : '' }}
                                           class="mt-0.5 rounded border-brand-border bg-brand-bg text-brand-accent focus:ring-brand-accent/20 h-4 w-4">
                                    <div>
                                        <span class="text-xs font-bold text-brand-text">Enable User Registration</span>
                                        <p class="text-[10px] text-brand-muted mt-0.5">When disabled, new users cannot register, but existing users can still sign in.</p>
                                    </div>
                                </label>
                            </div>

                            <div class="space-y-1.5">
                                <label for="github_token" class="text-xs font-semibold text-brand-muted">GitHub API Token (Optional)</label>
                                <input type="password" id="github_token" name="github_token" value="{{ $githubToken }}" placeholder="ghp_..." autocomplete="off"
                                       class="w-full rounded-lg border border-brand-border bg-brand-bg/40 px-3.5 py-2 text-xs text-brand-text outline-none focus:border-brand-accent/50 focus:ring-1 focus:ring-brand-accent/20">
                                <p class="text-[10px] text-brand-muted">Used to authenticate API requests to GitHub and increase rate limits.</p>
                            </div>
                        </div>

                        <!-- Social OAuth Credentials Box -->
                        <div class="rounded-xl border border-brand-border bg-brand-card/40 p-5 space-y-4">
                            <h4 class="text-xs font-bold text-brand-text border-b border-brand-border/40 pb-2">Social OAuth Configurations</h4>

                            <div class="border border-brand-border/30 bg-brand-bg/20 rounded-lg p-3 space-y-3">
                                <div class="flex items-center justify-between">
                                    <h5 class="text-[11px] font-bold text-brand-accent">GitHub OAuth Credentials</h5>
                                    <a href="https://github.com/settings/developers" target="_blank" rel="noopener noreferrer" class="text-[9px] text-brand-accent/80 hover:text-brand-accent hover:underline flex items-center gap-1 font-semibold">
                                        Generate Keys ↗
                                    </a>
                                </div>
                                <div class="space-y-1.5">
                                    <label for="github_client_id" class="text-[10px] font-semibold text-brand-muted">GitHub Client ID</label>
                                    <input type="text" id="github_client_id" name="github_client_id" value="{{ $githubClientId }}" placeholder="e.g. Ov23li..." autocomplete="off"
                                           class="w-full rounded-lg border border-brand-border bg-brand-bg/40 px-3.5 py-2 text-xs text-brand-text outline-none focus:border-brand-accent/50 focus:ring-1 focus:ring-brand-accent/20">
                                </div>
                                <div class="space-y-1.5">
                                    <label for="github_client_secret" class="text-[10px] font-semibold text-brand-muted">GitHub Client Secret</label>
                                    <input type="password" id="github_client_secret" name="github_client_secret" value="{{ $githubClientSecret }}" placeholder="••••••••" autocomplete="off"
                                           class="w-full rounded-lg border border-brand-border bg-brand-bg/40 px-3.5 py-2 text-xs text-brand-text outline-none focus:border-brand-accent/50 focus:ring-1 focus:ring-brand-accent/20">
                                </div>
                                <p class="text-[9px] text-brand-muted">Callback Redirect URI: <code class="bg-brand-bg px-1 py-0.5 rounded break-all select-all">{{ route('auth.social.callback', 'github') }}</code></p>
                            </div>

                            <div class="border border-brand-border/30 bg-brand-bg/20 rounded-lg p-3 space-y-3">
                                <div class="flex items-center justify-between">
                                    <h5 class="text-[11px] font-bold text-brand-accent">Google OAuth Credentials</h5>
                                    <a href="https://console.cloud.google.com/apis/credentials" target="_blank" rel="noopener noreferrer" class="text-[9px] text-brand-accent/80 hover:text-brand-accent hover:underline flex items-center gap-1 font-semibold">
                                        Generate Keys ↗
                                    </a>
                                </div>
                                <div class="space-y-1.5">
                                    <label for="google_client_id" class="text-[10px] font-semibold text-brand-muted">Google Client ID</label>
                                    <input type="text" id="google_client_id" name="google_client_id" value="{{ $googleClientId }}" placeholder="e.g. 1024-..." autocomplete="off"
                                           class="w-full rounded-lg border border-brand-border bg-brand-bg/40 px-3.5 py-2 text-xs text-brand-text outline-none focus:border-brand-accent/50 focus:ring-1 focus:ring-brand-accent/20">
                                </div>
                                <div class="space-y-1.5">
                                    <label for="google_client_secret" class="text-[10px] font-semibold text-brand-muted">Google Client Secret</label>
                                    <input type="password" id="google_client_secret" name="google_client_secret" value="{{ $googleClientSecret }}" placeholder="••••••••" autocomplete="off"
                                           class="w-full rounded-lg border border-brand-border bg-brand-bg/40 px-3.5 py-2 text-xs text-brand-text outline-none focus:border-brand-accent/50 focus:ring-1 focus:ring-brand-accent/20">
                                </div>
                                <p class="text-[9px] text-brand-muted">Callback Redirect URI: <code class="bg-brand-bg px-1 py-0.5 rounded break-all select-all">{{ route('auth.social.callback', 'google') }}</code></p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end pb-6">
                            <button type="submit" class="rounded-lg bg-brand-accent px-4 py-2.5 text-xs font-bold text-white hover:bg-brand-accent-hover transition shadow-md shadow-brand-accent/15 cursor-pointer">
                                Save Configuration Settings
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Column: Status & Info Sidebar Panel -->
                <div class="space-y-6 lg:col-span-1">
                    <!-- Diagnostics Card -->
                    <div class="rounded-xl border border-brand-border bg-brand-card/40 p-5 space-y-4">
                        <h4 class="text-xs font-bold text-brand-text border-b border-brand-border/40 pb-2 uppercase tracking-wider">System Diagnostics</h4>
                        <div class="space-y-2.5 text-xs">
                            <div class="flex justify-between">
                                <span class="text-brand-muted">PHP Version</span>
                                <span class="font-mono font-semibold text-brand-text">{{ PHP_VERSION }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-brand-muted">Laravel Version</span>
                                <span class="font-mono font-semibold text-brand-text">{{ app()->version() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-brand-muted">Environment</span>
                                <span class="rounded bg-brand-bg px-2 py-0.5 font-mono text-[10px] text-brand-accent border border-brand-accent/20">{{ app()->environment() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-brand-muted">Debug Mode</span>
                                <span class="rounded px-2 py-0.5 font-mono text-[10px] font-bold border
                                    {{ config('app.debug') ? 'bg-amber-500/10 text-amber-500 border-amber-500/20' : 'bg-emerald-500/10 text-brand-accent border-emerald-500/20' }}">
                                    {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-brand-muted">Database Connection</span>
                                <span class="font-mono font-semibold text-brand-text capitalize">{{ config('database.default') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Integration Health Status Card -->
                    <div class="rounded-xl border border-brand-border bg-brand-card/40 p-5 space-y-4">
                        <h4 class="text-xs font-bold text-brand-text border-b border-brand-border/40 pb-2 uppercase tracking-wider">Integration Health</h4>
                        <div class="space-y-3 text-xs">
                            <!-- GitHub OAuth -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 w-2 rounded-full {{ !empty($githubClientId) && !empty($githubClientSecret) ? 'bg-emerald-500 shadow-sm shadow-emerald-500/50' : 'bg-brand-muted/40' }}"></div>
                                    <span class="text-brand-text">GitHub OAuth</span>
                                </div>
                                <span class="text-[10px] font-semibold {{ !empty($githubClientId) && !empty($githubClientSecret) ? 'text-brand-accent' : 'text-brand-muted' }}">
                                    {{ !empty($githubClientId) && !empty($githubClientSecret) ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            <!-- Google OAuth -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 w-2 rounded-full {{ !empty($googleClientId) && !empty($googleClientSecret) ? 'bg-emerald-500 shadow-sm shadow-emerald-500/50' : 'bg-brand-muted/40' }}"></div>
                                    <span class="text-brand-text">Google OAuth</span>
                                </div>
                                <span class="text-[10px] font-semibold {{ !empty($googleClientId) && !empty($googleClientSecret) ? 'text-brand-accent' : 'text-brand-muted' }}">
                                    {{ !empty($googleClientId) && !empty($googleClientSecret) ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            <!-- GitHub Token (Rate Limit) -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 w-2 rounded-full {{ !empty($githubToken) ? 'bg-emerald-500 shadow-sm shadow-emerald-500/50' : 'bg-brand-muted/40' }}"></div>
                                    <span class="text-brand-text">GitHub Sync Token</span>
                                </div>
                                <span class="text-[10px] font-semibold {{ !empty($githubToken) ? 'text-brand-accent' : 'text-brand-muted' }}">
                                    {{ !empty($githubToken) ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Callback URIs Copy Card -->
                    <div class="rounded-xl border border-brand-border bg-brand-card/40 p-5 space-y-4">
                        <h4 class="text-xs font-bold text-brand-text border-b border-brand-border/40 pb-2 uppercase tracking-wider">Callback Reference URIs</h4>
                        <div class="space-y-3.5">
                            <div class="space-y-1">
                                <span class="text-[10px] font-semibold text-brand-muted block">GitHub OAuth Redirect URI</span>
                                <code class="block w-full bg-brand-bg px-2.5 py-2 border border-brand-border/50 rounded font-mono text-[9px] text-brand-text select-all break-all leading-relaxed">{{ route('auth.social.callback', 'github') }}</code>
                            </div>
                            <div class="space-y-1">
                                <span class="text-[10px] font-semibold text-brand-muted block">Google OAuth Redirect URI</span>
                                <code class="block w-full bg-brand-bg px-2.5 py-2 border border-brand-border/50 rounded font-mono text-[9px] text-brand-text select-all break-all leading-relaxed">{{ route('auth.social.callback', 'google') }}</code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
