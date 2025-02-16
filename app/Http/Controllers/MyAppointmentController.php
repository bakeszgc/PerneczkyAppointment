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
            ['app_start_time','>=',now('Europe/Budapest')]
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

        if (!Barber::find($request->barber_id)) {
            return redirect()->route('my-appointments.create.barber');
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

        if (!Barber::find($request->barber_id)) {
            return redirect()->route('my-appointments.create.barber');
        }

        if (!Service::find($request->service_id)) {
            return redirect()->route('my-appointments.create.service',['barber_id' => $request->barber_id]);
        }

        $allDates = [];
        for ($d=0; $d < 14; $d++) {
            for ($h=10; $h < Appointment::closingHour(today()->addDays($d)); $h++) { 
                for ($m=0; $m < 60; $m+=15) {

                    $time = today()->addDays($d)->addHours($h)->addMinutes($m);
                    if ($time >= now('Europe/Budapest')) {
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
        $request->validate([
            'date' => 'required|date|after_or_equal:now|date_format:Y-m-d G:i',
            'barber_id' =>'required',
            'service_id' => 'required'
        ]);

        //double check az appointmentre

        $app_start_time = Carbon::parse($request->date);
        $duration = Service::findOrFail($request->service_id)->duration;
        $app_end_time = $app_start_time->clone()->addMinutes($duration);

        Appointment::create([
            'user_id' => auth()->user()->id,
            'barber_id' => $request->barber_id,
            'service_id' => $request->service_id,
            'app_start_time' => $app_start_time,
            'app_end_time' => $app_end_time,
            'price' => Service::findOrFail($request->service_id)->price,
            'comment' => $request->comment,
        ]);

        //redirect show
    }

    public function show(Appointment $my_appointment)
    {
        if ($my_appointment->user->id != auth()->user()->id) {
            abort(403);
        }
        return view('my-appointment.show',[
            'appointment' => $my_appointment
        ]);
    }

    public function destroy(Appointment $my_appointment)
    {
        $my_appointment->delete();
        return redirect()->route('my-appointments.index')
            ->with('success','Appointment deleted successfully! Don\'t forget to book another one instead!');
    }
}