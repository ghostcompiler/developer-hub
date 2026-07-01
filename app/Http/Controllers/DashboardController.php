<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Blog;
use App\Models\LinkedRepo;
use App\Models\Setting;
use App\Jobs\SendQueuedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

class DashboardController extends Controller
{
    // ─────────────────────────────────────────────
    //  User Dashboard
    // ─────────────────────────────────────────────

    public function userIndex(Request $request)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $myBlogs = Blog::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $myRepos = LinkedRepo::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $tokens  = $user->tokens()->orderBy('created_at', 'desc')->get();

        $tab = $request->query('tab', 'overview');

        return view('dashboard.user', compact('myBlogs', 'myRepos', 'tokens', 'tab'));
    }

    // ─────────────────────────────────────────────
    //  Admin Dashboard
    // ─────────────────────────────────────────────

    public function adminIndex(Request $request)
    {
        $pendingBlogs = Blog::with('user')->where('status', 'pending')->orderBy('created_at', 'asc')->get();
        $allBlogs     = Blog::with('user')->orderBy('created_at', 'desc')->get();
        $users        = User::orderBy('created_at', 'desc')->get();
        $pendingRepos = LinkedRepo::with('user')->where('status', 'pending')->orderBy('created_at', 'asc')->get();
        $allRepos     = LinkedRepo::with('user')->orderBy('created_at', 'desc')->get();
        $tokens       = Auth::user()->tokens()->orderBy('created_at', 'desc')->get();

        $registrationEnabled = Setting::get('registration_enabled', '1');
        $githubToken         = Setting::get('github_token', '');

        $githubClientId      = Setting::get('github_client_id', '');
        $githubClientSecret  = Setting::get('github_client_secret', '');
        $googleClientId      = Setting::get('google_client_id', '');
        $googleClientSecret  = Setting::get('google_client_secret', '');

        $tab = $request->query('tab', 'overview');

        return view('dashboard.admin', compact(
            'pendingBlogs', 'allBlogs', 'users', 'pendingRepos', 'allRepos',
            'registrationEnabled', 'githubToken', 'tokens', 'tab',
            'githubClientId', 'githubClientSecret', 'googleClientId', 'googleClientSecret'
        ));
    }

    // ─────────────────────────────────────────────
    //  Profile Update
    // ─────────────────────────────────────────────

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ];

        // Require current password when changing password
        if ($request->filled('password')) {
            $rules['current_password']    = 'required|string';
            $rules['password']            = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        // Verify current password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
            }
        }

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    // ─────────────────────────────────────────────
    //  Blog CRUD
    // ─────────────────────────────────────────────

    public function editBlog(int $id)
    {
        $blog = Blog::findOrFail($id);
        if (!Auth::user()->isAdmin() && $blog->user_id !== Auth::id()) abort(403);
        return view('dashboard.blogs.edit', compact('blog'));
    }

    public function updateBlog(Request $request, int $id)
    {
        $blog = Blog::findOrFail($id);
        if (!Auth::user()->isAdmin() && $blog->user_id !== Auth::id()) abort(403);

        $request->validate([
            'title'   => 'required|string|max:255',
            'summary' => 'required|string|max:500',
            'content' => 'required|string',
        ]);

        $slug         = Str::slug($request->title);
        $originalSlug = $slug;
        $count        = 1;
        while (Blog::where('slug', $slug)->where('id', '!=', $blog->id)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $blog->title   = $request->title;
        $blog->slug    = $slug;
        $blog->summary = $request->summary;
        $blog->content = $request->input('content');

        if (!Auth::user()->isAdmin()) {
            $blog->status = 'pending';
        }

        $blog->save();

        $message  = Auth::user()->isAdmin() ? 'Blog post updated.' : 'Blog updated and submitted for review.';
        $redirect = Auth::user()->isAdmin() ? route('admin.dashboard', ['tab' => 'all-blogs']) : route('dashboard', ['tab' => 'blogs']);
        return redirect($redirect)->with('success', $message);
    }

    public function deleteBlog(int $id)
    {
        $blog = Blog::findOrFail($id);
        if (!Auth::user()->isAdmin() && $blog->user_id !== Auth::id()) abort(403);

        $title = $blog->title;
        $blog->delete();

        $redirect = Auth::user()->isAdmin() ? route('admin.dashboard', ['tab' => 'all-blogs']) : route('dashboard', ['tab' => 'blogs']);
        return redirect($redirect)->with('success', '"' . $title . '" deleted.');
    }

    // ─────────────────────────────────────────────
    //  Linked Repo CRUD
    // ─────────────────────────────────────────────

    public function editRepo(int $id)
    {
        $repo = LinkedRepo::findOrFail($id);
        if (!Auth::user()->isAdmin() && $repo->user_id !== Auth::id()) abort(403);
        return view('dashboard.repos.edit', compact('repo'));
    }

    public function updateRepo(Request $request, int $id)
    {
        $repo = LinkedRepo::findOrFail($id);
        if (!Auth::user()->isAdmin() && $repo->user_id !== Auth::id()) abort(403);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'repo_url'    => ['required', 'url', 'regex:/^https?:\/\/(www\.)?github\.com\/[a-zA-Z0-9_.-]+\/[a-zA-Z0-9_.-]+/i'],
        ]);

        $repo->title       = $request->title;
        $repo->description = $request->description;
        $repo->repo_url    = $request->repo_url;

        if (!Auth::user()->isAdmin()) {
            $repo->status = 'pending';
        }

        $repo->save();

        $message  = Auth::user()->isAdmin() ? 'Repository updated.' : 'Repository updated and submitted for review.';
        $redirect = Auth::user()->isAdmin() ? route('admin.dashboard', ['tab' => 'all-repos']) : route('dashboard', ['tab' => 'repos']);
        return redirect($redirect)->with('success', $message);
    }

    public function deleteRepo(int $id)
    {
        $repo = LinkedRepo::findOrFail($id);
        if (!Auth::user()->isAdmin() && $repo->user_id !== Auth::id()) abort(403);

        $title = $repo->title;
        $repo->delete();

        $redirect = Auth::user()->isAdmin() ? route('admin.dashboard', ['tab' => 'all-repos']) : route('dashboard', ['tab' => 'repos']);
        return redirect($redirect)->with('success', '"' . $title . '" removed.');
    }

    // ─────────────────────────────────────────────
    //  Admin Actions
    // ─────────────────────────────────────────────

    public function toggleUserStatus(int $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        return redirect()->back()->with('success', '"' . $user->name . '" is now ' . $user->status . '.');
    }

    public function approveBlog(int $id)
    {
        $blog         = Blog::with('user')->findOrFail($id);
        $blog->status = 'approved';
        $blog->save();

        // Notify author
        if ($blog->user && $blog->user->email) {
            try {
                SendQueuedMail::dispatch(
                    $blog->user->email,
                    'Your Blog Post was Approved - Ghost Compiler',
                    'emails.approved',
                    [
                        'name'        => $blog->user->name,
                        'type'        => 'blog post',
                        'title'       => $blog->title,
                        'viewUrl'     => route('blogs.show', $blog->slug),
                        'dashboardUrl'=> route('dashboard'),
                    ]
                );
            } catch (\Throwable $e) {
                Log::error('Failed to send blog approval email', ['error' => $e->getMessage()]);
            }
        }

        return redirect()->back()->with('success', '"' . $blog->title . '" approved and published.');
    }

    public function approveRepo(int $id)
    {
        $repo         = LinkedRepo::with('user')->findOrFail($id);
        $repo->status = 'approved';
        $repo->save();

        // Notify author
        if ($repo->user && $repo->user->email) {
            try {
                SendQueuedMail::dispatch(
                    $repo->user->email,
                    'Your Repository Submission was Approved - Ghost Compiler',
                    'emails.approved',
                    [
                        'name'        => $repo->user->name,
                        'type'        => 'repository',
                        'title'       => $repo->title,
                        'viewUrl'     => $repo->repo_url,
                        'dashboardUrl'=> route('dashboard'),
                    ]
                );
            } catch (\Throwable $e) {
                Log::error('Failed to send repo approval email', ['error' => $e->getMessage()]);
            }
        }

        return redirect()->back()->with('success', '"' . $repo->title . '" approved and indexed.');
    }

    public function saveSettings(Request $request)
    {
        Setting::set('registration_enabled', $request->has('registration_enabled') ? '1' : '0');
        Setting::set('github_token', $request->github_token ?? '');

        Setting::set('github_client_id', $request->github_client_id ?? '');
        Setting::set('github_client_secret', $request->github_client_secret ?? '');
        Setting::set('google_client_id', $request->google_client_id ?? '');
        Setting::set('google_client_secret', $request->google_client_secret ?? '');

        return redirect()->back()->with('success', 'Settings saved.');
    }

    public function editPolicies(Request $request)
    {
        $type = $request->query('type', 'privacy-policy');
        if (!in_array($type, ['privacy-policy', 'terms-of-service', 'terms-and-conditions'])) {
            $type = 'privacy-policy';
        }

        $filePath = base_path($type . '.md');
        $content = '';
        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
        } else {
            // Initial defaults fallback
            if ($type === 'privacy-policy') {
                $content = "# Privacy Policy\nLast updated: " . date('F d, Y') . "\n\nAt **Ghost Compiler**, we respect your privacy...";
            } elseif ($type === 'terms-of-service') {
                $content = "# Terms of Service\nLast updated: " . date('F d, Y') . "\n\nWelcome to **Ghost Compiler**...";
            } else {
                $content = "# Terms and Conditions\nLast updated: " . date('F d, Y') . "\n\nPlease read these terms...";
            }
            file_put_contents($filePath, $content);
        }

        return view('dashboard.policies.edit', compact('type', 'content'));
    }

    public function savePolicies(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:privacy-policy,terms-of-service,terms-and-conditions',
            'content' => 'required|string',
        ]);

        $filePath = base_path($request->type . '.md');
        file_put_contents($filePath, $request->content);

        return redirect()->route('admin.policies.edit', ['type' => $request->type])->with('success', 'Policy markdown file saved successfully.');
    }

    // ─────────────────────────────────────────────
    //  API Tokens
    // ─────────────────────────────────────────────

    public function createToken(Request $request)
    {
        $request->validate(['token_name' => 'required|string|max:100']);

        $token = Auth::user()->createToken($request->token_name);

        return redirect()->back()->with('new_token', $token->plainTextToken)
            ->with('success', 'API token created. Copy it now — it will not be shown again.');
    }

    public function deleteToken(int $id)
    {
        $token = Auth::user()->tokens()->findOrFail($id);
        $token->delete();

        return redirect()->back()->with('success', 'API token revoked.');
    }

    // ─────────────────────────────────────────────
    //  Two-Factor Authentication
    // ─────────────────────────────────────────────

    public function showTwoFactor()
    {
        $user     = Auth::user();
        $google2fa = new Google2FA();
        $qrCodeSvg = null;
        $secret    = null;

        if (!$user->hasTwoFactorEnabled()) {
            // Generate new secret for setup
            $secret    = $google2fa->generateSecretKey();
            $qrCodeUrl = $google2fa->getQRCodeUrl(
                config('app.name', 'Ghost Compiler'),
                $user->email,
                $secret
            );

            $renderer = new \BaconQrCode\Renderer\ImageRenderer(
                new \BaconQrCode\Renderer\RendererStyle\RendererStyle(180),
                new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
            );
            $writer = new \BaconQrCode\Writer($renderer);
            $qrCodeSvg = $writer->writeString($qrCodeUrl);
        }

        return view('dashboard.two-factor', compact('user', 'secret', 'qrCodeSvg'));
    }

    public function enableTwoFactor(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'secret'           => 'required|string',
            'code'             => 'required|string|size:6',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $google2fa = new Google2FA();
        $valid     = $google2fa->verifyKey($request->secret, $request->code);

        if (!$valid) {
            return back()->withErrors(['code' => 'Invalid 2FA code. Please check your authenticator app and try again.']);
        }

        $user->two_factor_secret       = encrypt($request->secret);
        $user->two_factor_confirmed_at = now();
        // Generate recovery codes
        $user->two_factor_recovery_codes = encrypt(json_encode(
            collect(range(1, 8))->map(fn() => Str::random(10) . '-' . Str::random(10))->toArray()
        ));
        $user->save();

        return redirect()->back()->with('success', '2FA enabled successfully. Save your recovery codes!');
    }

    public function disableTwoFactor(Request $request)
    {
        $request->validate(['current_password' => 'required|string']);
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->two_factor_secret          = null;
        $user->two_factor_recovery_codes  = null;
        $user->two_factor_confirmed_at    = null;
        $user->save();

        return redirect()->back()->with('success', '2FA has been disabled.');
    }

    protected function sendEmailOtp($user)
    {
        // Use CSPRNG to generate a cryptographically secure 6-digit OTP
        $otp = sprintf("%06d", random_int(0, 999999));
        
        session([
            'two_factor_email_otp'          => $otp,
            'two_factor_email_otp_expires_at' => now()->addMinutes(10),
            'two_factor_email_otp_attempts'   => 0,  // Reset attempt counter
        ]);

        try {
            \App\Jobs\SendQueuedMail::dispatch(
                $user->email,
                'Your One-Time Password - Ghost Compiler',
                'emails.two_factor_otp',
                ['name' => $user->name, 'otp' => $otp]
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send 2FA Email OTP', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
        }
    }

    public function showTwoFactorChallenge()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if (!$user->isActive()) {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Your account has been deactivated. Please contact an administrator.');
        }

        if (session('two_factor_verified')) {
            return $user->isAdmin() ? redirect()->route('admin.dashboard') : redirect()->route('dashboard');
        }

        if (!$user->hasTwoFactorEnabled()) {
            $otp = session('two_factor_email_otp');
            $expiresAt = session('two_factor_email_otp_expires_at');

            if (!$otp || !$expiresAt || now()->greaterThan($expiresAt)) {
                $this->sendEmailOtp($user);
            }
        }

        return view('auth.two-factor');
    }

    public function verifyTwoFactor(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $user = Auth::user();
        if ($user && !$user->isActive()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Your account has been deactivated. Please contact an administrator.');
        }

        if ($user->hasTwoFactorEnabled()) {
            $google2fa = new Google2FA();
            $secret    = decrypt($user->two_factor_secret);
            $valid     = $google2fa->verifyKey($secret, $request->code);

            if (!$valid) {
                // Check recovery codes
                $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);
                $index         = array_search($request->code, $recoveryCodes);

                if ($index === false) {
                    return back()->withErrors(['code' => 'Invalid authentication code. Please try again.']);
                }

                // Consume the recovery code
                array_splice($recoveryCodes, $index, 1);
                $user->two_factor_recovery_codes = encrypt(json_encode($recoveryCodes));
                $user->save();
            }
        } else {
            $otp       = session('two_factor_email_otp');
            $expiresAt = session('two_factor_email_otp_expires_at');
            $attempts  = (int) session('two_factor_email_otp_attempts', 0);

            // Invalidate if expired
            if (!$otp || !$expiresAt || now()->greaterThan($expiresAt)) {
                session()->forget(['two_factor_email_otp', 'two_factor_email_otp_expires_at', 'two_factor_email_otp_attempts']);
                return back()->withErrors(['code' => 'Your verification code has expired. Please request a new one.']);
            }

            // Enforce attempt limit (max 5 guesses)
            if ($attempts >= 5) {
                session()->forget(['two_factor_email_otp', 'two_factor_email_otp_expires_at', 'two_factor_email_otp_attempts']);
                return back()->withErrors(['code' => 'Too many incorrect attempts. Please request a new code.']);
            }

            $inputCode = str_replace(' ', '', $request->code);
            if (!hash_equals($otp, $inputCode)) {
                session()->put('two_factor_email_otp_attempts', $attempts + 1);
                return back()->withErrors(['code' => 'Invalid authentication code. Please try again.']);
            }

            session()->forget(['two_factor_email_otp', 'two_factor_email_otp_expires_at', 'two_factor_email_otp_attempts']);
        }

        $request->session()->put('two_factor_verified', true);

        return redirect()->intended($user->isAdmin() ? route('admin.dashboard') : route('dashboard'));
    }

    public function resendTwoFactorOtp(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isActive()) {
            Auth::logout();
            return redirect()->route('login');
        }

        if ($user->hasTwoFactorEnabled()) {
            return redirect()->route('two-factor.challenge')->with('error', 'TOTP is enabled on your account.');
        }

        $this->sendEmailOtp($user);

        return redirect()->route('two-factor.challenge')->with('success', 'A new verification code has been sent to your email.');
    }

}
