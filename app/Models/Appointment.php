<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['barber_id','service_id','user_id','app_start_time','app_end_time','comment','price'];

    public function user():BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function barber():BelongsTo {
        return $this->belongsTo(Barber::class);
    }

    public function service():BelongsTo {
        return $this->belongsTo(Service::class);
    }
    
    public static function closingHour (Carbon $date):int {
        if ($date->format('D') == 'Sun') {
            return 18;
        } else {
            return 20;
        }
    }
}
