<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        body {
            font-family: 'Instrument Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8fafc;
            color: #475569;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .wrapper {
            width: 100%;
            background-color: #f8fafc;
            padding: 40px 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .header {
            background-color: #0d1117;
            padding: 30px;
            text-align: center;
            border-bottom: 2px solid #10b981;
        }
        .header img {
            height: 40px;
            vertical-align: middle;
        }
        .header span {
            color: #ffffff;
            font-family: monospace;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: -0.5px;
            margin-left: 10px;
            vertical-align: middle;
        }
        @media (max-width: 600px) {
            .header span {
                display: none;
            }
        }
        .content {
            padding: 40px 30px;
        }
        @media (max-width: 600px) {
            .wrapper {
                padding: 20px 12px;
            }
            .content {
                padding: 24px 18px;
            }
        }
        h1 {
            color: #0f172a;
            font-size: 22px;
            font-weight: 800;
            margin-top: 0;
            margin-bottom: 20px;
        }
        p {
            font-size: 15px;
            margin-top: 0;
            margin-bottom: 20px;
            color: #475569;
        }
        .btn {
            display: inline-block;
            background-color: #10b981;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 28px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .btn:hover {
            background-color: #059669;
        }
        .footer {
            background-color: #f1f5f9;
            padding: 24px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
        .footer a {
            color: #10b981;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <img src="https://res.cloudinary.com/djgvfl1tv/image/upload/v1780666791/logo_mqnqn4.png" alt="Ghost Compiler Logo" style="height:44px; width:44px; border-radius:10px; vertical-align:middle; margin-right:10px; border:1px solid rgba(16,185,129,0.3);">
                <span>ghostcompiler<span style="color:#10b981;">.in</span></span>
            </div>
            
            <div class="content">
                @yield('content')
            </div>
            
            <div class="footer">
                <p style="margin: 0 0 10px 0; font-size: 12px; color: #94a3b8;">&copy; {{ date('Y') }} ghostcompiler.in. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
