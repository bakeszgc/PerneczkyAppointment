<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['barber_id','service_id','user_id','app_start_time','app_end_time','comment','price'];

    // RELATIONSHIP METHODS
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

    public static function formatDuration(int $duration) {
        $days = floor($duration / 60 / 24);
        $hours = floor(($duration % (24 * 60)) / 60);
        $minutes = $duration % 60;

        $daysText = $days != 0 ? ($days . ' ' . Str::plural('day',$days) . ' ') : '';
        $hoursText = $hours != 0 ? ($hours . ' ' . Str::plural('hour',$hours) . ' ') : '';
        $minutesText = $minutes != 0 || ($hours == 0 && $days == 0) ? ($minutes . ' ' . Str::plural('minute',$minutes)) : '';

        return $daysText . $hoursText . $minutesText;
    }

    public function isDeleted() {
        return $this->deleted_at ? 'Cancelled' : '';
    }

    public function isFullDay() {
        return Carbon::parse($this->app_start_time)->format('G') == 10 && $this->getDuration() >= 600;
    }

    public static function getSumOfBookings(?Barber $barber = null, ?User $user = null) {
        $sumOfBookings = [
            'previous' => [
                'all time' => [
                    'count' => Appointment::withoutTimeOffs()->previous()->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->when(isset($user), function ($q) use ($user) {
                        return $q->userFilter($user);
                    })->count(),
                    'value' => Appointment::withoutTimeOffs()->previous()->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->when(isset($user), function ($q) use ($user) {
                        return $q->userFilter($user);
                    })->sum('price')
                ],
                'past month' => [
                    'count' => Appointment::withoutTimeOffs()->previous()->startLaterThan(now()->subMonth())->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->when(isset($user), function ($q) use ($user) {
                        return $q->userFilter($user);
                    })->count(),
                    'value' => Appointment::withoutTimeOffs()->previous()->startLaterThan(now()->subMonth())->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->when(isset($user), function ($q) use ($user) {
                        return $q->userFilter($user);
                    })->sum('price')
                ],
                'past week' => [
                    'count' => Appointment::withoutTimeOffs()->previous()->startLaterThan(now()->subWeek())->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->when(isset($user), function ($q) use ($user) {
                        return $q->userFilter($user);
                    })->count(),
                    'value' => Appointment::withoutTimeOffs()->previous()->startLaterThan(now()->subWeek())->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->when(isset($user), function ($q) use ($user) {
                        return $q->userFilter($user);
                    })->sum('price')
                ],
                'past day' => [
                    'count' => Appointment::withoutTimeOffs()->previous()->startLaterThan(now()->subDay())->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->when(isset($user), function ($q) use ($user) {
                        return $q->userFilter($user);
                    })->count(),
                    'value' => Appointment::withoutTimeOffs()->previous()->startLaterThan(now()->subDay())->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->when(isset($user), function ($q) use ($user) {
                        return $q->userFilter($user);
                    })->sum('price')
                ]
            ],
            'upcoming' => [
                'all time' => [
                    'count' => Appointment::withoutTimeOffs()->upcoming()->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->when(isset($user), function ($q) use ($user) {
                        return $q->userFilter($user);
                    })->count(),
                    'value' => Appointment::withoutTimeOffs()->upcoming()->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->when(isset($user), function ($q) use ($user) {
                        return $q->userFilter($user);
                    })->sum('price')
                ],
                'next month' => [
                    'count' => Appointment::withoutTimeOffs()->upcoming()->startEarlierThan(now()->addMonth())->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->when(isset($user), function ($q) use ($user) {
                        return $q->userFilter($user);
                    })->count(),
                    'value' => Appointment::withoutTimeOffs()->upcoming()->startEarlierThan(now()->addMonth())->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->when(isset($user), function ($q) use ($user) {
                        return $q->userFilter($user);
                    })->sum('price')
                ],
                'next week' => [
                    'count' => Appointment::withoutTimeOffs()->upcoming()->startEarlierThan(now()->addWeek())->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->when(isset($user), function ($q) use ($user) {
                        return $q->userFilter($user);
                    })->count(),
                    'value' => Appointment::withoutTimeOffs()->upcoming()->startEarlierThan(now()->addWeek())->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->when(isset($user), function ($q) use ($user) {
                        return $q->userFilter($user);
                    })->sum('price')
                ],
                'next day' => [
                    'count' => Appointment::withoutTimeOffs()->upcoming()->startEarlierThan(now()->addDay())->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->when(isset($user), function ($q) use ($user) {
                        return $q->userFilter($user);
                    })->count(),
                    'value' => Appointment::withoutTimeOffs()->upcoming()->startEarlierThan(now()->addDay())->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->when(isset($user), function ($q) use ($user) {
                        return $q->userFilter($user);
                    })->sum('price')
                ]
            ],
            'cancelled' => [
                'all time' => [
                    'count' => Appointment::withoutTimeOffs()->onlyTrashed()->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->when(isset($user), function ($q) use ($user) {
                        return $q->userFilter($user);
                    })->count(),
                    'value' => Appointment::withoutTimeOffs()->onlyTrashed()->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->when(isset($user), function ($q) use ($user) {
                        return $q->userFilter($user);
                    })->sum('price')
                ],
                'previous' => [
                    'count' => Appointment::withoutTimeOffs()->onlyTrashed()->previous()->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->when(isset($user), function ($q) use ($user) {
                        return $q->userFilter($user);
                    })->count(),
                    'value' => Appointment::withoutTimeOffs()->onlyTrashed()->previous()->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->when(isset($user), function ($q) use ($user) {
                        return $q->userFilter($user);
                    })->sum('price')
                ],
                'upcoming' => [
                    'count' => Appointment::withoutTimeOffs()->onlyTrashed()->upcoming()->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->when(isset($user), function ($q) use ($user) {
                        return $q->userFilter($user);
                    })->count(),
                    'value' => Appointment::withoutTimeOffs()->onlyTrashed()->upcoming()->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->when(isset($user), function ($q) use ($user) {
                        return $q->userFilter($user);
                    })->sum('price')
                ]
            ]
        ];

        return $sumOfBookings;
    }

    public static function getSumOfTimeOffs(?Barber $barber = null) {
        $sumOfTimeOffs = [
            'previous' => [
                'all time' => [
                    'count' => Appointment::onlyTimeOffs()->previous()->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->count(),
                    'value' => Appointment::onlyTimeOffs()->previous()->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->get()->sum(function ($appointment) {
                        return $appointment->getDuration();
                    })
                ],
                'past month' => [
                    'count' => Appointment::onlyTimeOffs()->previous()->startLaterThan(now()->subMonth())->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->count(),
                    'value' => Appointment::onlyTimeOffs()->previous()->startLaterThan(now()->subMonth())->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->get()->sum(function ($appointment) {
                        return $appointment->getDuration();
                    })
                ],
                'past week' => [
                    'count' => Appointment::onlyTimeOffs()->previous()->startLaterThan(now()->subWeek())->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->count(),
                    'value' => Appointment::onlyTimeOffs()->previous()->startLaterThan(now()->subWeek())->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->get()->sum(function ($appointment) {
                        return $appointment->getDuration();
                    })
                ],
                'past day' => [
                    'count' => Appointment::onlyTimeOffs()->previous()->startLaterThan(now()->subDay())->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->count(),
                    'value' => Appointment::onlyTimeOffs()->previous()->startLaterThan(now()->subDay())->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->get()->sum(function ($appointment) {
                        return $appointment->getDuration();
                    })
                ]
            ],
            'upcoming' => [
                'all time' => [
                    'count' => Appointment::onlyTimeOffs()->upcoming()->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->count(),
                    'value' => Appointment::onlyTimeOffs()->upcoming()->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->get()->sum(function ($appointment) {
                        return $appointment->getDuration();
                    })
                ],
                'next month' => [
                    'count' => Appointment::onlyTimeOffs()->upcoming()->startEarlierThan(now()->addMonth())->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->count(),
                    'value' => Appointment::onlyTimeOffs()->upcoming()->startEarlierThan(now()->addMonth())->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->get()->sum(function ($appointment) {
                        return $appointment->getDuration();
                    })
                ],
                'next week' => [
                    'count' => Appointment::onlyTimeOffs()->upcoming()->startEarlierThan(now()->addWeek())->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->count(),
                    'value' => Appointment::onlyTimeOffs()->upcoming()->startEarlierThan(now()->addWeek())->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->get()->sum(function ($appointment) {
                        return $appointment->getDuration();
                    })
                ],
                'next day' => [
                    'count' => Appointment::onlyTimeOffs()->upcoming()->startEarlierThan(now()->addDay())->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->count(),
                    'value' => Appointment::onlyTimeOffs()->upcoming()->startEarlierThan(now()->addDay())->when(isset($barber), function ($q) use ($barber) {
                        return $q->barberFilter($barber);
                    })->get()->sum(function ($appointment) {
                        return $appointment->getDuration();
                    })
                ]
            ]
        ];

        return $sumOfTimeOffs;
    }

    // RETRIEVING ALL FREE TIMESLOTS FOR THE NEXT DAYS
    public static function getFreeTimeSlots(Barber $barber, Service $service, int $numberOfDays = 14)  {
        
        // ALL TIMESLOTS (15 MIN LONG EACH)
        $allDates = [];

        for ($d=0; $d < $numberOfDays; $d++) {
            for ($h=10; $h < Appointment::closingHour(today()->addDays($d)); $h++) { 
                for ($m=0; $m < 60; $m+=15) {
                    $time = today()->addDays($d)->addHours($h)->addMinutes($m);

                    if ($time >= now('Europe/Budapest')) {
                        $allDates[] = $time;
                    }
                }
            }
        }

        // RESERVED TIMESLOTS FOR THE SELECTED BARBER
        $reservedDates = Appointment::barberFilter($barber)->pluck('app_start_time')
        ->map(fn ($time) => Carbon::parse($time))->toArray();

        // TIMESLOTS THOSE WOULD OVERLAP WITH ANOTHER BOOKING
        $overlapDates = [];

        foreach ($reservedDates as $date) {
            $appointments = Appointment::barberFilter($barber)
            ->where('app_start_time','=',$date)->get();

            foreach ($appointments as $appointment)
            {
                $appDuration = $appointment->getDuration();
                $serviceDuration = $service->duration;

                for ($i=0; $i < $appDuration/15; $i++) { 
                    $overlapDates[] = Carbon::parse($date)->clone()->addMinutes($i*15);
                }
                for ($i=0; $i < $serviceDuration/15; $i++) { 
                    $overlapDates[] = Carbon::parse($date)->clone()->addMinutes($i*-15);
                }
            }
        }

        // ALL FREE DATES
        $freeDates = array_diff($allDates,$reservedDates,$overlapDates);

        // CONVERTING FREE DATES
        $availableSlotsByDate = [];

        foreach ($freeDates as $date) {
            $actualDate = Carbon::parse($date)->format('Y-m-d');

            if (!isset($availableSlotsByDate[$actualDate])) {
                $availableSlotsByDate[$actualDate] = [];
            }

            $availableSlotsByDate[$actualDate][] = $date->format('G:i');
        }

        return $availableSlotsByDate;
    }

    // CHECKING IF A BEING CREATED/EDITED APPOINTMENT CLASHING WITH OTHER ONES
    // RETURNS TRUE IF THERE ARE NO CLASHES
    public static function checkAppointmentClashes(Carbon $appStartTime, Carbon $appEndTime, Barber $barber, ?Appointment $appointment = null) {
        
        // APPOINTMENTS STARTING DURING THE NEW APPOINTMENT
        $appointmentsStart = Appointment::barberFilter($barber)
        ->startLaterThan($appStartTime)
        ->startEarlierThan($appEndTime,false)
        ->when($appointment, function ($q) use ($appointment) {
            $q->where('id','!=',$appointment->id);
        })->get();

        // APPOINTMENTS ENDING DURING THE NEW APPOINTMENT
        $appointmentsEnd = Appointment::barberFilter($barber)
        ->endLaterThan($appStartTime,false)
        ->endEarlierThan($appEndTime)
        ->when($appointment, function ($q) use ($appointment) {
            $q->where('id','!=',$appointment->id);
        })->get();

        // APPOINTMENTS STARTING BEFORE AND ENDING AFTER THE NEW APPOINTMENT
        $appointmentsBetween = Appointment::barberFilter($barber)
        ->startEarlierThan($appStartTime)
        ->endLaterThan($appEndTime)
        ->when($appointment, function ($q) use ($appointment) {
            $q->where('id','!=',$appointment->id);
        })->get();

        if ($appointmentsStart->count() + $appointmentsEnd->count() + $appointmentsBetween->count() == 0) {
            return true;
        } else {
            return false;
        }
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
        $query->where('user_id','=',$user->id);
    }

    // APPOINTMENTS OF A CERTAIN SERVICE
    public function scopeServiceFilter(Builder $query, Service|HasOne $service) {
        $query->where('service_id','=',$service->id);
    }

    // WITHOUT TIMEOFFS OR TIMEOFFS ONLY
    public function scopeWithoutTimeOffs(Builder $query) {
        $query->where('service_id','!=',1);
    }

    public function scopeOnlyTimeOffs(Builder $query) {
        $query->where('service_id','=',1);
    }
}
