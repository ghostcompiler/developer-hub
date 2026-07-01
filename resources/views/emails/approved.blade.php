@extends('emails.layout')

@section('title', 'Your Submission was Approved')

@section('content')
<h1>🎉 Your {{ ucfirst($type) }} was Approved!</h1>
<p>Hello {{ $name }},</p>
<p>Great news! Your <strong>{{ $type }}</strong> titled <strong>"{{ $title }}"</strong> has been reviewed and approved by the Ghost Compiler team.</p>
<p>It is now live and publicly visible on the platform.</p>

<div style="text-align:center;margin:24px 0;">
    <a href="{{ $viewUrl }}" class="btn">View Your {{ ucfirst($type) }}</a>
</div>

<p style="font-size:13px;color:#94a3b8;">You can manage all your submissions from your <a href="{{ $dashboardUrl }}" style="color:#10b981;">developer dashboard</a>.</p>
@endsection
