@extends('layouts.dashboard')

@section('title', 'Developer Dashboard - Ghost Compiler')
@section('page-title', 'Developer Dashboard')

@section('content')
<div class="space-y-6">

    @if($tab === 'overview')
        {{-- Stats Row --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
            <div class="rounded-xl border border-brand-border bg-brand-card/40 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-brand-muted">Linked Repos</p>
                <p class="text-2xl font-extrabold text-brand-text mt-1">{{ $myRepos->count() }}</p>
                <p class="text-[10px] text-brand-muted mt-1">{{ $myRepos->where('status','approved')->count() }} approved</p>
            </div>
            <div class="rounded-xl border border-brand-border bg-brand-card/40 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-brand-muted">Blog Posts</p>
                <p class="text-2xl font-extrabold text-brand-text mt-1">{{ $myBlogs->count() }}</p>
                <p class="text-[10px] text-brand-muted mt-1">{{ $myBlogs->where('status','approved')->count() }} published</p>
            </div>
            <div class="rounded-xl border border-brand-border bg-brand-card/40 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-brand-muted">API Tokens</p>
                <p class="text-2xl font-extrabold text-brand-text mt-1">{{ $tokens->count() }}</p>
                <p class="text-[10px] text-brand-muted mt-1">active tokens</p>
            </div>
        </div>

        {{-- Welcome Card --}}
        <div class="rounded-xl border border-brand-border bg-gradient-to-r from-brand-card/30 to-brand-accent/5 p-6 space-y-4">
            <h2 class="text-base font-bold text-brand-text">Welcome back, {{ Auth::user()->name }}!</h2>
            <p class="text-xs text-brand-muted leading-relaxed max-w-2xl">
                This is your Developer Command Center. From here, you can link and sync your GitHub repositories to display them on the Ghost Compiler directory, write tech blogs, and manage your API security tokens.
            </p>
            <div class="flex flex-wrap gap-3 pt-2">
                <a href="{{ route('repos.link') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-accent px-3.5 py-2 text-xs font-bold text-white hover:bg-brand-accent-hover transition shadow-sm cursor-pointer">
                    Link a Repository
                </a>
                <a href="{{ route('blogs.create') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-brand-border bg-brand-card/60 px-3.5 py-2 text-xs font-bold text-brand-text hover:border-brand-accent/40 transition cursor-pointer">
                    Write Blog Post
                </a>
                <a href="{{ route('dashboard.two-factor') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-brand-border bg-brand-card/60 px-3.5 py-2 text-xs font-bold text-brand-text hover:border-brand-accent/40 transition cursor-pointer">
                    Manage 2FA Settings
                </a>
            </div>
        </div>

        {{-- Side-by-Side Overview Tables --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Recent Repos --}}
            <div class="rounded-xl border border-brand-border bg-brand-card/20 p-5 space-y-4">
                <div class="flex items-center justify-between border-b border-brand-border/40 pb-3">
                    <h3 class="text-xs font-bold text-brand-text uppercase tracking-wider">Recent Linked Repos</h3>
                    <a href="{{ route('dashboard', ['tab' => 'repos']) }}" class="text-[10px] font-bold text-brand-accent hover:underline">View All →</a>
                </div>
                @if($myRepos->isEmpty())
                    <p class="text-xs text-brand-muted py-2">No repositories linked yet.</p>
                @else
                    <div class="space-y-3">
                        @foreach($myRepos->take(3) as $repo)
                            <div class="flex items-center justify-between border-b border-brand-border/20 pb-2 last:border-0 last:pb-0">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-brand-text truncate">{{ $repo->title }}</p>
                                    <p class="text-[10px] font-mono text-brand-muted truncate">{{ parse_url($repo->repo_url, PHP_URL_PATH) }}</p>
                                </div>
                                <span class="inline-flex rounded-full px-2 py-0.5 text-[9px] font-bold border shrink-0
                                    {{ $repo->status === 'approved' ? 'bg-emerald-500/10 text-brand-accent border-emerald-500/20' : 'bg-amber-500/10 text-amber-500 border-amber-500/20' }}">
                                    {{ $repo->status === 'approved' ? 'Approved' : 'Pending' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Recent Blogs --}}
            <div class="rounded-xl border border-brand-border bg-brand-card/20 p-5 space-y-4">
                <div class="flex items-center justify-between border-b border-brand-border/40 pb-3">
                    <h3 class="text-xs font-bold text-brand-text uppercase tracking-wider">Recent Blog Posts</h3>
                    <a href="{{ route('dashboard', ['tab' => 'blogs']) }}" class="text-[10px] font-bold text-brand-accent hover:underline">View All →</a>
                </div>
                @if($myBlogs->isEmpty())
                    <p class="text-xs text-brand-muted py-2">No blog posts written yet.</p>
                @else
                    <div class="space-y-3">
                        @foreach($myBlogs->take(3) as $blog)
                            <div class="flex items-center justify-between border-b border-brand-border/20 pb-2 last:border-0 last:pb-0">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-brand-text truncate">{{ $blog->title }}</p>
                                    <p class="text-[10px] text-brand-muted truncate">{{ $blog->created_at->format('M d, Y') }}</p>
                                </div>
                                <span class="inline-flex rounded-full px-2 py-0.5 text-[9px] font-bold border shrink-0
                                    {{ $blog->status === 'approved' ? 'bg-emerald-500/10 text-brand-accent border-emerald-500/20' : 'bg-amber-500/10 text-amber-500 border-amber-500/20' }}">
                                    {{ $blog->status === 'approved' ? 'Published' : 'Pending' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    @elseif($tab === 'repos')
        {{-- Repos Section --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold text-brand-text">Linked Repositories</h2>
                <a href="{{ route('repos.link') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-accent px-3.5 py-2 text-xs font-bold text-white hover:bg-brand-accent-hover transition shadow-sm cursor-pointer">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    Link Repo
                </a>
            </div>

            @if($myRepos->isEmpty())
                <div class="rounded-xl border border-dashed border-brand-border bg-brand-card/10 p-12 text-center">
                    <svg class="h-10 w-10 text-brand-muted/40 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                    <p class="text-sm text-brand-muted">No repositories linked yet.</p>
                    <a href="{{ route('repos.link') }}" class="inline-block mt-3 text-xs font-bold text-brand-accent hover:underline">Link your first repository →</a>
                </div>
            @else
                <div class="rounded-xl border border-brand-border overflow-hidden">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-brand-border bg-brand-card/40">
                                <th class="px-4 py-3 font-bold text-brand-text uppercase tracking-wider text-[10px]">Repository</th>
                                <th class="px-4 py-3 font-bold text-brand-text uppercase tracking-wider text-[10px]">Status</th>
                                <th class="px-4 py-3 font-bold text-brand-text uppercase tracking-wider text-[10px]">Submitted</th>
                                <th class="px-4 py-3 font-bold text-brand-text uppercase tracking-wider text-[10px] text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-brand-border/50">
                            @foreach($myRepos as $repo)
                            <tr class="hover:bg-brand-card/20 transition">
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-brand-text">{{ $repo->title }}</p>
                                    <a href="{{ $repo->repo_url }}" target="_blank" rel="noopener noreferrer" class="text-[10px] font-mono text-brand-muted hover:text-brand-accent transition">{{ parse_url($repo->repo_url, PHP_URL_PATH) }}</a>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-bold border
                                        {{ $repo->status === 'approved' ? 'bg-emerald-500/10 text-brand-accent border-emerald-500/20' : 'bg-amber-500/10 text-amber-500 border-amber-500/20' }}">
                                        {{ $repo->status === 'approved' ? 'Approved' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-brand-muted">{{ $repo->created_at->format('M d, Y') }}</td>
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
            @endif
        </div>

    @elseif($tab === 'blogs')
        {{-- Blogs Section --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold text-brand-text">Blog Posts</h2>
                <a href="{{ route('blogs.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-accent px-3.5 py-2 text-xs font-bold text-white hover:bg-brand-accent-hover transition shadow-sm cursor-pointer">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    Write Post
                </a>
            </div>

            @if($myBlogs->isEmpty())
                <div class="rounded-xl border border-dashed border-brand-border bg-brand-card/10 p-12 text-center">
                    <svg class="h-10 w-10 text-brand-muted/40 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    <p class="text-sm text-brand-muted">No blog posts yet.</p>
                    <a href="{{ route('blogs.create') }}" class="inline-block mt-3 text-xs font-bold text-brand-accent hover:underline">Write your first post →</a>
                </div>
            @else
                <div class="rounded-xl border border-brand-border overflow-hidden">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-brand-border bg-brand-card/40">
                                <th class="px-4 py-3 font-bold text-brand-text uppercase tracking-wider text-[10px]">Title</th>
                                <th class="px-4 py-3 font-bold text-brand-text uppercase tracking-wider text-[10px]">Status</th>
                                <th class="px-4 py-3 font-bold text-brand-text uppercase tracking-wider text-[10px]">Published</th>
                                <th class="px-4 py-3 font-bold text-brand-text uppercase tracking-wider text-[10px] text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-brand-border/50">
                            @foreach($myBlogs as $blog)
                            <tr class="hover:bg-brand-card/20 transition">
                                <td class="px-4 py-3">
                                    @if($blog->status === 'approved')
                                        <a href="{{ route('blogs.show', $blog->slug) }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-brand-text hover:text-brand-accent transition">{{ $blog->title }}</a>
                                    @else
                                        <span class="font-semibold text-brand-text">{{ $blog->title }}</span>
                                    @endif
                                    <p class="text-[10px] text-brand-muted mt-0.5 truncate max-w-xs">{{ $blog->summary }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-bold border
                                        {{ $blog->status === 'approved' ? 'bg-emerald-500/10 text-brand-accent border-emerald-500/20' : 'bg-amber-500/10 text-amber-500 border-amber-500/20' }}">
                                        {{ $blog->status === 'approved' ? 'Published' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-brand-muted">{{ $blog->created_at->format('M d, Y') }}</td>
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
            @endif
        </div>

    @elseif($tab === 'tokens')
        {{-- API Tokens Section --}}
        <div class="space-y-6">
            <div>
                <h2 class="text-sm font-bold text-brand-text">API Tokens</h2>
                <p class="text-xs text-brand-muted mt-1">Use these tokens to submit repositories programmatically via the API.</p>
            </div>

            {{-- Show newly created token once --}}
            @if(session('new_token'))
                <div class="rounded-xl border border-amber-500/20 bg-amber-500/10 p-4">
                    <p class="text-xs font-bold text-amber-600 dark:text-amber-400 mb-2">⚠ Copy your token now — it won't be shown again.</p>
                    <code class="block w-full rounded-lg border border-brand-border bg-brand-bg px-4 py-3 font-mono text-xs text-brand-text break-all select-all">{{ session('new_token') }}</code>
                </div>
            @endif

            {{-- Create Token Form --}}
            <div class="rounded-xl border border-brand-border bg-brand-card/40 p-5">
                <h3 class="text-xs font-bold text-brand-text mb-4">Create New Token</h3>
                <form action="{{ route('dashboard.tokens.create') }}" method="POST" class="flex items-end gap-3">
                    @csrf
                    <div class="flex-1 space-y-1.5">
                        <label for="token_name" class="text-xs font-semibold text-brand-muted">Token Name</label>
                        <input type="text" id="token_name" name="token_name" placeholder="e.g. CI/CD Pipeline, My Script" required
                               class="w-full rounded-lg border border-brand-border bg-brand-bg/40 px-3.5 py-2 text-xs text-brand-text outline-none focus:border-brand-accent/50 focus:ring-1 focus:ring-brand-accent/20">
                    </div>
                    <button type="submit" class="rounded-lg bg-brand-accent px-4 py-2 text-xs font-bold text-white hover:bg-brand-accent-hover transition cursor-pointer shrink-0">
                        Create
                    </button>
                </form>
            </div>

            {{-- Token List --}}
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
            @else
                <p class="text-xs text-brand-muted">No tokens yet.</p>
            @endif

            {{-- API Documentation --}}
            <div class="rounded-xl border border-brand-border bg-brand-card/40 overflow-hidden">
                <div class="px-5 py-4 border-b border-brand-border">
                    <h3 class="text-xs font-bold text-brand-text">API Documentation</h3>
                    <p class="text-[10px] text-brand-muted mt-0.5">Use this endpoint to submit repositories to Ghost Compiler.</p>
                </div>
                <div class="p-5 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="rounded bg-brand-accent/15 border border-brand-accent/20 px-2 py-0.5 text-[10px] font-bold text-brand-accent font-mono">POST</span>
                        <code class="text-xs font-mono text-brand-text">/api/links</code>
                    </div>
                    <p class="text-xs text-brand-muted">Submit a new repository link. Requires Bearer token authentication.</p>

                    <div class="rounded-lg bg-brand-bg border border-brand-border p-4 font-mono text-[11px] text-brand-text leading-relaxed space-y-1">
                        <p class="text-brand-muted">// Example request</p>
                        <p><span class="text-brand-accent">curl</span> -X POST {{ url('/api/links') }} \</p>
                        <p class="pl-4">-H <span class="text-amber-500">"Authorization: Bearer YOUR_TOKEN"</span> \</p>
                        <p class="pl-4">-H <span class="text-amber-500">"Content-Type: application/json"</span> \</p>
                        <p class="pl-4">-d '<span class="text-emerald-500">{</span></p>
                        <p class="pl-8 text-emerald-500">"title": "My Package",</p>
                        <p class="pl-8 text-emerald-500">"description": "A short description",</p>
                        <p class="pl-8 text-emerald-500">"repo_url": "https://github.com/user/repo"</p>
                        <p class="pl-4"><span class="text-emerald-500">}</span>'</p>
                    </div>

                    <div class="space-y-2 text-xs">
                        <p class="font-bold text-brand-text">Parameters:</p>
                        @foreach([['title','string, required, max 255 chars'],['description','string, required, max 1000 chars'],['repo_url','string, required, valid GitHub URL']] as [$p,$d])
                        <div class="flex gap-3 items-baseline">
                            <code class="rounded border border-brand-border bg-brand-bg px-2 py-0.5 text-[11px] text-brand-accent shrink-0">{{ $p }}</code>
                            <span class="text-brand-muted">{{ $d }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    @elseif($tab === 'profile')
        {{-- Profile Section --}}
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
    @endif

</div>
@endsection
