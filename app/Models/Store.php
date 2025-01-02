<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'banner',
        'advertise',
        'info_swiper',
        'address',
        'whatsapp',
        'province_id',
        'regency_id',
        'subdistrict_id',
        'province_name',
        'regency_name',
        'subdistrict_name',
        'email_notification',
        'payment_gateway',
    ];

    protected $casts = [
        'banner' => 'array',
        'info_swiper' => 'array',
    ];

    public function getImageUrlAttribute()
    {
        return $this->image ? url('storage/' . $this->image) : null;
    }

    public function getBannerUrlAttribute()
    {
        return $this->banner ? url('storage/' . $this->banner) : null;
    }

}
