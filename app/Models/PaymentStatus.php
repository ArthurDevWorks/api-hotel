<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentStatus extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'payment_id',
        'state'
    ];

    public function payments():BelongsTo{
        return $this->belongsTo(Payment::class);
    }
}
