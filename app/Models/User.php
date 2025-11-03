<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Notifications\PasswordResetNotification;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, \Illuminate\Auth\Passwords\CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'tel_number',
        'email',
        'password',
        'email_verified_at',
        'pfp_path',
        'is_admin',
        'created_at',
        'google_id',
        'facebook_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function appointments():HasMany {
        return $this->hasMany(Appointment::class)->withTrashed();
    }

    public function barber():HasOne {
        return $this->hasOne(Barber::class)->withTrashed();
    }

    public function isDeleted() {
        return $this->deleted_at ? ' (deleted)' : '';
    }

    public function sendPasswordResetNotification($token) {
        $this->notify(new PasswordResetNotification($token));
    }

    public function scopeHasStoredEmail(Builder $query) {
        $query->where('email','!=',null);
    }

    public function hasEmail() {
        return $this->email != null;
    }

    public function scopeIsRegistered(Builder $query) {
        $query->where('email','!=',null)->where('last_name','!=',null);
    }

    public function isRegistered() {
        return $this->hasEmail() && ($this->last_name != null || $this->google_id || $this->facebook_id);
    }
}
