<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable(['user_id', 'title', 'slug', 'summary', 'content', 'status'])]
class Blog extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the formatted blog content HTML.
     */
    public function getFormattedContentAttribute(): string
    {
        if (empty($this->content)) {
            return '';
        }

        try {
            $converter = new \League\CommonMark\CommonMarkConverter([
                'html_input'         => 'strip',  // Strip raw HTML to prevent stored XSS
                'allow_unsafe_links' => false,
            ]);
            return $converter->convert($this->content)->getContent();
        } catch (\Exception $e) {
            return nl2br(e($this->content));
        }
    }

    /**
     * Scope to only include approved blogs of active users.
     */
    public function scopeActiveAndApproved($query)
    {
        return $query->where('status', 'approved')
            ->whereHas('user', function ($q) {
                $q->where('status', 'active');
            });
    }
}
