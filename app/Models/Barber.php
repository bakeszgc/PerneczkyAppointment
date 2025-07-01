<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barber extends Model
{
    /** @use HasFactory<\Database\Factories\BarberFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['display_name','is_visible','user_id'];

    public function user():BelongsTo {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function appointments():HasMany {
        return $this->hasMany(Appointment::class)->withTrashed();
    }

    public function getPicture() {
        return $this->user->pfp_path ? asset('storage/pfp/' . $this->user->pfp_path) : asset('pictures/pfp_blank.png');
    }

    public function getName() {
        return $this->display_name ?? $this->user->first_name;
    }
}
