@extends('emails.layout')

@section('title', 'New Blog Post Submitted')

@section('content')
<h1>New Blog Post Submitted</h1>
<p>Hello {{ $adminName }},</p>
<p><strong>{{ $submitter }}</strong> has submitted a new blog post for review on Ghost Compiler.</p>

<table style="width:100%;border-collapse:collapse;margin:20px 0;">
    <tr>
        <td style="padding:10px 12px;background:#f8fafc;border:1px solid #e2e8f0;font-size:13px;font-weight:bold;width:120px;">Title</td>
        <td style="padding:10px 12px;border:1px solid #e2e8f0;font-size:13px;">{{ $blogTitle }}</td>
    </tr>
    <tr>
        <td style="padding:10px 12px;background:#f8fafc;border:1px solid #e2e8f0;font-size:13px;font-weight:bold;">Author</td>
        <td style="padding:10px 12px;border:1px solid #e2e8f0;font-size:13px;">{{ $submitter }}</td>
    </tr>
</table>

<div style="text-align:center;">
    <a href="{{ $dashboardUrl }}" class="btn">Review in Admin Panel</a>
</div>

<p style="font-size:13px;color:#94a3b8;margin-top:24px;">You are receiving this because you are an administrator on Ghost Compiler.</p>
@endsection
