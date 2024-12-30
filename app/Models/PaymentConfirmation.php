<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentConfirmation extends Model
{
    protected $fillable = [
        'order_id',
        'payment_method_id',
        'source_bank_name',
        'source_account_name',
        'amount',
        'image',
        'transfer_date',
    ];

    public function order():BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function paymentMethod():BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
