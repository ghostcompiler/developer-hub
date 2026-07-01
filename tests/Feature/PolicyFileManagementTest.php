<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class PolicyFileManagementTest extends TestCase
{
    use RefreshDatabase;

    protected array $backups = [];
    protected array $files = ['privacy-policy.md', 'terms-of-service.md', 'terms-and-conditions.md'];

    protected function setUp(): void
    {
        parent::setUp();
        // Backup actual workspace files so tests do not destroy them
        foreach ($this->files as $file) {
            $path = base_path($file);
            if (file_exists($path)) {
                $this->backups[$file] = file_get_contents($path);
            }
        }
    }

    protected function tearDown(): void
    {
        // Restore actual workspace files
        foreach ($this->files as $file) {
            $path = base_path($file);
            if (isset($this->backups[$file])) {
                file_put_contents($path, $this->backups[$file]);
            } else {
                if (file_exists($path)) {
                    unlink($path);
                }
            }
        }
        parent::tearDown();
    }

    public function test_public_policy_routes_load_correctly_from_files(): void
    {
        file_put_contents(base_path('privacy-policy.md'), '# Custom Test Privacy Policy');
        
        $response = $this->get(route('policies.privacy'));
        $response->assertStatus(200);
        $response->assertSee('Custom Test Privacy Policy');
    }

    public function test_non_admin_cannot_access_policy_editor(): void
    {
        $user = User::factory()->create(['role' => 'user', 'status' => 'active']);

        $response = $this->actingAs($user)->withSession(['two_factor_verified' => true])->get(route('admin.policies.edit'));
        $response->assertStatus(403);

        $response = $this->actingAs($user)->withSession(['two_factor_verified' => true])->post(route('admin.policies.save'), [
            'type' => 'privacy-policy',
            'content' => 'Unauthorized update content',
        ]);
        $response->assertStatus(403);
    }

    public function test_admin_can_view_and_save_policies(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);

        // View policy editor page
        $response = $this->actingAs($admin)->withSession(['two_factor_verified' => true])->get(route('admin.policies.edit', ['type' => 'terms-of-service']));
        $response->assertStatus(200);
        $response->assertSee('Policy Markdown Editor');
        $response->assertSee('terms-of-service.md');

        // Post update
        $response = $this->actingAs($admin)->withSession(['two_factor_verified' => true])->post(route('admin.policies.save'), [
            'type' => 'terms-of-service',
            'content' => '## Custom Terms of Service Content Here',
        ]);
        
        $response->assertRedirect(route('admin.policies.edit', ['type' => 'terms-of-service']));
        $this->assertEquals('## Custom Terms of Service Content Here', file_get_contents(base_path('terms-of-service.md')));
    }
}
