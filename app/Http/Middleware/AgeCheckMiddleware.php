<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LinkedRepo;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class AgeCheckMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user) {
            if (empty($user->dob)) {
                // Allow visiting completion routes and logging out
                if ($request->routeIs('profile.complete') || $request->routeIs('profile.complete.store') || $request->routeIs('logout')) {
                    return $next($request);
                }

                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Profile setup incomplete. Please provide your Date of Birth.'
                    ], 403);
                }

                return redirect()->route('profile.complete');
            }

            $dob = Carbon::parse($user->dob);
            if ($dob->diffInYears(now(), false) < 18) {
                // 1. Make all user's repos pending so they do not index
                LinkedRepo::where('user_id', $user->id)
                    ->where('status', '!=', 'pending')
                    ->update(['status' => 'pending']);

                // 2. If it's an API request, return 403 JSON
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Suspicious activity cannot be tolerated. You cannot access your account now.'
                    ], 403);
                }

                // Allow visiting lockscreen and logging out
                if ($request->routeIs('lockscreen') || $request->routeIs('logout')) {
                    return $next($request);
                }

                // Redirect to lock screen
                return redirect()->route('lockscreen');
            }
        }

        return $next($request);
    }
}
