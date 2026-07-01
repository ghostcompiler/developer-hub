@extends('layouts.app')

@section('title', 'Access Denied - Ghost Compiler')

@section('content')
<div class="min-h-[75vh] flex flex-col items-center justify-center px-4 py-12">
    <!-- Icon and Header -->
    <div class="text-center mb-6">
        <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-rose-500/10 ring-1 ring-rose-500/20 mb-4 animate-pulse">
            <svg class="h-8 w-8 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-brand-text tracking-tight">Account Suspended</h1>
        <p class="mt-2 text-sm text-brand-muted max-w-md mx-auto">
            Suspicious activity cannot be tolerated. You cannot access your account now.
        </p>
    </div>

    <!-- Details Box (GitHub-Style Card) -->
    <div class="w-full max-w-[420px] rounded-lg border border-rose-500/20 bg-brand-card p-6 shadow-sm text-center">
        <p class="text-xs text-rose-400 font-semibold uppercase tracking-wider mb-2">Access Restriced (Underage Account)</p>
        <p class="text-xs text-brand-muted leading-relaxed mb-4">
            Our security systems detected that your registered Date of Birth is under 18 years of age. Bypassing minimum age requirements or manipulating registration parameters violates our Terms of Service.
        </p>
        <div class="border-t border-brand-border/60 pt-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full rounded-md bg-rose-600 hover:bg-rose-500 py-1.5 text-xs font-bold text-white transition active:scale-[0.99] shadow-sm cursor-pointer">
                    Sign Out
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
