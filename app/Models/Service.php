<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['name','price','duration','is_visible'];

    public function appointments(): HasMany {
        return $this->hasMany(Appointment::class)->withTrashed();
    }

    public function isDeleted() {
        return $this->deleted_at ? '(deleted)' : '';
    }

    public function scopeWithoutTimeoff(Builder $query) {
        $query->where('id','!=',1);
    }
}
