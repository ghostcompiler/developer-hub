<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CacheProjectTrees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:cache-trees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pre-fetch and cache the repository file trees of all projects for fast indexing.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $projects = Project::all();
        $this->info("Caching repository trees for " . $projects->count() . " projects...");

        foreach ($projects as $project) {
            $this->info("Processing: {$project->name}");
            $cacheKey = "project_tree_{$project->id}";

            try {
                $username = 'ghostcompiler';
                $name = $project->name;
                $branch = $project->default_branch ?: 'main';
                $token = Setting::get('github_token', '');
                
                $url = "https://api.github.com/repos/{$username}/{$name}/git/trees/{$branch}?recursive=1";
                
                $request = Http::timeout(10)->withHeaders(['User-Agent' => 'Laravel-Github-Sync-Client']);
                if (!empty($token)) {
                    $request = $request->withToken($token);
                }
                
                $response = $request->get($url);
                if ($response->successful()) {
                    $treeData = $response->json();
                    // Cache the tree data for 25 hours
                    Cache::put($cacheKey, $treeData, 90000); // 25 hours
                    $this->info("Successfully cached tree for {$project->name}.");
                } else {
                    $this->error("Failed to fetch tree for {$project->name}: Status " . $response->status());
                }
            } catch (\Throwable $e) {
                $this->error("Error caching tree for {$project->name}: " . $e->getMessage());
                Log::error("Error caching tree for {$project->name}: " . $e->getMessage());
            }
        }

        $this->info("Repository tree caching completed!");
    }
}
