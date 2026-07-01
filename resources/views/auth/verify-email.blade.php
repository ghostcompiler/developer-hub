@extends('layouts.app')

@section('title', 'Verify Email - Ghost Compiler')

@section('content')
<div class="min-h-[75vh] flex flex-col items-center justify-center px-4 py-12">
    <!-- Logo outside and above the card -->
    <div class="text-center mb-6">
        <a href="{{ route('projects.index') }}" class="inline-block transition hover:opacity-90">
            <img src="https://res.cloudinary.com/djgvfl1tv/image/upload/v1780666791/logo_mqnqn4.png" alt="Ghost Compiler Logo" class="h-12 w-12 rounded-xl object-contain border border-brand-border shadow-md">
        </a>
        <h1 class="text-xl font-light text-brand-text mt-4 tracking-tight">Verify your email address</h1>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="w-full max-w-[340px] mb-4">
            <div class="rounded-lg border border-emerald-500/20 bg-emerald-500/10 p-3 text-xs text-brand-accent">
                {{ session('success') }}
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="w-full max-w-[340px] mb-4">
            <div class="rounded-lg border border-rose-500/20 bg-rose-500/10 p-3 text-xs text-rose-500">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Verification Card (GitHub-Style) -->
    <div class="w-full max-w-[340px] rounded-lg border border-brand-border bg-brand-card p-5 shadow-sm space-y-4">
        <p class="text-xs text-brand-muted leading-relaxed text-center">
            Before signing in, please click the secure confirmation link sent to your email address:
        </p>

        <div class="rounded border border-brand-border bg-brand-bg/60 p-3 text-center text-xs font-mono font-bold text-brand-text break-all">
            {{ $email }}
        </div>

        <form action="{{ route('verification.send') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">

            <button type="submit" class="w-full rounded-md bg-emerald-600 hover:bg-emerald-500 py-1.5 text-xs font-bold text-white transition active:scale-[0.99] shadow-sm cursor-pointer">
                Resend verification email
            </button>
        </form>
    </div>

    <!-- Return Link (GitHub-Style) -->
    <div class="w-full max-w-[340px] mt-4 rounded-lg border border-brand-border bg-brand-card/45 p-4 text-center text-xs text-brand-muted">
        Already verified? 
        <a href="{{ route('login') }}" class="font-bold text-brand-accent hover:underline ml-1">Sign In</a>
    </div>
</div>
@endsection
