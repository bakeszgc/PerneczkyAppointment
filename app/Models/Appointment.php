<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['barber_id','service_id','user_id','app_start_time','app_end_time','comment','price'];

    public function user():BelongsTo {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function barber():BelongsTo {
        return $this->belongsTo(Barber::class)->withTrashed();
    }

    public function service():BelongsTo {
        return $this->belongsTo(Service::class)->withTrashed();
    }
    
    public static function closingHour (Carbon $date):int {
        if ($date->format('D') == 'Sun') {
            return 18;
        } else {
            return 20;
        }
    }

    public function getDuration() {
        $start = Carbon::parse($this->app_start_time);
        $end = Carbon::parse($this->app_end_time);

        return $start->diffInMinutes($end);
    }

    public function isDeleted() {
        return $this->deleted_at ? 'Cancelled' : '';
    }

    // SCOPES

    // LATER & EARLIER APPOINTMENTS
    public function scopeStartLaterThan(Builder $query, Carbon $dateTime, bool $allowEqual = true) {
        $operator = $allowEqual ? '>=' : '>';
        $query->where('app_start_time',$operator,$dateTime);
    }

    public function scopeStartEarlierThan(Builder $query, Carbon $dateTime, bool $allowEqual = true) {
        $operator = $allowEqual ? '<=' : '<';
        $query->where('app_start_time',$operator,$dateTime);
    }

    public function scopeEndLaterThan(Builder $query, Carbon $dateTime, bool $allowEqual = true) {
        $operator = $allowEqual ? '>=' : '>';
        $query->where('app_end_time',$operator,$dateTime);
    }

    public function scopeEndEarlierThan(Builder $query, Carbon $dateTime, bool $allowEqual = true) {
        $operator = $allowEqual ? '<=' : '<';
        $query->where('app_end_time',$operator,$dateTime);
    }

    // UPCOMING & PREVIOUS APPOINTMENTS
    public function scopeUpcoming(Builder $query) {
        $query->startLaterThan(now('Europe/Budapest'))->orderBy('app_start_time');
    }

    public function scopePrevious(Builder $query) {
        $query->startEarlierThan(now('Europe/Budapest'))->orderBy('app_start_time','desc');
    }

    // APPOINTMENTS OF A CERTAIN BARBER OR USER
    public function scopeBarberFilter(Builder $query, Barber|HasOne $barber) {
        $query->where('barber_id','=',$barber->id);
    }

    public function scopeUserFilter(Builder $query, User|BelongsTo $user) {
        $query->where('user_id','=',$user   ->id);
    }

    // WITHOUT TIMEOFFS OR TIMEOFFS ONLY
    public function scopeWithoutTimeOffs(Builder $query) {
        $query->where('service_id','!=',1);
    }
}
