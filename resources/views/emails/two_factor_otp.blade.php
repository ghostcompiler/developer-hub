@extends('emails.layout')

@section('title', 'Your One-Time Password - Ghost Compiler')

@section('content')
<h1>Secure Login Verification</h1>
<p>Hello {{ $name }},</p>
<p>To complete your login, please enter the following 6-digit verification code on the authentication screen:</p>

<div style="text-align: center; margin: 30px 0;">
    <div style="display: inline-block; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px 24px; font-size: 32px; font-family: monospace; font-weight: bold; letter-spacing: 6px; color: #0f172a;">
        {{ $otp }}
    </div>
</div>

<p>This verification code is valid for <strong>10 minutes</strong>. If you did not request this code, please secure your account immediately or contact support.</p>
@endsection
