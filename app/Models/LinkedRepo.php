<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'title', 'description', 'repo_url', 'status'])]
class LinkedRepo extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to only include approved repos of active users.
     */
    public function scopeActiveAndApproved($query)
    {
        return $query->where('status', 'approved')
            ->whereHas('user', function ($q) {
                $q->where('status', 'active');
            });
    }
}
