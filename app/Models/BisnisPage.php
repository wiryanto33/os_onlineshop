<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BisnisPage extends Model
{
    protected $fillable = [
        'image_content',
    ];

    protected $casts = [
        'image_content' => 'array',
    ];
}
