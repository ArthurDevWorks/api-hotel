<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomImage extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'room_id',
        'url',
        'alt'
    ];
    public function rooms():BelongsTo{
        return $this->belongsTo(Room::class);
    }
}
