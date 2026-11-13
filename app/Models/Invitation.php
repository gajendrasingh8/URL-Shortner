<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Override;

class Invitation extends Model
{
    protected $fillable = ['email', 'role_id', 'company_id', 'invited_by', 'token', 'expires_at','accepted_at'];

    protected function casts()
    {
        return [
            'expires_at' => 'datetime',
            'accepted_at' => 'datetime'
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
}
