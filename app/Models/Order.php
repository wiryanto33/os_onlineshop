<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'subtotal',
        'total_amount',
        'status',
        'payment_status',
        'recipient_name',
        'phone',
        'province',
        'city',
        'subdistrict',
        'address_detail',
        'shipping_code',
        'shipping_service',
        'shipping_description',
        'shipping_number',
        'shipping_cost',
        'shipping_etd',
        'tracking_number',
        'notes',
        'snap_token',
        'detail_transaction'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
