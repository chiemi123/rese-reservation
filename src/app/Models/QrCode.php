<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    protected $fillable = ['reservation_id', 'token', 'used_at'];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
