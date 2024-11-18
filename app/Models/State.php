<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class States extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'state',
        'reservation_id'
    ];
    public function reservation():belongsTo{
        return $this->belongsTo(Reservation::class);
    }
}
