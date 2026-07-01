@extends('layouts.dashboard')

@section('title', 'Security & 2FA - Ghost Compiler')
@section('page-title', 'Security & Two-Factor Authentication')

@section('content')
<div class="max-w-2xl space-y-8">

    <!-- 2FA Status Card -->
    <div class="rounded-xl border border-brand-border bg-brand-card/40 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-brand-border">
            <div>
                <h2 class="text-sm font-bold text-brand-text">Two-Factor Authentication</h2>
                <p class="text-xs text-brand-muted mt-0.5">Add an extra layer of security to your account using a TOTP authenticator app.</p>
            </div>
            @if($user->hasTwoFactorEnabled())
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 px-3 py-1 text-[11px] font-bold text-brand-accent">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand-accent"></span> Enabled
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-500/10 border border-rose-500/20 px-3 py-1 text-[11px] font-bold text-rose-500">
                    <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Disabled
                </span>
            @endif
        </div>

        <div class="px-6 py-5">
            @if($user->hasTwoFactorEnabled())
                <p class="text-sm text-brand-muted mb-4">Two-factor authentication is active on your account. To disable it, confirm your current password below.</p>

                <form action="{{ route('dashboard.two-factor.disable') }}" method="POST" class="space-y-4 max-w-sm">
                    @csrf
                    @error('current_password')
                        <div class="rounded-lg border border-rose-500/20 bg-rose-500/10 p-3 text-xs text-rose-500">{{ $message }}</div>
                    @enderror
                    <div class="space-y-1.5">
                        <label for="disable_password" class="text-xs font-semibold text-brand-muted">Current Password</label>
                        <input type="password" id="disable_password" name="current_password" required
                               class="w-full rounded-lg border border-brand-border bg-brand-bg/40 px-3.5 py-2 text-xs text-brand-text outline-none focus:border-brand-accent/50 focus:ring-1 focus:ring-brand-accent/20">
                    </div>
                    <button type="submit" onclick="return confirm('Disable two-factor authentication?');"
                            class="rounded-lg bg-rose-600 hover:bg-rose-500 px-4 py-2 text-xs font-bold text-white transition cursor-pointer">
                        Disable 2FA
                    </button>
                </form>

                @if(session('success') && str_contains(session('success'), '2FA enabled'))
                    @php
                        $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes ?? encrypt('[]')), true);
                    @endphp
                    @if(!empty($recoveryCodes))
                    <div class="mt-6 rounded-xl border border-amber-500/20 bg-amber-500/10 p-4">
                        <p class="text-xs font-bold text-amber-500 mb-3">⚠ Save your recovery codes now — they won't be shown again.</p>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($recoveryCodes as $code)
                                <code class="block rounded bg-brand-bg border border-brand-border px-3 py-1.5 text-xs font-mono text-brand-text">{{ $code }}</code>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endif

            @else
                <p class="text-sm text-brand-muted mb-5">Scan the QR code with your authenticator app (Google Authenticator, Authy, 1Password, etc.), then enter the 6-digit code to confirm setup.</p>

                @if($secret)
                    <div class="mb-5 flex flex-col items-start gap-4">
                        <!-- QR Code -->
                        <div class="rounded-xl border-2 border-brand-border bg-white p-3">
                            {!! $qrCodeSvg !!}
                        </div>
                        <div>
                            <p class="text-xs text-brand-muted mb-1">Or enter this secret key manually:</p>
                            <code class="block rounded-lg border border-brand-border bg-brand-bg px-4 py-2.5 font-mono text-sm text-brand-text tracking-widest">{{ $secret }}</code>
                        </div>
                    </div>

                    <form action="{{ route('dashboard.two-factor.enable') }}" method="POST" class="space-y-4 max-w-sm">
                        @csrf
                        <input type="hidden" name="secret" value="{{ $secret }}">

                        @error('current_password')
                            <div class="rounded-lg border border-rose-500/20 bg-rose-500/10 p-3 text-xs text-rose-500">{{ $message }}</div>
                        @enderror
                        @error('code')
                            <div class="rounded-lg border border-rose-500/20 bg-rose-500/10 p-3 text-xs text-rose-500">{{ $message }}</div>
                        @enderror

                        <div class="space-y-1.5">
                            <label for="current_pwd" class="text-xs font-semibold text-brand-muted">Current Password</label>
                            <input type="password" id="current_pwd" name="current_password" required
                                   class="w-full rounded-lg border border-brand-border bg-brand-bg/40 px-3.5 py-2 text-xs text-brand-text outline-none focus:border-brand-accent/50 focus:ring-1 focus:ring-brand-accent/20">
                        </div>

                        <div class="space-y-1.5">
                            <label for="totp_code" class="text-xs font-semibold text-brand-muted">6-Digit Code from App</label>
                            <input type="text" id="totp_code" name="code" inputmode="numeric" maxlength="6"
                                   placeholder="000000"
                                   class="w-full rounded-lg border border-brand-border bg-brand-bg/40 px-3.5 py-2 text-center font-mono text-lg font-bold tracking-[0.4em] text-brand-text outline-none focus:border-brand-accent/50 focus:ring-1 focus:ring-brand-accent/20">
                        </div>

                        <button type="submit" class="rounded-lg bg-brand-accent hover:bg-brand-accent-hover px-4 py-2.5 text-xs font-bold text-white transition shadow-md shadow-brand-accent/15 cursor-pointer">
                            Enable 2FA
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </div>

</div>
@endsection
