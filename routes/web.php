<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\LinkedRepoController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// ─── Public: Projects & Code Explorer ────────────────────────────────────────
Route::get('/', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/projects/{slug}/tree', [ProjectController::class, 'tree'])->name('projects.tree');
Route::get('/projects/{slug}/{page_slug}', [ProjectController::class, 'showPage'])->name('projects.page');
Route::get('/projects/{slug}/files/{path}', [ProjectController::class, 'showFile'])->name('projects.file')->where('path', '.*');

// ─── Public: Blogs ───────────────────────────────────────────────────────────
Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{slug}', [BlogController::class, 'show'])->name('blogs.show');

// ─── Auth ────────────────────────────────────────────────────────────────────
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\PolicyController;

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── Socialite OAuth Routes ──────────────────────────────────────────────────
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirectToProvider'])->name('auth.social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('auth.social.callback');

// ─── Policy Pages ────────────────────────────────────────────────────────────
Route::get('/privacy-policy', [PolicyController::class, 'privacy'])->name('policies.privacy');
Route::get('/terms-of-service', [PolicyController::class, 'terms'])->name('policies.terms');
Route::get('/terms-and-conditions', [PolicyController::class, 'conditions'])->name('policies.conditions');

// ─── Email Verification ───────────────────────────────────────────────────────
Route::get('/email/verify', [AuthController::class, 'showVerifyNotice'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify');
Route::post('/email/verification-notification', [AuthController::class, 'resendVerification'])->name('verification.send')->middleware('throttle:5,1');

// ─── Password Reset ───────────────────────────────────────────────────────────
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email')->middleware('throttle:5,1');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update')->middleware('throttle:5,1');

// ─── Two-Factor Challenge (public — user is logged in but 2FA not yet verified) ─
Route::get('/lock-screen', function () {
    $user = Auth::user();
    if ($user && $user->dob) {
        $dob = \Carbon\Carbon::parse($user->dob);
        if ($dob->diffInYears(now(), false) >= 18) {
            return redirect()->route('dashboard');
        }
    } else {
        return redirect()->route('profile.complete');
    }
    return view('auth.lock-screen');
})->name('lockscreen')->middleware('auth');

Route::get('/profile/complete', [AuthController::class, 'showCompleteProfile'])->name('profile.complete')->middleware('auth');
Route::post('/profile/complete', [AuthController::class, 'completeProfile'])->name('profile.complete.store')->middleware(['auth', 'throttle:10,1']);

Route::get('/two-factor-challenge', [DashboardController::class, 'showTwoFactorChallenge'])->name('two-factor.challenge')->middleware(['auth', 'age_lock']);
Route::post('/two-factor-challenge', [DashboardController::class, 'verifyTwoFactor'])->name('two-factor.verify')->middleware(['auth', 'age_lock', 'throttle:10,1']);
Route::post('/two-factor-challenge/resend', [DashboardController::class, 'resendTwoFactorOtp'])->name('two-factor.resend')->middleware(['auth', 'age_lock', 'throttle:5,1']);

// ─── Authenticated Dashboard ──────────────────────────────────────────────────
Route::middleware(['auth', 'two_factor', 'age_lock'])->group(function () {

    // User Dashboard Home
    Route::get('/dashboard', [DashboardController::class, 'userIndex'])->name('dashboard');

    // Profile
    Route::post('/dashboard/profile', [DashboardController::class, 'updateProfile'])->name('dashboard.profile.update');

    // Blog management (create moved to dashboard)
    Route::get('/dashboard/blogs/create', [BlogController::class, 'create'])->name('blogs.create');
    Route::post('/dashboard/blogs', [BlogController::class, 'store'])->name('blogs.store');
    Route::get('/dashboard/blogs/{id}/edit', [DashboardController::class, 'editBlog'])->name('dashboard.blogs.edit');
    Route::post('/dashboard/blogs/{id}/update', [DashboardController::class, 'updateBlog'])->name('dashboard.blogs.update');
    Route::post('/dashboard/blogs/{id}/delete', [DashboardController::class, 'deleteBlog'])->name('dashboard.blogs.delete');

    // Repo management (create moved to dashboard)
    Route::get('/dashboard/repos/link', [LinkedRepoController::class, 'create'])->name('repos.link');
    Route::post('/dashboard/repos', [LinkedRepoController::class, 'store'])->name('repos.store');
    Route::get('/dashboard/repos/{id}/edit', [DashboardController::class, 'editRepo'])->name('dashboard.repos.edit');
    Route::post('/dashboard/repos/{id}/update', [DashboardController::class, 'updateRepo'])->name('dashboard.repos.update');
    Route::post('/dashboard/repos/{id}/delete', [DashboardController::class, 'deleteRepo'])->name('dashboard.repos.delete');

    // API Tokens
    Route::post('/dashboard/tokens', [DashboardController::class, 'createToken'])->name('dashboard.tokens.create');
    Route::post('/dashboard/tokens/{id}/delete', [DashboardController::class, 'deleteToken'])->name('dashboard.tokens.delete');

    // Two-Factor Auth Settings
    Route::get('/dashboard/two-factor', [DashboardController::class, 'showTwoFactor'])->name('dashboard.two-factor');
    Route::post('/dashboard/two-factor/enable', [DashboardController::class, 'enableTwoFactor'])->name('dashboard.two-factor.enable');
    Route::post('/dashboard/two-factor/disable', [DashboardController::class, 'disableTwoFactor'])->name('dashboard.two-factor.disable');

    // Admin Command Center
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin', [DashboardController::class, 'adminIndex'])->name('admin.dashboard');
        Route::post('/admin/users/{id}/toggle-status', [DashboardController::class, 'toggleUserStatus'])->name('dashboard.admin.users.toggle');
        Route::post('/admin/blogs/{id}/approve', [DashboardController::class, 'approveBlog'])->name('dashboard.admin.blogs.approve');
        Route::post('/admin/repos/{id}/approve', [DashboardController::class, 'approveRepo'])->name('dashboard.admin.repos.approve');
        Route::post('/admin/settings', [DashboardController::class, 'saveSettings'])->name('dashboard.admin.settings.save');
        Route::get('/admin/policies', [DashboardController::class, 'editPolicies'])->name('admin.policies.edit');
        Route::post('/admin/policies', [DashboardController::class, 'savePolicies'])->name('admin.policies.save');
    });
});

// ─── SEO / Crawlers ──────────────────────────────────────────────────────────
Route::get('/sitemap.xml', [ProjectController::class, 'sitemap'])->name('sitemap');
Route::get('/llms.txt', [ProjectController::class, 'llmsText'])->name('llms-txt');
