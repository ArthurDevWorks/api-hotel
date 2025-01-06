<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $fillabe = [
        'reservation_id',
        'total',
        'payment_date'
    ];

    public function reservations():BelongsTo{
        return $this->belongsTo(Reservation::class);
    }

    public function statuses():HasMany{
        return $this->hasMany(PaymentStatus::class);
    }
}
