<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (Schema::hasTable('settings')) {
                config([
                    'services.github.client_id' => Setting::get('github_client_id'),
                    'services.github.client_secret' => Setting::get('github_client_secret'),
                    'services.github.redirect' => url('auth/github/callback'),
                    
                    'services.google.client_id' => Setting::get('google_client_id'),
                    'services.google.client_secret' => Setting::get('google_client_secret'),
                    'services.google.redirect' => url('auth/google/callback'),
                ]);
            }
        } catch (\Throwable $e) {
            // Database not ready yet
        }
    }
}
