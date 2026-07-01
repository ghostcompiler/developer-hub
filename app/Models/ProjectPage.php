<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable(['project_id', 'title', 'slug', 'path', 'content_html'])]
class ProjectPage extends Model
{
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Generates a Table of Contents list from the page HTML.
     */
    public function getTableOfContents(): array
    {
        if (empty($this->content_html)) {
            return [];
        }

        // Match h2 and h3 elements
        preg_match_all('/<h([2-3])[^>]*>(.*?)<\/h\1>/is', $this->content_html, $headings);

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
     * Get the content HTML with slugified IDs injected into h2 and h3 elements.
     */
    public function getFormattedContentHtmlAttribute(): string
    {
        if (empty($this->content_html)) {
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
        }, $this->content_html);

        return $processed;
    }
}
