<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && !$user->isActive()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Your account has been deactivated.'], 403);
            }

            return redirect()->route('login')->with('error', 'Your account has been deactivated. Please contact an administrator.');
        }

        if ($user && !$request->session()->get('two_factor_verified')) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Two-factor authentication required.'], 403);
            }
            // Store intended URL
            $request->session()->put('url.intended', $request->url());
            return redirect()->route('two-factor.challenge');
        }

        return $next($request);
    }
}
