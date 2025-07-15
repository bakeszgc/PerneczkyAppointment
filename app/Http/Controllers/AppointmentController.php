<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Barber;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Rules\ValidAppointmentTime;
use App\Notifications\BookingCancellationNotification;
use App\Notifications\BookingConfirmationNotification;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::barberFilter(auth()->user()->barber)->withoutTimeOffs()->withTrashed()->latest()->paginate(10);

        $calAppointments = Appointment::barberFilter(auth()->user()->barber)->whereBetween('app_start_time',[date("Y-m-d", strtotime('monday this week')),date("Y-m-d", strtotime('monday next week'))])->get();
     
        return view('appointment.index',[
            'appointments' => $appointments,
            'calAppointments' => $calAppointments,
            'type' => 'All'
        ]);
    }

    public function indexUpcoming() {
        $upcomingAppointments = Appointment::upcoming()->barberFilter(auth()->user()->barber)
        ->withoutTimeOffs()->paginate(10);
        
        return view('appointment.index',[
            'appointments' => $upcomingAppointments,
            'type' => 'Upcoming'
        ]);
    }

    public function indexPrevious() {
        $previousAppointments = Appointment::previous()->barberFilter(auth()->user()->barber)
        ->withoutTimeOffs()->paginate(10);
        
        return view('appointment.index',[
            'appointments' => $previousAppointments,
            'type' => 'Previous'
        ]);
    }

    public function indexCancelled() {
        $cancelledAppointments = Appointment::onlyTrashed()->barberFilter(auth()->user()->barber)
        ->withoutTimeOffs()->orderBy('app_start_time','desc')->paginate(10);

        return view('appointment.index',[
            'appointments' => $cancelledAppointments,
            'type' => 'Cancelled'
        ]);
    }

    public function create(Request $request)
    {
        $query = $request->input('query');

        $users = User::where('id','!=',auth()->user()->id)
        ->when($query, function ($q) use ($query) {
            $q->where('first_name','like',"%$query%")
            ->orWhere('last_name','like',"%$query%")
            ->orWhere('email','like',"%$query%")
            ->orWhere('tel_number','like',"%$query%");
        })->paginate(10);

        return view('appointment.create',['users' => $users]);
    }

    public function createService(Request $request)
    {
        // redirect ha nincs a user_id az adatbázisban vagy pont a barber a user_id
        if (!User::find($request->user_id) || $request->user_id === auth()->user()->id) {
            return redirect()->route('appointments.create')->with('error','Please select a valid user from the list!');
        }

        $services = Service::where('is_visible','=',1)->get();
        return view('appointment.create_service',['services' => $services]);
    }

    public function createDate(Request $request)
    {
        // redirect ha nincs a user_id az adatbázisban vagy pont a barber a user_id
        if (!User::find($request->user_id) || $request->user_id === auth()->user()->id) {
            return redirect()->route('appointments.create')->with('error','Please select a valid user from the list!');
        } 
        // redirect ha nincs a service_id az adatbázisban vagy a timeoffot választotta
        if (!Service::find($request->service_id) || $request->service_id == 1) {
            return redirect()->route('appointments.create.service',['user_id' => $request->user_id])->with('error','Please select a valid service from the list');
        }

        $barber = auth()->user()->barber;

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

        return view('appointment.create_date',[
            'dates' => $dates,
            'user' => User::findOrFail($request->user_id),
            'barber' => $barber,
            'service' => Service::findOrFail($request->service_id)
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => ['required','date','after_or_equal:now','date_format:Y-m-d G:i',new ValidAppointmentTime],
            'user_id' => ['required','exists:users,id'],
            'service_id' => ['required','exists:services,id'],
            'comment' => ['nullable','string']
        ]);

        $app_start_time = Carbon::parse($request->date);
        $duration = Service::findOrFail($request->service_id)->duration;
        $app_end_time = $app_start_time->clone()->addMinutes($duration);
        $barber = auth()->user()->barber;

        // foglalások amik az új foglalás alatt kezdődnek
        $appointmentsStart = Appointment::barberFilter($barber)
        ->startLaterThan($app_start_time)
        ->startEarlierThan($app_end_time,false)->get();

        // foglalások amik az új foglalás alatt végződnek
        $appointmentsEnd = Appointment::barberFilter($barber)
        ->endLaterThan($app_start_time,false)
        ->endEarlierThan($app_end_time,)->get();

        // foglalások amik az új foglalás előtt kezdődnek de utána végződnek
        $appointmentsBetween = Appointment::barberFilter($barber)
        ->startEarlierThan($app_start_time)
        ->endLaterThan($app_end_time)->get();

        if ($appointmentsStart->count() + $appointmentsEnd->count() + $appointmentsBetween->count() != 0) {
            return redirect()->route('appointments.create.date',['user_id' => $request->user_id, 'service_id' => $request->service_id])->with('error','You have another bookings clashing with the selected timeslot. Please choose another one!');
        }

        $appointment = Appointment::create([
            'user_id' => $request->user_id,
            'barber_id' => auth()->user()->barber->id,
            'service_id' => $request->service_id,
            'app_start_time' => $app_start_time,
            'app_end_time' => $app_end_time,
            'price' => Service::findOrFail($request->service_id)->price,
            'comment' => $request->comment,
        ]);

        $appointment->user->notify(
            new BookingConfirmationNotification($appointment)
        );

        return redirect()->route('appointments.show',['appointment' =>  $appointment])->with('success','Appointment booked successfully! See you soon!');
    }
    
    public function show(Appointment $appointment)
    {
        if ($appointment->barber->id !== auth()->user()->barber->id) {
            return redirect()->route('appointments.index')->with('error',"You can't view other barbers' bookings.");
        }

        $upcoming = Appointment::userFilter($appointment->user)->upcoming()->count();
        $previous = Appointment::userFilter($appointment->user)->previous()->count();
        $cancelled = Appointment::onlyTrashed()->userFilter($appointment->user)->count();

        $barber = Appointment::select('barber_id',DB::raw('COUNT(barber_id) as selection_count'))->userFilter($appointment->user)->groupBy('barber_id')->orderByDesc('selection_count')->first();

        $favBarber = Barber::find($barber->barber_id);
        $numBarber = $barber->selection_count;

        $service = Appointment::select('service_id',DB::raw('COUNT(service_id) as selection_count'))->userFilter($appointment->user)->groupBy('service_id')->orderByDesc('selection_count')->first();

        $favService = Service::find($service->service_id);
        $numService = $service->selection_count;

        return view('appointment.show',[
            'appointment' => $appointment,
            'upcoming' => $upcoming,
            'previous' => $previous,
            'cancelled' => $cancelled,
            'favBarber' => $favBarber,
            'numBarber' => $numBarber,
            'favService' => $favService,
            'numService' => $numService
        ]);
    }
    
    public function edit(Appointment $appointment)
    {
        if ($appointment->barber->id != auth()->user()->barber->id) {
            return redirect()->route('appointments.index')->with('error',"You can't edit other barbers' bookings.");
        } elseif ($appointment->app_start_time <= now()) {
            return redirect()->route('appointments.show',$appointment)->with('error',"You can't edit bookings from the past.");
        } elseif ($appointment->deleted_at) {
            return redirect()->route('appointments.show',$appointment)->with('error',"You can't edit cancelled bookings.");
        }

        $previousAppointment = Appointment::barberFilter(auth()->user()->barber)->endEarlierThan($appointment->app_start_time)->orderByDesc('app_end_time')->first();

        $nextAppointment = Appointment::barberFilter(auth()->user()->barber)->startLaterThan($appointment->app_end_time)->orderBy('app_start_time')->first();

        $services = Service::where('is_visible','=',1)->get();

        return view('appointment.edit', [
            'appointment' => $appointment,
            'services' => $services,
            'previous' => $previousAppointment,
            'next' => $nextAppointment
        ]);
    }
    
    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'service' => 'required',
            'price' => 'required|integer|min:0',
            'app_start_date' => ['required','date','after_or_equal:today'],
            'app_start_hour' => ['required','integer','between:10,19'],
            'app_start_minute' => 'required|integer|multiple_of:15',
            'app_end_date' => ['required','date','after_or_equal:app_start_date'],
            'app_end_hour' => ['required','between:10,21','integer','gte:app_start_hour'],
            'app_end_minute' => 'required|integer|multiple_of:15',
            'comment' => 'nullable|max:255',
        ]);

        $app_start_time = Carbon::parse($request->app_start_date . " " . $request->app_start_hour . ":" . $request->app_start_minute);
        $app_end_time = Carbon::parse($request->app_end_date . " " . $request->app_end_hour . ":" . $request->app_end_minute);
        $barber = auth()->user()->barber;

        if ($app_start_time >= $app_end_time) {
            return redirect()->route('appointments.edit',$appointment)->with('error',"The booking's ending time has to be later than its starting time");
        }

        // foglalások amik az új foglalás alatt kezdődnek
        $appointmentsStart = Appointment::barberFilter($barber)
        ->startLaterThan($app_start_time)
        ->startEarlierThan($app_end_time,false)
        ->where('id','!=',$appointment->id)->get();

        // foglalások amik az új foglalás alatt végződnek
        $appointmentsEnd = Appointment::barberFilter($barber)
        ->endLaterThan($app_start_time,false)
        ->endEarlierThan($app_end_time)
        ->where('id','!=',$appointment->id)->get();

        // foglalások amik az új foglalás előtt kezdődnek de utána végződnek
        $appointmentsBetween = Appointment::barberFilter($barber)
        ->startEarlierThan($app_start_time)
        ->endLaterThan($app_end_time)
        ->where('id','!=',$appointment->id)->get();

        if ($appointmentsStart->count() + $appointmentsEnd->count() + $appointmentsBetween->count() != 0) {
            return redirect()->route('appointments.edit',$appointment)->with('error','You have another bookings clashing with the selected timeslot. Please choose another one!');
        }

        $appointment->update([
            'service_id' => $request->service,
            'comment' => $request->comment,
            'price' => $request->price,
            'app_start_time' => $app_start_time,
            'app_end_time' => $app_end_time
        ]);

        return redirect()->route('appointments.show',['appointment' => $appointment])->with('success','Booking has been updated successfully!');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->user->notify(
            new BookingCancellationNotification($appointment,'barber')
        );
        $appointment->delete();
        return redirect()->route('appointments.index')
            ->with('success','Appointment cancelled successfully! Be sure to set up a new booking with your client!');
    }
}
