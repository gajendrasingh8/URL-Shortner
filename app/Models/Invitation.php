<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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

}
