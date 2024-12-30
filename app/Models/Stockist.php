<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stockist extends Model
{
    protected $fillable = [
        'name',
        'email',
        'address',
        'phone',
        'foto_profile',
        'foto_ktp',
        'facebook',
        'instagram',
        'tiktok',
        'is_active',
    ];
}
