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
    public function scopeLaterThan(Builder $query, Carbon $datetime, string $initDateTime = 'app_start_time') {
        $query->where($initDateTime,'>=',$datetime);
    }

    public function scopeEarlierThan(Builder $query, Carbon $datetime, string $initDateTime = 'app_start_time') {
        $query->where($initDateTime,'<',$datetime);
    }

    public function scopeUpcoming(Builder $query) {
        $query->laterThan(now('Europe/Budapest'))->orderBy('app_start_time');
    }

    public function scopePrevious(Builder $query) {
        $query->earlierThan(now('Europe/Budapest'))->orderBy('app_start_time','desc');
    }

    public function scopeBarberFilter(Builder $query, Barber|HasOne $barber) {
        $query->where('barber_id','=',$barber->id);
    }

    public function scopeUserFilter(Builder $query, User|BelongsTo $user) {
        $query->where('user_id','=',$user   ->id);
    }

    public function scopeWithoutTimeOffs(Builder $query) {
        $query->where('service_id','!=',1);
    }
}
