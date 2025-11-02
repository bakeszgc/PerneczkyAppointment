<?php

namespace App\Models;

use App\Notifications\PasswordResetNotification;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'created_at'
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
}
