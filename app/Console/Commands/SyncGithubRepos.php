<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\ProjectPage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SyncGithubRepos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'github:sync {--force : Force sync all repos even if API limit is low}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync GitHub repositories, READMEs, and metadata for ghostcompiler profile';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $username = 'ghostcompiler';
        $this->info("Starting GitHub sync for profile: {$username}...");

        $token = env('GITHUB_TOKEN');
        if (empty($token) || $token === 'github_pat_antigravitydummytoken') {
            $token = \App\Models\Setting::get('github_token');
        }
        if (empty($token) || $token === 'github_pat_antigravitydummytoken') {
            $token = null;
        }

        if (!empty($token)) {
            $this->info("Using GitHub Personal Access Token for authentication.");
            $response = Http::withToken($token)->withHeaders([
                'User-Agent' => 'Laravel-Github-Sync-Client',
            ])->get("https://api.github.com/users/{$username}/repos?per_page=100&sort=pushed");
            
            if ($response->status() === 401) {
                $this->warn("Provided GITHUB_TOKEN returned 401 (Bad Credentials). Falling back to unauthenticated request...");
                $response = Http::withHeaders([
                    'User-Agent' => 'Laravel-Github-Sync-Client',
                ])->get("https://api.github.com/users/{$username}/repos?per_page=100&sort=pushed");
                $token = null;
            }
        } else {
            $this->warn("No GITHUB_TOKEN found in environment. Rate limit will be capped at 60 requests/hr.");
            $response = Http::withHeaders([
                'User-Agent' => 'Laravel-Github-Sync-Client',
            ])->get("https://api.github.com/users/{$username}/repos?per_page=100&sort=pushed");
        }

        $repos = [];
        if ($response->successful()) {
            $repos = $response->json();
            if (!is_array($repos)) {
                $this->error("Unexpected response format from GitHub API.");
                return Command::FAILURE;
            }
        } else {
            $this->warn("Failed to fetch repository list from GitHub API: " . $response->body());
            $this->warn("Falling back to local database entries for repair sync...");
            
            // Build list of repos from projects already in DB
            $dbProjects = Project::all();
            foreach ($dbProjects as $proj) {
                $repos[] = [
                    'name' => $proj->name,
                    'description' => $proj->description,
                    'stargazers_count' => $proj->stars_count,
                    'forks_count' => $proj->forks_count,
                    'open_issues_count' => $proj->open_issues_count,
                    'language' => $proj->language,
                    'license' => ['spdx_id' => $proj->license_name],
                    'html_url' => $proj->github_url,
                    'homepage' => $proj->homepage_url,
                    'default_branch' => $proj->default_branch,
                    'topics' => $proj->topics,
                    'fork' => false,
                ];
            }
        }

        $this->info("Processing " . count($repos) . " repositories...");
        
        $syncedProjectNames = [];
        $count = 0;
        foreach ($repos as $repoData) {
            $name = $repoData['name'];
            
            // Skip the profile README repository
            if (Str::lower($name) === 'ghostcompiler') {
                $this->info("Skipping profile repository: {$name}");
                continue;
            }

            // Skip forks by default
            if ($repoData['fork']) {
                $this->info("Skipping fork repository: {$name}");
                continue;
            }

            $this->line("Processing repository: <info>{$name}</info>");

            // Fresh request instance for README
            $readmeRequest = Http::withHeaders([
                'User-Agent' => 'Laravel-Github-Sync-Client',
                'Accept' => 'application/vnd.github.html',
            ]);
            if (!empty($token)) {
                $readmeRequest = $readmeRequest->withToken($token);
            }

            $readmeUrl = "https://api.github.com/repos/{$username}/{$name}/readme";
            $readmeResponse = $readmeRequest->get($readmeUrl);

            $readmeHtml = null;
            if ($readmeResponse->successful()) {
                $readmeHtml = $readmeResponse->body();
            } else {
                $this->warn("  - GitHub API README fetch failed (Status " . $readmeResponse->status() . "). Trying raw Markdown fallback...");
                
                // Fetch raw Markdown from raw.githubusercontent.com
                $defaultBranch = $repoData['default_branch'] ?? 'main';
                $rawUrl = "https://raw.githubusercontent.com/{$username}/{$name}/{$defaultBranch}/README.md";
                $rawResponse = Http::get($rawUrl);
                
                if ($rawResponse->failed()) {
                    // Try master branch if main failed
                    $rawUrl = "https://raw.githubusercontent.com/{$username}/{$name}/master/README.md";
                    $rawResponse = Http::get($rawUrl);
                }
                
                if ($rawResponse->successful()) {
                    $rawMarkdown = $rawResponse->body();
                    try {
                        $converter = new \League\CommonMark\CommonMarkConverter([
                            'html_input'         => 'strip',  // Strip raw HTML to prevent stored XSS
                            'allow_unsafe_links' => false,
                        ]);
                        $readmeHtml = $converter->convert($rawMarkdown)->getContent();
                        $this->info("  - [SUCCESS] Locally converted raw README to HTML.");
                    } catch (\Exception $e) {
                        $this->error("  - [ERROR] Failed to parse Markdown: " . $e->getMessage());
                    }
                } else {
                    $this->warn("  - Raw Markdown fetch failed.");
                }
            }

            // Fresh request instance for releases
            $releaseRequest = Http::withHeaders([
                'User-Agent' => 'Laravel-Github-Sync-Client',
            ]);
            if (!empty($token)) {
                $releaseRequest = $releaseRequest->withToken($token);
            }

            $releaseUrl = "https://api.github.com/repos/{$username}/{$name}/releases/latest";
            $releaseResponse = $releaseRequest->get($releaseUrl);
            
            $releaseInfo = null;
            if ($releaseResponse->successful()) {
                $releaseData = $releaseResponse->json();
                $releaseInfo = [
                    'tag_name' => $releaseData['tag_name'] ?? null,
                    'name' => $releaseData['name'] ?? null,
                    'published_at' => $releaseData['published_at'] ?? null,
                    'zipball_url' => $releaseData['zipball_url'] ?? null,
                    'tarball_url' => $releaseData['tarball_url'] ?? null,
                    'html_url' => $releaseData['html_url'] ?? null,
                    'body' => $releaseData['body'] ?? null,
                ];
                $this->line("  - Found release: <comment>{$releaseInfo['tag_name']}</comment>");
            }

            // 4. Fetch Packagist / NPM Downloads Count
            $downloadsCount = 0;
            if ($name === 'bhidu-language') {
                $npmUrl = "https://api.npmjs.org/downloads/point/last-month/bhidu-lang";
                $npmResponse = Http::get($npmUrl);
                if ($npmResponse->successful()) {
                    $downloadsCount = $npmResponse->json()['downloads'] ?? 0;
                }
            } else {
                // Try Packagist first
                $packagistUrl = "https://packagist.org/packages/ghostcompiler/{$name}.json";
                $packagistResponse = Http::get($packagistUrl);
                if ($packagistResponse->successful()) {
                    $downloadsCount = $packagistResponse->json()['package']['downloads']['total'] ?? 0;
                } else {
                    // Try NPM as fallback for JS/TS packages
                    $npmUrl = "https://api.npmjs.org/downloads/point/last-month/{$name}";
                    $npmResponse = Http::get($npmUrl);
                    if ($npmResponse->successful()) {
                        $downloadsCount = $npmResponse->json()['downloads'] ?? 0;
                    }
                }
            }
            $this->line("  - Downloads count: <comment>{$downloadsCount}</comment>");

            // 5. Update or Create in DB with cache protection
            $project = Project::where('name', $name)->first();
            
            $updateData = [
                'slug' => Str::slug($name),
                'description' => $repoData['description'],
                'stars_count' => $repoData['stargazers_count'] ?? 0,
                'forks_count' => $repoData['forks_count'] ?? 0,
                'open_issues_count' => $repoData['open_issues_count'] ?? 0,
                'downloads_count' => $downloadsCount,
                'language' => $repoData['language'] ?? 'N/A',
                'license_name' => $repoData['license']['spdx_id'] ?? ($repoData['license']['name'] ?? null),
                'github_url' => $repoData['html_url'],
                'homepage_url' => $repoData['homepage'],
                'default_branch' => $repoData['default_branch'] ?? 'main',
                'topics' => $repoData['topics'] ?? [],
                'synced_at' => now(),
            ];

            if ($readmeHtml !== null) {
                $updateData['readme_html'] = $readmeHtml;
            }
            if ($releaseInfo !== null) {
                $updateData['releases_info'] = $releaseInfo;
            }

            if ($project) {
                $project->update($updateData);
            } else {
                $updateData['name'] = $name;
                $updateData['readme_html'] = $readmeHtml;
                $updateData['releases_info'] = $releaseInfo;
                $project = Project::create($updateData);
            }

            // Clear file tree cache so it updates immediately
            \Illuminate\Support\Facades\Cache::forget("project_tree_{$project->id}");

            $syncedProjectNames[] = $name;

            // --- 6. Sync Additional Documentation Pages (e.g. functions.md, developer.md) ---
            $pagesToSync = [];
            $hasApiAccess = !empty($token) || $response->successful(); 
            
            if ($hasApiAccess) {
                $contentsUrl = "https://api.github.com/repos/{$username}/{$name}/contents";
                $contentsRequest = Http::withHeaders(['User-Agent' => 'Laravel-Github-Sync-Client']);
                if (!empty($token)) {
                    $contentsRequest = $contentsRequest->withToken($token);
                }
                
                $contentsResponse = $contentsRequest->get($contentsUrl);
                if ($contentsResponse->successful()) {
                    $files = $contentsResponse->json();
                    $hasDocsDir = false;
                    
                    if (is_array($files)) {
                        foreach ($files as $file) {
                            if ($file['type'] === 'file' && str_ends_with(strtolower($file['name']), '.md')) {
                                if (strtolower($file['name']) !== 'readme.md') {
                                    $pageTitle = Str::title(str_replace('-', ' ', pathinfo($file['name'], PATHINFO_FILENAME)));
                                    $pagesToSync[] = [
                                        'title' => $pageTitle,
                                        'slug' => Str::slug(pathinfo($file['name'], PATHINFO_FILENAME)),
                                        'path' => $file['path'],
                                    ];
                                }
                            }
                            if ($file['type'] === 'dir' && strtolower($file['name']) === 'docs') {
                                $hasDocsDir = true;
                            }
                        }
                    }
                    
                    if ($hasDocsDir) {
                        $docsUrl = "https://api.github.com/repos/{$username}/{$name}/contents/docs";
                        $docsResponse = $contentsRequest->get($docsUrl);
                        if ($docsResponse->successful()) {
                            $docsFiles = $docsResponse->json();
                            if (is_array($docsFiles)) {
                                foreach ($docsFiles as $file) {
                                    if ($file['type'] === 'file' && str_ends_with(strtolower($file['name']), '.md')) {
                                        $pageTitle = Str::title(str_replace('-', ' ', pathinfo($file['name'], PATHINFO_FILENAME)));
                                        $pagesToSync[] = [
                                            'title' => $pageTitle,
                                            'slug' => Str::slug(pathinfo($file['name'], PATHINFO_FILENAME)),
                                            'path' => $file['path'],
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            // Fallback to common files check via raw.githubusercontent.com
            if (empty($pagesToSync)) {
                $commonPaths = [
                    'docs/functions.md' => 'Functions Reference',
                    'functions.md' => 'Functions Reference',
                    'docs/developer.md' => 'Developer Guide',
                    'developer.md' => 'Developer Guide',
                    'docs/developer-guide.md' => 'Developer Guide',
                    'developer-guide.md' => 'Developer Guide',
                    'docs/usage.md' => 'Usage Guide',
                    'usage.md' => 'Usage Guide',
                ];
                
                foreach ($commonPaths as $path => $title) {
                    $defaultBranch = $repoData['default_branch'] ?? 'main';
                    $rawUrl = "https://raw.githubusercontent.com/{$username}/{$name}/{$defaultBranch}/{$path}";
                    
                    $rawResponse = Http::get($rawUrl);
                    if ($rawResponse->failed() && ($defaultBranch === 'main' || $defaultBranch === 'master')) {
                        $altBranch = $defaultBranch === 'main' ? 'master' : 'main';
                        $rawUrl = "https://raw.githubusercontent.com/{$username}/{$name}/{$altBranch}/{$path}";
                        $rawResponse = Http::get($rawUrl);
                    }
                    
                    if ($rawResponse->successful()) {
                        $pagesToSync[] = [
                            'title' => $title,
                            'slug' => Str::slug(pathinfo($path, PATHINFO_FILENAME)),
                            'path' => $path,
                            'raw_content' => $rawResponse->body(),
                        ];
                    }
                }
            }
            
            // Sync/Save pages in database
            $syncedPageIds = [];
            foreach ($pagesToSync as $pageInfo) {
                $pageSlug = $pageInfo['slug'];
                $pagePath = $pageInfo['path'];
                $pageTitle = $pageInfo['title'];
                $pageHtml = null;
                
                if (isset($pageInfo['raw_content'])) {
                    try {
                        $converter = new \League\CommonMark\CommonMarkConverter([
                            'html_input'         => 'strip',  // Strip raw HTML to prevent stored XSS
                            'allow_unsafe_links' => false,
                        ]);
                        $pageHtml = $converter->convert($pageInfo['raw_content'])->getContent();
                    } catch (\Exception $e) {
                        $this->error("    - Failed parsing fallback MD: " . $e->getMessage());
                    }
                } else {
                    $pageApiUrl = "https://api.github.com/repos/{$username}/{$name}/contents/{$pagePath}";
                    $pageRequest = Http::withHeaders([
                        'User-Agent' => 'Laravel-Github-Sync-Client',
                        'Accept' => 'application/vnd.github.html',
                    ]);
                    if (!empty($token)) {
                        $pageRequest = $pageRequest->withToken($token);
                    }
                    
                    $pageResponse = $pageRequest->get($pageApiUrl);
                    if ($pageResponse->successful()) {
                        $pageHtml = $pageResponse->body();
                    } else {
                        $defaultBranch = $repoData['default_branch'] ?? 'main';
                        $rawUrl = "https://raw.githubusercontent.com/{$username}/{$name}/{$defaultBranch}/{$pagePath}";
                        $rawResponse = Http::get($rawUrl);
                        if ($rawResponse->successful()) {
                            try {
                                $converter = new \League\CommonMark\CommonMarkConverter([
                                    'html_input'         => 'strip',  // Strip raw HTML to prevent stored XSS
                                    'allow_unsafe_links' => false,
                                ]);
                                $pageHtml = $converter->convert($rawResponse->body())->getContent();
                            } catch (\Exception $e) {
                                $this->error("    - Failed parsing fallback MD: " . $e->getMessage());
                            }
                        }
                    }
                }
                
                if ($pageHtml !== null) {
                    $savedPage = ProjectPage::updateOrCreate(
                        [
                            'project_id' => $project->id,
                            'slug' => $pageSlug,
                        ],
                        [
                            'title' => $pageTitle,
                            'path' => $pagePath,
                            'content_html' => $pageHtml,
                        ]
                    );
                    $syncedPageIds[] = $savedPage->id;
                    $this->line("    - Synced subpage: <info>{$pageTitle}</info> ({$pagePath})");
                }
            }
            
            // Delete deleted subpages
            ProjectPage::where('project_id', $project->id)
                ->whereNotIn('id', $syncedPageIds)
                ->delete();

            $count++;
        }

        if ($response->successful()) {
            // Delete projects in DB that are not in the list of synced repositories
            $deletedCount = Project::whereNotIn('name', $syncedProjectNames)->delete();
            if ($deletedCount > 0) {
                $this->info("Deleted {$deletedCount} obsolete/removed repositories from the system.");
            }
        }

        $this->info("Sync complete! Successfully synchronized {$count} repositories.");
        return Command::SUCCESS;
    }
}
