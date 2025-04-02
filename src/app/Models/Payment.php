<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'reservation_id',
        'stripe_payment_id',
        'amount',
        'status',
        'paid_at'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
