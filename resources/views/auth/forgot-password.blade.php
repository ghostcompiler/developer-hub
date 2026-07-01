@extends('layouts.app')

@section('title', 'Reset Password - Ghost Compiler')

@section('content')
<div class="container mx-auto min-h-[75vh] flex flex-col items-center justify-center px-4 py-12">
    <!-- Logo outside and above the card -->
    <div class="text-center mb-6">
        <a href="{{ route('projects.index') }}" class="inline-block transition hover:opacity-90">
            <img src="https://res.cloudinary.com/djgvfl1tv/image/upload/v1780666791/logo_mqnqn4.png" alt="Ghost Compiler Logo" class="h-12 w-12 rounded-xl object-contain border border-brand-border shadow-md">
        </a>
        <h1 class="text-xl font-light text-brand-text mt-4 tracking-tight">Reset your password</h1>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-4 w-full sm:w-[340px]">
            <div class="rounded-lg border border-emerald-500/20 bg-emerald-500/10 p-3 text-xs text-brand-accent">
                {{ session('success') }}
            </div>
        </div>
    @endif
    
    @if($errors->any())
        <div class="mb-4 w-full sm:w-[340px]">
            <div class="rounded-lg border border-rose-500/20 bg-rose-500/10 p-3 text-xs text-rose-500">
                <ul class="list-disc pl-4 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Forgot Password Card (GitHub-Style) -->
    <div class="w-full rounded-lg border border-brand-border bg-brand-card p-5 shadow-sm sm:w-[340px]">
        <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="space-y-1.5">
                <label for="email" class="block text-xs font-semibold text-brand-text">Enter your email address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com" class="w-full rounded-md border border-brand-border bg-brand-bg px-3 py-1.5 text-xs text-brand-text outline-none focus:border-brand-accent focus:ring-1 focus:ring-brand-accent/30 transition">
            </div>

            <button type="submit" class="w-full rounded-md bg-emerald-600 hover:bg-emerald-500 py-1.5 text-xs font-bold text-white transition active:scale-[0.99] shadow-sm cursor-pointer mt-2">
                Send password reset email
            </button>
        </form>
    </div>

    <!-- Return Link (GitHub-Style) -->
    <div class="mt-4 w-full rounded-lg border border-brand-border bg-brand-card/45 p-4 text-center text-xs text-brand-muted sm:w-[340px]">
        Remembered your password? 
        <a href="{{ route('login') }}" class="font-bold text-brand-accent hover:underline ml-1">Sign In</a>
    </div>
</div>
@endsection
