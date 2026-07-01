@extends('emails.layout')

@section('title', 'Verify Email Address - Ghost Compiler')

@section('content')
<h1>Confirm Your Email Address</h1>
<p>Hello {{ $name }},</p>
<p>Thank you for registering at <strong>ghostcompiler.in</strong>. To complete your account setup and activate your developer access, please click the verification button below:</p>

<div style="text-align: center;">
    <a href="{{ $verificationUrl }}" class="btn">Verify Email Address</a>
</div>

<p>This verification link will expire in 60 minutes. If you did not create a Ghost Compiler account, please disregard this message.</p>

<p style="margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 20px; font-size: 13px; color: #64748b;">
    If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser:<br>
    <a href="{{ $verificationUrl }}" style="color: #10b981; word-break: break-all;">{{ $verificationUrl }}</a>
</p>
@endsection
