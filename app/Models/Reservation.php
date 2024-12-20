<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class Reservation extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'checkin_at',
        'checkout_at',
        'deleted_at'
    ];

    public function guests(){
        return $this->belongsToMany(Guest::class)
        ->withPivot(['checkin_at','checkout_at','type']);
    }
    public function payments():HasMany{
        return $this->hasMany(Payment::class);
    }
    public function services():BelongsToMany{
        return $this->belongsToMany(Services::class);
    }
    public function rooms():BelongsToMany{
        return $this->belongsToMany(Room::class);
    }
}
