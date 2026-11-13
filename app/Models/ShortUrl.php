<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ShortUrl extends Model
{
    protected $fillable = ['user_id', 'original_url', 'short_code'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeVisibleTo(Builder $query, User $user)
    {
        if ($user->isAdmin()) {
            return $query->whereHas('user', function($q) use ($user) {
                $q->where('company_id', $user->company_id);
            });
        }
        if ($user->isMember()) {
            return $query->where('user_id', $user->id);
        }

        abort(403);
    }
}
