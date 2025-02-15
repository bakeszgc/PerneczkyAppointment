<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Barber;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class MyAppointmentController extends Controller
{
    public function index()
    {
        $upcomingAppointments = Appointment::where([
            ['user_id','=',auth()->user()->id],
            ['app_start_time','>=',now()]
        ])->orderBy('app_start_time')->paginate(10);
        
        return view('my-appointment.index',[
            'appointments' => $upcomingAppointments
        ]);
    }
    
    public function create()
    {
        if (auth()->user()) {
            return redirect()->route('my-appointments.create.barber');
        } else {
            return view('my-appointment.create');
        }
    }

    public function createBarber() {
        if (!auth()->user()) {
           return redirect()->route('login');
        }
        $barbers = Barber::all();
        return view('my-appointment.create_barber',[
            'barbers' => $barbers
        ]);
    }

    public function createService(Request $request) {
        if (!auth()->user()) {
            return redirect()->route('my-appointments.create');
        }

        return view('my-appointment.create_service',[
            'barber' => Barber::where('id','=',$request->barber_id)->with('user')->firstOrFail(),
            'services' => Service::all()
        ]);
    }

    public function createDate(Request $request)
    {
        if (!auth()->user()) {
            return redirect()->route('login');
        }

        $allDates = [];
        for ($d=0; $d < 14; $d++) {
            for ($h=10; $h < Appointment::closingHour(today()->addDays($d)); $h++) { 
                for ($m=0; $m < 60; $m+=15) {

                    $time = today()->addDays($d)->addHours($h)->addMinutes($m);
                    if ($time >= now()->addHour()) {
                        $allDates[] = $time;
                    }
                }
            }
        }

        // foglalt app_start_timeok
        $reservedDates = Appointment::where('barber_id','=',$request->barber_id)->pluck('app_start_time')
        ->map(fn ($time) => Carbon::parse($time))->toArray();

        // timeslotok amikbe belelóg egy másik foglalás
        // timeslotok amik belelógnának egy következő foglalásba
        $overlapDates = [];
        foreach ($reservedDates as $date) {
            $appointments = Appointment::where('barber_id','=',$request->barber_id)
                ->where('app_start_time','=',$date)->get();
            $service = Service::findOrFail($request->service_id);

            foreach ($appointments as $appointment)
            {
                $appDuration = $appointment->service->duration;
                $serviceDuration = $service->duration;
                for ($i=0; $i < $appDuration/15; $i++) { 
                    $overlapDates[] = Carbon::parse($date)->clone()->addMinutes($i*15);
                }
                for ($i=0; $i < $serviceDuration/15; $i++) { 
                    $overlapDates[] = Carbon::parse($date)->clone()->addMinutes($i*-15);
                }
            }
        }

        $freeDates = array_diff($allDates,$reservedDates,$overlapDates);

        $dates = [];
        foreach ($freeDates as $date) {
            $dayDiff = today()->diffInDays($date);

            if (!isset($dates[$dayDiff])) {
                $dates[$dayDiff] = [];
            }

            $dates[$dayDiff][] = $date;
        }

        return view('my-appointment.create_date',[
            'dates' => $dates,
            'barber' => Barber::where('id','=',$request->barber_id)->with('user')->firstOrFail(),
            'service' => Service::where('id','=',$request->service_id)->firstOrFail()
        ]);
    }

    public function store(Request $request)
    {
        //double check az appointmentre
        $request->validate([
            'date' => 'required|date|after_or_equal:now|date_format:Y-m-d G:i',
            'barber' =>'required',
            'service' => 'required'
        ]);

        $app_start_time = Carbon::parse($request->date);
        $duration = Service::findOrFail($request->service)->duration;

        Appointment::create([
            'user_id' => auth()->user()->id,
            'barber_id' => $request->barber,
            'service_id' => $request->service,
            'app_start_time' => $app_start_time,
            'app_end_time' => $app_start_time->addMinutes($duration),
            'price' => Service::findOrFail($request->service)->price,
            'comment' => $request->comment,

        ]);
    }

    public function show(string $id)
    {
        // csak a sajátot tudja megnézni
    }

    public function edit(string $id)
    {
        // szerintem ez nem kell
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // ez se
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // ez még kéne
    }
}