<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use App\Models\Blog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LlmsTextGeneratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_llms_txt_route_returns_markdown_compliant_content(): void
    {
        // Create user and a project
        $user = User::factory()->create();
        Project::create([
            'name' => 'laravel-model-caching',
            'slug' => 'laravel-model-caching',
            'github_url' => 'https://github.com/ghostcompiler/laravel-model-caching',
            'stars_count' => 120,
            'forks_count' => 15,
            'language' => 'PHP',
            'description' => 'Fast caching for Eloquent models.',
        ]);

        // Create a blog post
        Blog::create([
            'user_id' => $user->id,
            'title' => 'Building Fast Laravel Apps',
            'slug' => 'building-fast-laravel-apps',
            'summary' => 'Optimizing queries and query cache.',
            'content' => 'Full article content here.',
            'status' => 'approved',
        ]);

        // Access route
        $response = $this->get('/llms.txt');

        // Assert response status and content type
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');

        // Assert Markdown links and H1 heading are present
        $content = $response->getContent();
        $this->assertStringStartsWith('# Ghost Compiler', $content);
        $this->assertStringContainsString('## Projects & SDKs', $content);
        $this->assertStringContainsString('## Developer Blogs', $content);

        // Verify markdown formatted links are present
        $this->assertStringContainsString('[laravel-model-caching](' . url('/projects/laravel-model-caching') . '):', $content);
        $this->assertStringContainsString('[Building Fast Laravel Apps](' . url('/blogs/building-fast-laravel-apps') . '):', $content);
        $this->assertStringContainsString('[Privacy Policy](' . url('/privacy-policy') . '):', $content);
    }
}
