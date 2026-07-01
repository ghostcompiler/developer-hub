<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::orderBy('stars_count', 'desc')->get();
        $linkedRepos = \App\Models\LinkedRepo::activeAndApproved()->orderBy('created_at', 'desc')->paginate(6, ['*'], 'repos_page');
        
        // Get all unique languages
        $languages = $projects->pluck('language')->filter()->unique()->values()->all();
        
        // Get all unique topics
        $topics = $projects->flatMap(function ($project) {
            return $project->topics ?? [];
        })->filter()->unique()->values()->all();

        return view('projects.index', compact('projects', 'languages', 'topics', 'linkedRepos'));
    }

    private function getCachedTree(Project $project): array
    {
        $cacheKey = "project_tree_{$project->id}";
        
        $treeData = Cache::remember($cacheKey, 1800, function () use ($project) {
            try {
                $username = 'ghostcompiler';
                $name = $project->name;
                $branch = $project->default_branch ?: 'main';
                $token = Setting::get('github_token', '');
                
                $url = "https://api.github.com/repos/{$username}/{$name}/git/trees/{$branch}?recursive=1";
                
                $request = Http::timeout(3)->withHeaders(['User-Agent' => 'Laravel-Github-Sync-Client']);
                if (!empty($token)) {
                    $request = $request->withToken($token);
                }
                
                $response = $request->get($url);
                if ($response->successful()) {
                    return $response->json();
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("Failed to fetch tree for project {$project->name}: " . $e->getMessage());
            }
            
            return null;
        });
        
        if (empty($treeData) || !isset($treeData['tree'])) {
            return [];
        }
        
        // Filter out vendor, node_modules, and git files to keep list clean
        return array_values(array_filter($treeData['tree'], function ($item) {
            $path = $item['path'];
            return !str_starts_with($path, '.git/') && 
                   !str_contains($path, 'node_modules/') && 
                   !str_contains($path, 'vendor/');
        }));
    }

    /**
     * Display the specified project.
     */
    public function show(string $slug)
    {
        $project = Project::with('pages')->where('slug', $slug)->firstOrFail();
        
        // Get other projects for navigation sidebar
        $otherProjects = Project::orderBy('stars_count', 'desc')
            ->where('id', '!=', $project->id)
            ->get();
            
        $toc = $project->getTableOfContents();
        $files = $this->getCachedTree($project);

        return view('projects.show', compact('project', 'otherProjects', 'toc', 'files'));
    }

    /**
     * Display the specified project page.
     */
    public function showPage(string $slug, string $pageSlug)
    {
        $project = Project::with('pages')->where('slug', $slug)->firstOrFail();
        
        $activePage = $project->pages->where('slug', $pageSlug)->first();
        if (!$activePage) {
            abort(404);
        }
        
        // Get other projects for navigation sidebar
        $otherProjects = Project::orderBy('stars_count', 'desc')
            ->where('id', '!=', $project->id)
            ->get();
            
        $toc = $activePage->getTableOfContents();
        $files = $this->getCachedTree($project);

        return view('projects.show', compact('project', 'otherProjects', 'toc', 'activePage', 'files'));
    }

    /**
     * Display the specified project file.
     */
    public function showFile(string $slug, string $path)
    {
        $project = Project::with('pages')->where('slug', $slug)->firstOrFail();
        
        // Get other projects for navigation sidebar
        $otherProjects = Project::orderBy('stars_count', 'desc')
            ->where('id', '!=', $project->id)
            ->get();
            
        $toc = $project->getTableOfContents();
        $files = $this->getCachedTree($project);
        
        // Fetch file content from GitHub raw URL on server side
        $username = 'ghostcompiler';
        $name = $project->name;
        $branch = $project->default_branch ?: 'main';
        $rawUrl = "https://raw.githubusercontent.com/{$username}/{$name}/{$branch}/{$path}";
        
        $token = Setting::get('github_token', '');
        $request = Http::withHeaders(['User-Agent' => 'Laravel-Github-Sync-Client']);
        if (!empty($token)) {
            $request = $request->withToken($token);
        }
        
        $response = $request->get($rawUrl);
        $fileContent = $response->successful() ? $response->body() : 'Could not load file contents from GitHub.';
        $activeFilePath = $path;
        
        return view('projects.show', compact('project', 'otherProjects', 'toc', 'files', 'activeFilePath', 'fileContent'));
    }

    /**
     * Generate the sitemap.xml for SEO indexing.
     */
    public function sitemap()
    {
        $projects = Project::with('pages:id,project_id,slug,updated_at')
            ->select('id', 'slug', 'name', 'default_branch', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        $blogs = \App\Models\Blog::activeAndApproved()
            ->orderBy('updated_at', 'desc')
            ->get();
        
        $xml = [];
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Homepage
        $xml[] = '  <url>';
        $xml[] = '    <loc>' . url('/') . '</loc>';
        $xml[] = '    <lastmod>' . now()->toAtomString() . '</lastmod>';
        $xml[] = '    <changefreq>daily</changefreq>';
        $xml[] = '    <priority>1.0</priority>';
        $xml[] = '  </url>';

        // Blogs Feed
        $xml[] = '  <url>';
        $xml[] = '    <loc>' . url('/blogs') . '</loc>';
        $xml[] = '    <lastmod>' . now()->toAtomString() . '</lastmod>';
        $xml[] = '    <changefreq>daily</changefreq>';
        $xml[] = '    <priority>0.8</priority>';
        $xml[] = '  </url>';
        
        // Project Detail Pages, Subpages, and Repository Files
        foreach ($projects as $project) {
            $xml[] = '  <url>';
            $xml[] = '    <loc>' . url('/projects/' . $project->slug) . '</loc>';
            $xml[] = '    <lastmod>' . $project->updated_at->toAtomString() . '</lastmod>';
            $xml[] = '    <changefreq>daily</changefreq>';
            $xml[] = '    <priority>0.8</priority>';
            $xml[] = '  </url>';

            foreach ($project->pages as $page) {
                $xml[] = '  <url>';
                $xml[] = '    <loc>' . url('/projects/' . $project->slug . '/' . $page->slug) . '</loc>';
                $xml[] = '    <lastmod>' . $page->updated_at->toAtomString() . '</lastmod>';
                $xml[] = '    <changefreq>daily</changefreq>';
                $xml[] = '    <priority>0.6</priority>';
                $xml[] = '  </url>';
            }

            // Add all the repository files to the sitemap for direct crawler indexing
            $files = $this->getCachedTree($project);
            foreach ($files as $file) {
                if ($file['type'] === 'blob') {
                    $xml[] = '  <url>';
                    $xml[] = '    <loc>' . url('/projects/' . $project->slug . '/files/' . $file['path']) . '</loc>';
                    $xml[] = '    <lastmod>' . $project->updated_at->toAtomString() . '</lastmod>';
                    $xml[] = '    <changefreq>weekly</changefreq>';
                    $xml[] = '    <priority>0.4</priority>';
                    $xml[] = '  </url>';
                }
            }
        }

        // Blog Pages
        foreach ($blogs as $blog) {
            $xml[] = '  <url>';
            $xml[] = '    <loc>' . url('/blogs/' . $blog->slug) . '</loc>';
            $xml[] = '    <lastmod>' . $blog->updated_at->toAtomString() . '</lastmod>';
            $xml[] = '    <changefreq>daily</changefreq>';
            $xml[] = '    <priority>0.7</priority>';
            $xml[] = '  </url>';
        }
        
        $xml[] = '</urlset>';
        
        return response(implode("\n", $xml), 200, [
            'Content-Type' => 'application/xml',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    /**
     * Get the repository file tree list.
     */
    public function tree(string $slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();
        $filteredTree = $this->getCachedTree($project);
        
        return response()->json([
            'default_branch' => $project->default_branch ?: 'main',
            'tree' => $filteredTree
        ]);
    }

    /**
     * Generate the llms.txt text file for AI bot scraping.
     */
    public function llmsText()
    {
        $projects = Project::with('pages')->orderBy('stars_count', 'desc')->get();
        $blogs = \App\Models\Blog::activeAndApproved()->orderBy('created_at', 'desc')->get();
        $linkedRepos = \App\Models\LinkedRepo::activeAndApproved()->orderBy('created_at', 'desc')->get();
        
        $out = [];
        $out[] = "# Ghost Compiler";
        $out[] = "> Open source developer hub publishing packages, SDKs, and engineering insights.";
        $out[] = "";
        $out[] = "This file provides indexable resources, project documentations, and technical blogs published on ghostcompiler.in.";
        $out[] = "";
        
        $out[] = "## Projects & SDKs";
        $out[] = "";
        foreach ($projects as $project) {
            $desc = str_replace(["\r", "\n"], ' ', $project->description);
            // This format matches the expected test assertion link: - [name](url): description
            $out[] = "- [" . $project->name . "](" . url('/projects/' . $project->slug) . "): " . $desc . " (" . $project->language . ", " . $project->stars_count . " stars)";
            
            // Render plain text README content as sub-text
            if (!empty($project->readme_html)) {
                $out[] = "";
                $out[] = "  **README Content**:";
                $readmeText = trim(html_entity_decode(strip_tags($project->readme_html)));
                $lines = explode("\n", $readmeText);
                foreach ($lines as $line) {
                    if (trim($line) !== '') {
                        $out[] = "  > " . trim($line);
                    }
                }
            }

            // Render file tree structure and links as sub-list items
            $files = $this->getCachedTree($project);
            if (count($files) > 0) {
                $out[] = "";
                $out[] = "  **Repository Files & Source Code**:";
                foreach ($files as $file) {
                    if ($file['type'] === 'blob') {
                        $out[] = "  - [" . $file['path'] . "](" . url('/projects/' . $project->slug . '/files/' . $file['path']) . ")";
                    }
                }
            }
            $out[] = "";
        }
        $out[] = "";
        
        $out[] = "## Documentation Pages";
        $out[] = "";
        foreach ($projects as $project) {
            if ($project->pages->isNotEmpty()) {
                foreach ($project->pages as $p) {
                    $out[] = "- [" . ucwords(str_replace('-', ' ', $project->name)) . " — " . $p->title . "](" . url('/projects/' . $project->slug . '/' . $p->slug) . "): Documentation section for " . $p->title;
                }
            }
        }
        $out[] = "";
        
        $out[] = "## Developer Blogs";
        $out[] = "";
        foreach ($blogs as $blog) {
            $summary = str_replace(["\r", "\n"], ' ', $blog->summary);
            $out[] = "- [" . $blog->title . "](" . url('/blogs/' . $blog->slug) . "): " . $summary;
        }
        $out[] = "";
        
        $out[] = "## Community Contributed Repositories";
        $out[] = "";
        foreach ($linkedRepos as $repo) {
            $desc = str_replace(["\r", "\n"], ' ', $repo->description);
            $out[] = "- [" . $repo->title . "](" . $repo->repo_url . "): " . $desc . " (Contributed by " . $repo->user->name . ")";
        }
        $out[] = "";
 
        $out[] = "## Legal Policies";
        $out[] = "";
        $out[] = "- [Privacy Policy](" . url('/privacy-policy') . "): Legal policy regarding data privacy on Ghost Compiler.";
        $out[] = "- [Terms of Service](" . url('/terms-of-service') . "): Terms governing the usage of the Ghost Compiler platform.";
        $out[] = "- [Terms & Conditions](" . url('/terms-and-conditions') . "): Legal terms and conditions.";
        
        return response(implode("\n", $out), 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
