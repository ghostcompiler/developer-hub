@extends('layouts.app')

@section('title', 'Reset Password - Ghost Compiler')

@section('content')
<div class="min-h-[75vh] flex flex-col items-center justify-center px-4 py-12">
    <!-- Logo outside and above the card -->
    <div class="text-center mb-6">
        <a href="{{ route('projects.index') }}" class="inline-block transition hover:opacity-90">
            <img src="https://res.cloudinary.com/djgvfl1tv/image/upload/v1780666791/logo_mqnqn4.png" alt="Ghost Compiler Logo" class="h-12 w-12 rounded-xl object-contain border border-brand-border shadow-md">
        </a>
        <h1 class="text-xl font-light text-brand-text mt-4 tracking-tight">Choose a new password</h1>
    </div>

    <!-- Alert Messages -->
    @if($errors->any())
        <div class="w-full max-w-[340px] mb-4">
            <div class="rounded-lg border border-rose-500/20 bg-rose-500/10 p-3 text-xs text-rose-500">
                <ul class="list-disc pl-4 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Reset Password Card (GitHub-Style) -->
    <div class="w-full max-w-[340px] rounded-lg border border-brand-border bg-brand-card p-5 shadow-sm">
        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf
            
            <input type="hidden" name="token" value="{{ $token }}">
            
            <!-- Email (readonly) -->
            <div class="space-y-1.5">
                <label for="email" class="block text-xs font-semibold text-brand-text font-medium">Email address</label>
                <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" required readonly class="w-full rounded-md border border-brand-border bg-brand-bg/40 px-3 py-1.5 text-xs text-brand-text/60 outline-none cursor-not-allowed">
            </div>

            <!-- Password -->
            <div class="space-y-1.5">
                <label for="password" class="block text-xs font-semibold text-brand-text">New password</label>
                <input type="password" id="password" name="password" required autofocus placeholder="Min. 8 characters" class="w-full rounded-md border border-brand-border bg-brand-bg px-3 py-1.5 text-xs text-brand-text outline-none focus:border-brand-accent focus:ring-1 focus:ring-brand-accent/30 transition">
            </div>

            <!-- Password Confirmation -->
            <div class="space-y-1.5">
                <label for="password_confirmation" class="block text-xs font-semibold text-brand-text">Confirm new password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Repeat new password" class="w-full rounded-md border border-brand-border bg-brand-bg px-3 py-1.5 text-xs text-brand-text outline-none focus:border-brand-accent focus:ring-1 focus:ring-brand-accent/30 transition">
            </div>

            <button type="submit" class="w-full rounded-md bg-emerald-600 hover:bg-emerald-500 py-1.5 text-xs font-bold text-white transition active:scale-[0.99] shadow-sm cursor-pointer mt-2">
                Update Password
            </button>
        </form>
    </div>
</div>
@endsection
