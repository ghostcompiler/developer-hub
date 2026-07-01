<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    /**
     * Redirect to the provider's OAuth page.
     */
    public function redirectToProvider(string $provider)
    {
        if (!in_array($provider, ['google', 'github'])) {
            abort(404);
        }

        $clientId = config("services.{$provider}.client_id");
        $clientSecret = config("services.{$provider}.client_secret");

        if (empty($clientId) || empty($clientSecret)) {
            return redirect()->route('login')->with('error', "Social login via " . ucfirst($provider) . " is not configured by the administrator yet.");
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the provider callback.
     */
    public function handleProviderCallback(Request $request, string $provider)
    {
        if (!in_array($provider, ['google', 'github'])) {
            abort(404);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Throwable $e) {
            return redirect()->route('login')->with('error', 'Authentication failed or was canceled.');
        }

        $email = $socialUser->getEmail();
        if (empty($email)) {
            return redirect()->route('login')->with('error', 'Could not retrieve email address from social provider.');
        }

        // Verify email is verified if logging in via Google
        if ($provider === 'google') {
            $rawUser = $socialUser->getRaw();
            $emailVerified = $rawUser['email_verified'] ?? null;
            if ($emailVerified !== true && $emailVerified !== 'true' && $emailVerified !== 1 && $emailVerified !== '1') {
                return redirect()->route('login')->with('error', 'Your Google email address must be verified to sign in.');
            }
        }

        // 1. Check if user already exists with this provider ID
        $user = User::where("{$provider}_id", $socialUser->getId())->first();

        if (!$user) {
            // 2. Check if a user with the same email exists
            $user = User::where('email', $email)->first();

            if ($user) {
                // Link the provider ID to the existing account
                $user->update(["{$provider}_id" => $socialUser->getId()]);
            } else {
                // 3. Create a brand new user
                $isFirstUser = User::count() === 0;
                $role = $isFirstUser ? 'admin' : 'user';

                $user = User::create([
                    'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'OAuth User',
                    'email' => $email,
                    'password' => null,
                    "{$provider}_id" => $socialUser->getId(),
                    'role' => $role,
                    'status' => 'active',
                ]);
                
                $user->email_verified_at = now();
                $user->save();
            }
        }

        // 4. Verify status
        if (!$user->isActive()) {
            return redirect()->route('login')->with('error', 'Your account is deactivated. Please contact an administrator.');
        }

        // 5. Log in the user
        Auth::login($user);
        $request->session()->regenerate();

        // 6. Handle Two-Factor Authentication (required for all users)
        return redirect()->route('two-factor.challenge');
    }
}
