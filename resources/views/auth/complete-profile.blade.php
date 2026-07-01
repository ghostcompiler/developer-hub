@extends('layouts.app')

@section('title', 'Complete Profile - Ghost Compiler')

@section('content')
<div class="min-h-[75vh] flex flex-col items-center justify-center px-4 py-12">
    <!-- Logo outside and above the card -->
    <div class="text-center mb-6">
        <a href="{{ route('projects.index') }}" class="inline-block transition hover:opacity-90">
            <img src="{{ asset('images/logo.png') }}" alt="Ghost Compiler Logo" class="h-12 w-12 rounded-xl object-contain border border-brand-border shadow-md" onerror="this.src='https://res.cloudinary.com/djgvfl1tv/image/upload/v1780666791/logo_mqnqn4.png'">
        </a>
        <h1 class="text-xl font-light text-brand-text mt-4 tracking-tight">Complete Your Profile</h1>
        <p class="text-xs text-brand-muted mt-1 max-w-[280px]">Please specify your Date of Birth to verify your age policy compliance.</p>
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

    <!-- Profile Completion Card (GitHub-Style) -->
    <div class="w-full max-w-[340px] rounded-lg border border-brand-border bg-brand-card p-5 shadow-sm">
        <form action="{{ route('profile.complete.store') }}" method="POST" class="space-y-4">
            @csrf
            
            <!-- Date of birth -->
            <div class="space-y-1.5">
                <label for="dob" class="block text-xs font-semibold text-brand-text">Date of birth</label>
                <input type="date" id="dob" name="dob" value="{{ old('dob') }}" required autofocus class="w-full rounded-md border border-brand-border bg-brand-bg px-3 py-1.5 text-xs text-brand-text outline-none focus:border-brand-accent focus:ring-1 focus:ring-brand-accent/30 transition">
                <p class="text-[10px] text-brand-muted leading-tight">We enforce a strict 18+ policy for index submissions and developer publishing.</p>
            </div>

            <button type="submit" class="w-full rounded-md bg-emerald-600 hover:bg-emerald-500 py-1.5 text-xs font-bold text-white transition active:scale-[0.99] shadow-sm cursor-pointer">
                Complete Setup
            </button>
        </form>
    </div>
</div>
@endsection
