@extends('layouts.app')

@section('title', 'Sign In - Ghost Compiler')

@section('content')
<div class="min-h-[75vh] flex flex-col items-center justify-center px-4 py-12">
    <!-- Logo outside and above the card -->
    <div class="text-center mb-6">
        <a href="{{ route('projects.index') }}" class="inline-block transition hover:opacity-90">
            <img src="https://res.cloudinary.com/djgvfl1tv/image/upload/v1780666791/logo_mqnqn4.png" alt="Ghost Compiler Logo" class="h-12 w-12 rounded-xl object-contain border border-brand-border shadow-md">
        </a>
        <h1 class="text-xl font-light text-brand-text mt-4 tracking-tight">Sign in to Ghost Compiler</h1>
    </div>

    <!-- Alert Messages -->
    @if(session('success') || session('error') || $errors->any())
        <div class="w-full max-w-[340px] mb-4">
            @if(session('success'))
                <div class="rounded-lg border border-emerald-500/20 bg-emerald-500/10 p-3 text-xs text-brand-accent">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="rounded-lg border border-rose-500/20 bg-rose-500/10 p-3 text-xs text-rose-500">
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="rounded-lg border border-rose-500/20 bg-rose-500/10 p-3 text-xs text-rose-500">
                    <ul class="list-disc pl-4 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <!-- Login Card (GitHub-Style) -->
    <div class="w-full max-w-[340px] rounded-lg border border-brand-border bg-brand-card p-5 shadow-sm">
        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            
            <!-- Email -->
            <div class="space-y-1.5">
                <label for="email" class="block text-xs font-semibold text-brand-text">Username or email address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="w-full rounded-md border border-brand-border bg-brand-bg px-3 py-1.5 text-xs text-brand-text outline-none focus:border-brand-accent focus:ring-1 focus:ring-brand-accent/30 transition">
            </div>

            <!-- Password -->
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <label for="password" class="block text-xs font-semibold text-brand-text">Password</label>
                    <a href="{{ route('password.request') }}" class="text-[11px] font-medium text-brand-accent hover:underline">Forgot password?</a>
                </div>
                <input type="password" id="password" name="password" required class="w-full rounded-md border border-brand-border bg-brand-bg px-3 py-1.5 text-xs text-brand-text outline-none focus:border-brand-accent focus:ring-1 focus:ring-brand-accent/30 transition">
            </div>

            <!-- Remember me (optional but kept minimal) -->
            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" id="remember" name="remember" class="h-3.5 w-3.5 rounded border-brand-border bg-brand-bg text-brand-accent focus:ring-brand-accent/20 cursor-pointer">
                <label for="remember" class="text-xs text-brand-muted select-none cursor-pointer">Remember me</label>
            </div>

            <button type="submit" class="w-full rounded-md bg-emerald-600 hover:bg-emerald-500 py-1.5 text-xs font-bold text-white transition active:scale-[0.99] shadow-sm cursor-pointer">
                Sign In
            </button>

            @php
                $showGithub = !empty(config('services.github.client_id')) && !empty(config('services.github.client_secret'));
                $showGoogle = !empty(config('services.google.client_id')) && !empty(config('services.google.client_secret'));
            @endphp

            @if($showGithub || $showGoogle)
                <!-- Divider -->
                <div class="relative flex py-2 items-center gap-1">
                    <div class="flex-grow border-t border-brand-border/60"></div>
                    <span class="flex-shrink mx-3 text-[10px] text-brand-muted uppercase tracking-wider font-semibold">or continue with</span>
                    <div class="flex-grow border-t border-brand-border/60"></div>
                </div>

                <!-- Social Logins -->
                <div class="space-y-2">
                    @if($showGithub)
                        <a href="{{ route('auth.social.redirect', 'github') }}" class="w-full flex items-center justify-center gap-2 rounded-md border border-brand-border bg-brand-bg/50 hover:bg-brand-bg py-2 text-xs font-semibold text-brand-text transition hover:border-brand-accent/50 cursor-pointer">
                            <svg class="h-4 w-4 fill-current" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 0C3.58 0 0 3.58 0 8C0 11.54 2.29 14.53 5.47 15.59C5.87 15.66 6.02 15.42 6.02 15.21C6.02 15.02 6.01 14.39 6.01 13.72C4 14.09 3.48 13.23 3.32 12.78C3.23 12.55 2.84 11.84 2.5 11.65C2.22 11.5 1.82 11.13 2.49 11.12C3.12 11.11 3.57 11.7 3.72 11.94C4.44 13.15 5.59 12.81 6.05 12.6C6.12 12.08 6.33 11.73 6.56 11.53C4.78 11.33 2.92 10.64 2.92 7.58C2.92 6.71 3.23 5.99 3.74 5.43C3.66 5.23 3.38 4.41 3.82 3.31C3.82 3.31 4.49 3.1 6.02 4.13C6.66 3.95 7.34 3.86 8.02 3.86C8.7 3.86 9.38 3.95 10.02 4.13C11.55 3.09 12.22 3.31 12.22 3.31C12.66 4.41 12.38 5.23 12.3 5.43C12.81 5.99 13.12 6.7 13.12 7.58C13.12 10.65 11.25 11.33 9.47 11.53C9.76 11.78 10.01 12.26 10.01 13.01C10.01 14.08 10 14.94 10 15.21C10 15.42 10.15 15.67 10.55 15.59C13.71 14.53 16 11.53 16 8C16 3.58 12.42 0 8 0Z"/>
                            </svg>
                            <span>Sign in with GitHub</span>
                        </a>
                    @endif
                    @if($showGoogle)
                        <a href="{{ route('auth.social.redirect', 'google') }}" class="w-full flex items-center justify-center gap-2 rounded-md border border-brand-border bg-brand-bg/50 hover:bg-brand-bg py-2 text-xs font-semibold text-brand-text transition hover:border-brand-accent/50 cursor-pointer">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"/>
                            </svg>
                            <span>Sign in with Google</span>
                        </a>
                    @endif
                </div>
            @endif
        </form>
    </div>

    <!-- Register Box (GitHub-Style) -->
    <div class="w-full max-w-[340px] mt-4 rounded-lg border border-brand-border bg-brand-card/45 p-4 text-center text-xs text-brand-muted">
        New to Ghost Compiler? 
        <a href="{{ route('register') }}" class="font-bold text-brand-accent hover:underline ml-1">Create an account</a>
    </div>

    <!-- Policy Links -->
    <div class="w-full max-w-[340px] mt-6 flex justify-center gap-4 text-[10px] text-brand-muted">
        <a href="{{ route('policies.privacy') }}" class="hover:text-brand-text transition hover:underline">Privacy Policy</a>
        <span>&bull;</span>
        <a href="{{ route('policies.terms') }}" class="hover:text-brand-text transition hover:underline">Terms of Service</a>
        <span>&bull;</span>
        <a href="{{ route('policies.conditions') }}" class="hover:text-brand-text transition hover:underline">Terms & Conditions</a>
    </div>
</div>
@endsection
