<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barber extends Model
{
    /** @use HasFactory<\Database\Factories\BarberFactory> */
    use HasFactory;

    public function user():BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function appointments():HasMany {
        return $this->hasMany(Appointment::class);
    }

    public function getPicture() {
        return $this->user->pfp_path ? asset('storage/pfp/' . $this->user->pfp_path) : asset('pictures/pfp_blank.png');
    }
}
