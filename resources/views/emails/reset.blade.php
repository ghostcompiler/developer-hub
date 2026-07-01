@extends('emails.layout')

@section('title', 'Reset Password - Ghost Compiler')

@section('content')
<h1>Reset Your Password</h1>
<p>Hello {{ $name }},</p>
<p>We received a request to reset the password for your Ghost Compiler developer account. Click the button below to choose a new password:</p>

<div style="text-align: center;">
    <a href="{{ $resetUrl }}" class="btn">Reset Password</a>
</div>

<p>This password reset link will expire in 60 minutes. If you did not request a password reset, you can safely ignore this email.</p>

<p style="margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 20px; font-size: 13px; color: #64748b;">
    If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:<br>
    <a href="{{ $resetUrl }}" style="color: #10b981; word-break: break-all;">{{ $resetUrl }}</a>
</p>
@endsection
