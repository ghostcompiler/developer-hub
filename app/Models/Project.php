<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable([
    'name',
    'slug',
    'description',
    'stars_count',
    'forks_count',
    'open_issues_count',
    'downloads_count',
    'language',
    'license_name',
    'github_url',
    'homepage_url',
    'default_branch',
    'topics',
    'releases_info',
    'readme_html',
    'synced_at'
])]
class Project extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'topics' => 'array',
            'releases_info' => 'array',
            'synced_at' => 'datetime',
        ];
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the subpages for the project.
     */
    public function pages()
    {
        return $this->hasMany(ProjectPage::class);
    }

    /**
     * Generates a Table of Contents list from the README HTML.
     */
    public function getTableOfContents(): array
    {
        if (empty($this->readme_html)) {
            return [];
        }

        // Match h2 and h3 elements
        preg_match_all('/<h([2-3])[^>]*>(.*?)<\/h\1>/is', $this->readme_html, $headings);

        $toc = [];
        $slugCounts = [];

        foreach ($headings[0] as $i => $fullHeading) {
            $level = (int)$headings[1][$i];
            $content = $headings[2][$i];

            $text = trim(strip_tags($content));
            if (empty($text)) {
                continue;
            }

            // Generate unique slug
            $slug = Str::slug($text);
            if (empty($slug)) {
                $slug = 'section';
            }

            if (isset($slugCounts[$slug])) {
                $slugCounts[$slug]++;
                $slug = $slug . '-' . $slugCounts[$slug];
            } else {
                $slugCounts[$slug] = 0;
            }

            $toc[] = [
                'level' => $level,
                'anchor' => $slug,
                'text' => $text,
            ];
        }

        return $toc;
    }

    /**
     * Get the README HTML with slugified IDs injected into h2 and h3 elements.
     */
    public function getFormattedReadmeHtmlAttribute(): string
    {
        if (empty($this->readme_html)) {
            return '';
        }

        $slugCounts = [];

        $processed = preg_replace_callback('/<h([2-3])([^>]*)>(.*?)<\/h\1>/is', function ($matches) use (&$slugCounts) {
            $level = $matches[1];
            $attrs = $matches[2];
            $content = $matches[3];

            $text = trim(strip_tags($content));
            if (empty($text)) {
                return $matches[0];
            }

            $slug = Str::slug($text);
            if (empty($slug)) {
                $slug = 'section';
            }

            if (isset($slugCounts[$slug])) {
                $slugCounts[$slug]++;
                $slug = $slug . '-' . $slugCounts[$slug];
            } else {
                $slugCounts[$slug] = 0;
            }

            // Strip any existing id from attributes
            $attrs = preg_replace('/id="[^"]*"/i', '', $attrs);
            $attrs = trim($attrs);

            return "<h{$level} id=\"{$slug}\" " . ($attrs ? $attrs . ' ' : '') . ">{$content}</h{$level}>";
        }, $this->readme_html);

        return $processed;
    }
}
