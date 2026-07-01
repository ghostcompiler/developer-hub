<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use App\Jobs\SendQueuedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showRegister()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin() ? redirect()->route('admin.dashboard') : redirect()->route('dashboard');
        }
        if (Setting::get('registration_enabled', '1') !== '1') {
            return redirect()->route('login')->with('error', 'Registration is currently disabled.');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin() ? redirect()->route('admin.dashboard') : redirect()->route('dashboard');
        }
        if (Setting::get('registration_enabled', '1') !== '1') {
            return redirect()->route('login')->with('error', 'Registration is currently disabled.');
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'dob'      => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
        ], [
            'dob.before_or_equal' => 'You must be at least 18 years old to register.',
        ]);

        $isFirstUser = User::count() === 0;
        $role        = $isFirstUser ? 'admin' : 'user';
        $verifiedAt  = $isFirstUser ? now() : null;

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'dob'      => $request->dob,
            'role'     => $role,
            'status'   => 'active',
        ]);

        $user->email_verified_at = $verifiedAt;
        $user->save();

        if ($isFirstUser) {
            Auth::login($user);
            return redirect()->route('dashboard')->with('success', 'Admin registration successful!');
        }

        $this->dispatchVerificationEmail($user);

        session(['verify_email' => $user->email]);

        return redirect()->route('verification.notice', ['email' => $user->email])
            ->with('success', 'Registration successful! Please check your email to verify your account.');
    }

    public function showVerifyNotice(Request $request)
    {
        if (Auth::check() && Auth::user()->email_verified_at !== null) {
            return Auth::user()->isAdmin() ? redirect()->route('admin.dashboard') : redirect()->route('dashboard');
        }

        $email = $request->query('email') ?? session('verify_email');
        if ($email) {
            session(['verify_email' => $email]);
        }

        return view('auth.verify-email', compact('email'));
    }

    public function verify(Request $request, $id, $hash)
    {
        // Enforce signed URL expiry — must be checked BEFORE any DB lookup
        if (!$request->hasValidSignature()) {
            abort(403, 'This verification link has expired or is invalid. Please request a new one.');
        }

        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->email))) {
            abort(403, 'Invalid verification signature.');
        }

        if ($user->email_verified_at !== null) {
            return redirect()->route('login')->with('success', 'Email already verified. Please sign in.');
        }

        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('login')->with('success', 'Email verified! You can now log in.');
    }

    public function resendVerification(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if ($user && $user->email_verified_at === null) {
            $this->dispatchVerificationEmail($user);
            return back()->with('success', 'Verification link has been resent to your email.');
        }

        return back()->with('error', 'Unable to resend. Email may already be verified.');
    }

    protected function dispatchVerificationEmail(User $user): void
    {
        try {
            $url = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id' => $user->id, 'hash' => sha1($user->email)]
            );

            SendQueuedMail::dispatch(
                $user->email,
                'Confirm Your Email - Ghost Compiler',
                'emails.verify',
                ['name' => $user->name, 'verificationUrl' => $url]
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to dispatch verification email', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
        }
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin() ? redirect()->route('admin.dashboard') : redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin() ? redirect()->route('admin.dashboard') : redirect()->route('dashboard');
        }

        $credentials = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (!$user->isActive()) {
                return back()->withErrors([
                    'email' => 'Your account is deactivated. Please contact an administrator.',
                ])->onlyInput('email');
            }

            if ($user->email_verified_at === null) {
                session(['verify_email' => $user->email]);
                return redirect()->route('verification.notice', ['email' => $user->email])->with([
                    'error' => 'Please verify your email address before signing in.',
                ]);
            }
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $authUser = Auth::user();

            // Two-Factor Authentication is required for all users
            return redirect()->route('two-factor.challenge');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('projects.index')->with('success', 'Signed out successfully.');
    }

    // --- Forgot Password ---

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $token = Str::random(60);
            $hashedToken = hash('sha256', $token);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                ['token' => $hashedToken, 'created_at' => now()]
            );

            $url = route('password.reset', ['token' => $token, 'email' => $request->email]);

            try {
                SendQueuedMail::dispatch(
                    $user->email,
                    'Reset Your Password - Ghost Compiler',
                    'emails.reset',
                    ['name' => $user->name, 'resetUrl' => $url]
                );
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Failed to dispatch password reset email', [
                    'user_id' => $user->id,
                    'error'   => $e->getMessage(),
                ]);
            }
        }

        return back()->with('success', 'If that email matches an account, a reset link has been sent.');
    }

    public function showResetPassword(Request $request, $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return back()->withErrors(['email' => 'Invalid or expired password reset token.']);
        }

        $incomingHashedToken = hash('sha256', $request->token);
        $tokenMatches = hash_equals((string) $record->token, $incomingHashedToken)
            // Backward compatibility for old reset rows that may still have plaintext tokens
            || hash_equals((string) $record->token, (string) $request->token);

        if (!$tokenMatches) {
            return back()->withErrors(['email' => 'Invalid or expired password reset token.']);
        }

        if (now()->diffInMinutes($record->created_at) > 60) {
            return back()->withErrors(['email' => 'The password reset link has expired. Please request a new one.']);
        }

        $user           = User::where('email', $request->email)->firstOrFail();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password reset successful! Please sign in.');
    }

    public function showCompleteProfile()
    {
        $user = Auth::user();
        if ($user->dob !== null) {
            return redirect()->route('dashboard');
        }
        return view('auth.complete-profile');
    }

    public function completeProfile(Request $request)
    {
        $user = Auth::user();
        if ($user->dob !== null) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'dob' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
        ], [
            'dob.before_or_equal' => 'You must be at least 18 years old to use this platform.',
        ]);

        $user->dob = $request->dob;
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Profile setup complete. Date of Birth saved successfully!');
    }
}
