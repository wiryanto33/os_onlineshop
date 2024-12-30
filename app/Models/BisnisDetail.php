<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BisnisDetail extends Model
{
    protected $fillable = [
       'bisnis_name',
       'bisnis_image',
       'banner',
       'bisnis_description',
    ];


    public function bisnisRules():HasMany
    {
        return $this->hasMany(BisnisRule::class);
    }

    public function bisnisBenefits():HasMany
    {
        return $this->hasMany(BisnisBenefit::class);
    }
}
