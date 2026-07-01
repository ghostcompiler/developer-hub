<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\LinkedRepo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgeLimitVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_fails_if_age_under_18(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'underageuser',
            'email' => 'underage@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'dob' => now()->subYears(17)->format('Y-m-d'), // 17 years old
        ]);

        $response->assertSessionHasErrors('dob');
        $this->assertDatabaseMissing('users', ['email' => 'underage@example.com']);
    }

    public function test_registration_succeeds_if_age_is_18_or_older(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'adultuser',
            'email' => 'adult@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'dob' => now()->subYears(18)->format('Y-m-d'), // exactly 18 years old
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => 'adult@example.com']);
    }

    public function test_underage_user_is_redirected_to_lockscreen_and_repos_are_set_to_pending(): void
    {
        // Create an underage user bypass
        $user = User::factory()->create([
            'dob' => now()->subYears(16)->format('Y-m-d'), // 16 years old
            'status' => 'active',
        ]);

        // Create an approved repo for this user
        $repo = LinkedRepo::create([
            'user_id' => $user->id,
            'title' => 'My Good Repo',
            'description' => 'A nice repository',
            'repo_url' => 'https://github.com/someuser/somerepo',
            'status' => 'approved',
        ]);

        // Access dashboard
        $response = $this->actingAs($user)
            ->withSession(['two_factor_verified' => true])
            ->get(route('dashboard'));

        // Should redirect to lockscreen
        $response->assertRedirect(route('lockscreen'));

        // The repo status should now be pending
        $this->assertEquals('pending', $repo->fresh()->status);
    }

    public function test_adult_user_can_access_dashboard_normally(): void
    {
        $user = User::factory()->create([
            'dob' => now()->subYears(19)->format('Y-m-d'), // 19 years old
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)
            ->withSession(['two_factor_verified' => true])
            ->get(route('dashboard'));

        $response->assertStatus(200);
    }

    public function test_user_without_dob_is_redirected_to_profile_complete(): void
    {
        $user = User::factory()->create([
            'dob' => null,
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)
            ->withSession(['two_factor_verified' => true])
            ->get(route('dashboard'));

        $response->assertRedirect(route('profile.complete'));
    }

    public function test_user_without_dob_can_view_profile_complete_page(): void
    {
        $user = User::factory()->create([
            'dob' => null,
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)
            ->get(route('profile.complete'));

        $response->assertStatus(200);
    }

    public function test_user_without_dob_submitting_underage_dob_fails(): void
    {
        $user = User::factory()->create([
            'dob' => null,
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)
            ->post(route('profile.complete.store'), [
                'dob' => now()->subYears(17)->format('Y-m-d'), // 17 years old
            ]);

        $response->assertSessionHasErrors('dob');
        $this->assertNull($user->fresh()->dob);
    }

    public function test_user_without_dob_submitting_adult_dob_succeeds(): void
    {
        $user = User::factory()->create([
            'dob' => null,
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)
            ->post(route('profile.complete.store'), [
                'dob' => now()->subYears(18)->format('Y-m-d'), // 18 years old
            ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertEquals(now()->subYears(18)->format('Y-m-d'), $user->fresh()->dob);
    }
}
