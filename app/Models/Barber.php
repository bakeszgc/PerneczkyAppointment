<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barber extends Model
{
    /** @use HasFactory<\Database\Factories\BarberFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['display_name','is_visible','user_id','description'];

    public function user():BelongsTo {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function appointments():HasMany {
        return $this->hasMany(Appointment::class)->withTrashed();
    }

    public function getPicture() {
        if ($this->user->pfp_path && Storage::disk('public')->exists('pfp/' . $this->user->pfp_path)) {
            return asset('storage/pfp/' . $this->user->pfp_path);
        } else {
            return asset('pictures/pfp_blank.png');
        }
    }

    public function getName() {
        return $this->display_name ?? $this->user->first_name;
    }

    public function isDeleted() {
        return $this->deleted_at ? '(deleted)' : '';
    }
}
