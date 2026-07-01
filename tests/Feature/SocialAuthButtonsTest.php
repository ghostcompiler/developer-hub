<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Setting;
use Tests\TestCase;

class SocialAuthButtonsTest extends TestCase
{
    use RefreshDatabase;

    public function test_social_login_buttons_are_hidden_when_credentials_are_empty(): void
    {
        // Ensure database settings are empty
        Setting::whereIn('key', [
            'github_client_id',
            'github_client_secret',
            'google_client_id',
            'google_client_secret'
        ])->delete();
        
        // Explicitly override configuration for this test case
        config([
            'services.github.client_id' => null,
            'services.github.client_secret' => null,
            'services.google.client_id' => null,
            'services.google.client_secret' => null,
        ]);

        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertDontSee('Sign in with GitHub');
        $response->assertDontSee('Sign in with Google');
        $response->assertDontSee('or continue with');

        $response = $this->get(route('register'));
        $response->assertStatus(200);
        $response->assertDontSee('Sign up with GitHub');
        $response->assertDontSee('Sign up with Google');
        $response->assertDontSee('or continue with');
    }

    public function test_social_login_buttons_are_shown_when_credentials_are_present(): void
    {
        // Set configuration settings
        config([
            'services.github.client_id' => 'dummy-github-id',
            'services.github.client_secret' => 'dummy-github-secret',
            'services.google.client_id' => 'dummy-google-id',
            'services.google.client_secret' => 'dummy-google-secret',
        ]);

        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertSee('Sign in with GitHub');
        $response->assertSee('Sign in with Google');
        $response->assertSee('or continue with');

        $response = $this->get(route('register'));
        $response->assertStatus(200);
        $response->assertSee('Sign up with GitHub');
        $response->assertSee('Sign up with Google');
        $response->assertSee('or continue with');
    }

    public function test_credentials_are_loaded_from_settings_table_into_config(): void
    {
        Setting::set('github_client_id', 'github-id-from-db');
        Setting::set('github_client_secret', 'github-secret-from-db');
        Setting::set('google_client_id', 'google-id-from-db');
        Setting::set('google_client_secret', 'google-secret-from-db');

        // Re-boot or manually run the provider's boot method
        (new \App\Providers\AppServiceProvider(app()))->boot();

        $this->assertEquals('github-id-from-db', config('services.github.client_id'));
        $this->assertEquals('github-secret-from-db', config('services.github.client_secret'));
        $this->assertEquals(url('auth/github/callback'), config('services.github.redirect'));
        $this->assertEquals('google-id-from-db', config('services.google.client_id'));
        $this->assertEquals('google-secret-from-db', config('services.google.client_secret'));
        $this->assertEquals(url('auth/google/callback'), config('services.google.redirect'));
    }
}
