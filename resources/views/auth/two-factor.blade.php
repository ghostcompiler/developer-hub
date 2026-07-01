@extends('layouts.app')

@section('title', 'Two-Factor Authentication - Ghost Compiler')

@section('content')
@php
    $user = Auth::user();
    $isTotp = $user && $user->hasTwoFactorEnabled();
    $email = $user ? $user->email : '';
    $parts = explode('@', $email);
    if (count($parts) === 2) {
        $name = $parts[0];
        $domain = $parts[1];
        $obfuscatedName = substr($name, 0, 1) . str_repeat('*', max(1, strlen($name) - 2)) . (strlen($name) > 1 ? substr($name, -1) : '');
        $obfuscatedEmail = $obfuscatedName . '@' . $domain;
    } else {
        $obfuscatedEmail = $email;
    }
@endphp

<div class="min-h-[75vh] flex flex-col items-center justify-center px-4 py-12">
    <div class="text-center mb-6">
        <a href="{{ route('projects.index') }}" class="inline-block transition hover:opacity-90">
            <img src="https://res.cloudinary.com/djgvfl1tv/image/upload/v1780666791/logo_mqnqn4.png" alt="Ghost Compiler Logo" class="h-12 w-12 rounded-xl object-contain border border-brand-border shadow-md mx-auto">
        </a>
        <h1 class="text-xl font-light text-brand-text mt-4 tracking-tight">Two-Factor Authentication</h1>
        <p class="text-xs text-brand-muted mt-1">
            @if($isTotp)
                Enter the 6-digit code from your authenticator app
            @else
                We've sent a 6-digit verification code to <span class="font-semibold text-brand-text">{{ $obfuscatedEmail }}</span>
            @endif
        </p>
    </div>

    @if(session('success'))
        <div class="w-full max-w-[340px] mb-4">
            <div class="rounded-lg border border-emerald-500/20 bg-emerald-500/10 p-3 text-xs text-emerald-500 text-center">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="w-full max-w-[340px] mb-4">
            <div class="rounded-lg border border-rose-500/20 bg-rose-500/10 p-3 text-xs text-rose-500 text-center">
                {{ $errors->first() }}
            </div>
        </div>
    @endif

    <div class="w-full max-w-[340px] rounded-lg border border-brand-border bg-brand-card p-5 shadow-sm">
        <form action="{{ route('two-factor.verify') }}" method="POST" class="space-y-4">
            @csrf
            <div class="space-y-1.5">
                <label for="code" class="text-xs font-semibold text-brand-muted">Authentication Code</label>
                <input type="text" id="code" name="code" inputmode="numeric" pattern="[0-9 ]*"
                       autocomplete="one-time-code" maxlength="8" placeholder="000000"
                       class="w-full rounded-lg border border-brand-border bg-brand-bg/40 px-3.5 py-2.5 text-center text-xl font-mono font-bold tracking-[0.5em] text-brand-text outline-none focus:border-brand-accent/50 focus:ring-1 focus:ring-brand-accent/20"
                       autofocus required>
                @if($isTotp)
                    <p class="text-[10px] text-brand-muted">You can also enter a recovery code.</p>
                @endif
            </div>
            <button type="submit" class="w-full rounded-lg bg-brand-accent px-4 py-2.5 text-sm font-bold text-white hover:bg-brand-accent-hover transition shadow-md shadow-brand-accent/15 cursor-pointer">
                Verify & Continue
            </button>
        </form>
    </div>

    @if(!$isTotp)
        <div class="w-full max-w-[340px] mt-4 rounded-lg border border-brand-border bg-brand-card/45 p-4 text-center text-xs text-brand-muted">
            Didn't receive the code?
            <form action="{{ route('two-factor.resend') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="font-bold text-brand-accent hover:underline ml-1 cursor-pointer">Resend Code</button>
            </form>
        </div>
    @endif

    <div class="w-full max-w-[340px] mt-4 rounded-lg border border-brand-border bg-brand-card/45 p-4 text-center text-xs text-brand-muted">
        Not you?
        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="font-bold text-brand-accent hover:underline ml-1 cursor-pointer">Sign out</button>
        </form>
    </div>
</div>
@endsection
