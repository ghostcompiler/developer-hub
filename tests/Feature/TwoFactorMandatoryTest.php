<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class TwoFactorMandatoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_is_redirected_to_two_factor_challenge_after_login(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
            'status' => 'active',
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('two-factor.challenge'));
        $this->assertFalse(session()->has('two_factor_verified'));
    }

    public function test_user_cannot_access_dashboard_without_verifying_two_factor(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('two-factor.challenge'));
    }

    public function test_challenge_generates_and_emails_otp_if_totp_disabled(): void
    {
        Queue::fake();

        $user = User::factory()->create(['status' => 'active']);
        $this->actingAs($user);

        // Access the challenge page
        $response = $this->get(route('two-factor.challenge'));
        $response->assertStatus(200);
        $response->assertSee("We've sent a 6-digit verification code", false);

        // Check OTP was stored in session
        $this->assertTrue(session()->has('two_factor_email_otp'));
        $this->assertTrue(session()->has('two_factor_email_otp_expires_at'));

        // Check job was pushed
        Queue::assertPushed(\App\Jobs\SendQueuedMail::class, function ($job) use ($user) {
            return $job->getTo() === $user->email;
        });
    }

    public function test_user_can_verify_email_otp_and_access_dashboard(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $this->actingAs($user);

        // Seed OTP in session
        session([
            'two_factor_email_otp' => '123456',
            'two_factor_email_otp_expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->post(route('two-factor.verify'), [
            'code' => '123456',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertTrue(session('two_factor_verified'));
        $this->assertFalse(session()->has('two_factor_email_otp'));
    }

    public function test_user_cannot_verify_with_wrong_email_otp(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $this->actingAs($user);

        session([
            'two_factor_email_otp' => '123456',
            'two_factor_email_otp_expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->post(route('two-factor.verify'), [
            'code' => '654321', // wrong code
        ]);

        $response->assertSessionHasErrors('code');
        $this->assertFalse(session()->has('two_factor_verified'));
    }

    public function test_user_cannot_verify_with_expired_email_otp(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $this->actingAs($user);

        session([
            'two_factor_email_otp' => '123456',
            'two_factor_email_otp_expires_at' => now()->subMinutes(1), // expired
        ]);

        $response = $this->post(route('two-factor.verify'), [
            'code' => '123456',
        ]);

        $response->assertSessionHasErrors('code');
        $this->assertFalse(session()->has('two_factor_verified'));
    }

    public function test_user_can_resend_email_otp(): void
    {
        Queue::fake();

        $user = User::factory()->create(['status' => 'active']);
        $this->actingAs($user);

        session([
            'two_factor_email_otp' => '111111',
            'two_factor_email_otp_expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->post(route('two-factor.resend'));
        $response->assertRedirect(route('two-factor.challenge'));
        $response->assertSessionHas('success');

        // Check a new OTP was generated
        $this->assertNotEquals('111111', session('two_factor_email_otp'));
        Queue::assertPushed(\App\Jobs\SendQueuedMail::class, function ($job) use ($user) {
            return $job->getTo() === $user->email;
        });
    }
}
