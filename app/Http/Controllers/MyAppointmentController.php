<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Barber;
use App\Models\Service;
use App\Notifications\BookingCancellationNotification;
use App\Notifications\BookingConfirmationNotification;
use App\Rules\ValidAppointmentTime;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class MyAppointmentController extends Controller
{
    public function index()
    {
        $upcomingAppointments = Appointment::userFilter(auth()->user())->upcoming()
        ->withoutTimeOffs()->paginate(10);
        
        return view('my-appointment.index',[
            'appointments' => $upcomingAppointments,
            'type' => 'Upcoming'
        ]);
    }

    public function indexPrevious()
    {
        $previousAppointments = Appointment::userFilter(auth()->user())->previous()
        ->withoutTimeOffs()->paginate(10);
        
        return view('my-appointment.index',[
            'appointments' => $previousAppointments,
            'type' => 'Previous'
        ]);
    }
    
    public function create()
    {
        if (auth()->user()) {
            return redirect()->route('my-appointments.create.barber.service');
        } else {
            return view('my-appointment.create');
        }
    }

    public function createBarberService(Request $request)
    {
        if (!auth()->user()) {
           return redirect()->route('login');
        }
        
        $selectedServiceId = $request->service_id;
        $selectedBarberId = $request->barber_id;

        $barbers = Barber::when(auth()->user()->barber != null, function($q) {
            return $q->where('id','!=',auth()->user()->barber->id);
        })->where('is_visible','=',1)->get();

        $services = Service::where('is_visible','=',1)->get();

        return view('my-appointment.create_barber_service',[
            'barbers' => $barbers,
            'services' => $services,
            'service_id' => $selectedServiceId,
            'barber_id' => $selectedBarberId
        ]);
    }

    public function createDate(Request $request)
    {
        if (!auth()->user()) {
            return redirect()->route('login');
        }

        if (!Barber::find($request->barber_id) || auth()->user()->barber && $request->barber_id == auth()->user()->barber->id) {
            return redirect()->route('my-appointments.create.barber.service',['service_id' => $request->service_id])->with('error','Please select a barber here!');
        }

        if (!Service::find($request->service_id) || $request->service_id == 1) {
            return redirect()->route('my-appointments.create.barber.service',['barber_id' => $request->barber_id])->with('error','Please select a service here!');
        }

        $barber = Barber::find($request->barber_id);

        // összes lehetséges időpont
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
        $reservedDates = Appointment::barberFilter($barber)->pluck('app_start_time')
        ->map(fn ($time) => Carbon::parse($time))->toArray();

        // timeslotok amikbe belelóg egy másik foglalás
        // timeslotok amik belelógnának egy következő foglalásba
        $overlapDates = [];
        foreach ($reservedDates as $date) {
            $appointments = Appointment::barberFilter($barber)
                ->where('app_start_time','=',$date)->get();
            $service = Service::findOrFail($request->service_id);

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

        $freeDates = array_diff($allDates,$reservedDates,$overlapDates);

        $dates = [];
        foreach ($freeDates as $date) {
            $dayDiff = today()->diffInDays($date);

            if (!isset($dates[$dayDiff])) {
                $dates[$dayDiff] = [];
            }

            $dates[$dayDiff][] = $date;
        }

        // TESTING PURPOSE
        $availableSlotsByDate = [];
        foreach ($freeDates as $date) {
            $actualDate = Carbon::parse($date)->format('Y-m-d');

            if (!isset($availableSlotsByDate[$actualDate])) {
                $availableSlotsByDate[$actualDate] = [];
            }

            $availableSlotsByDate[$actualDate][] = $date->format('G:i');
        }

        return view('my-appointment.create_date',[
            'dates' => $dates,
            'availableSlotsByDate' => $availableSlotsByDate,
            'barber' => Barber::find($request->barber_id),
            'service' => Service::find($request->service_id)
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => ['required','date','after_or_equal:now','date_format:Y-m-d G:i',new ValidAppointmentTime],
            'barber_id' => ['required','exists:barbers,id'],
            'service_id' => ['required','exists:services,id'],
            'comment' => ['nullable','string']
        ]);

        $barber = Barber::find($request->barber_id);

        $app_start_time = Carbon::parse($request->date);
        $duration = Service::findOrFail($request->service_id)->duration;
        $app_end_time = $app_start_time->clone()->addMinutes($duration);

        // foglalások amik az új foglalás alatt kezdődnek
        $appointmentsStart = Appointment::barberFilter($barber)
        ->startLaterThan($app_start_time)
        ->startEarlierThan($app_end_time,false)->get();

        // foglalások amik az új foglalás alatt végződnek
        $appointmentsEnd = Appointment::barberFilter($barber)
        ->endLaterThan($app_start_time,false)
        ->endEarlierThan($app_end_time)->get();

        // foglalások amik az új foglalás előtt kezdődnek de utána végződnek
        $appointmentsBetween = Appointment::barberFilter($barber)
        ->startEarlierThan($app_start_time)
        ->endLaterThan($app_end_time)->get();

        if ($appointmentsStart->count() + $appointmentsEnd->count() + $appointmentsBetween->count() != 0) {
            return redirect()->route('my-appointments.create.date',['barber_id' => $request->barber_id, 'service_id' => $request->service_id])->with('error','Your barber has another bookings clashing with the selected timeslot. Please choose another one!');
        }

        $appointment = Appointment::create([
            'user_id' => auth()->user()->id,
            'barber_id' => $request->barber_id,
            'service_id' => $request->service_id,
            'app_start_time' => $app_start_time,
            'app_end_time' => $app_end_time,
            'price' => Service::findOrFail($request->service_id)->price,
            'comment' => $request->comment,
        ]);

        $appointment->user->notify(
            new BookingConfirmationNotification($appointment)
        );

        return redirect()->route('my-appointments.show',['my_appointment' =>  $appointment])->with('success','Appointment booked successfully! See you soon!');
    }

    public function show(Appointment $my_appointment)
    {
        if ($my_appointment->user->id != auth()->user()->id) {
            return redirect()->route('my-appointments.index');
        }
        return view('my-appointment.show',[
            'appointment' => $my_appointment
        ]);
    }

    public function destroy(Appointment $my_appointment)
    {
        $my_appointment->barber->user->notify(
            new BookingCancellationNotification($my_appointment,'user')
        );
        $my_appointment->delete();
        return redirect()->route('my-appointments.index')
            ->with('success','Appointment cancelled successfully! Don\'t forget to book another one instead!');
    }
}