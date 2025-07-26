<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Barber;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\BookingCancellationNotification;

class AdminAppointmentController extends Controller
{
    public function index(Request $request) {
        $request->validate([
            'barber' => 'integer|exists:barbers,id',
            'service' => 'integer|exists:services,id',
            'user' => 'integer|exists:users,id',
            'from_app_start_date' => 'date',
            'from_app_start_hour' => 'int|between:10,20',
            'from_app_start_minute' => 'int|between:0,45|multiple_of:15',
            'to_app_start_date' => ['date', function ($attribute, $value, $fail) use ($request) {
                if ($request->filled('from_app_start_date') && $value < $request->input('from_app_start_date')) {
                    $fail('The end date must be after or equal to the start date.');
                }
            }],
            'to_app_start_hour' => 'int|between:10,20',
            'to_app_start_minute' => 'int|between:0,45|multiple_of:15'
        ]);

        $fromAppStartTime = null;
        $toAppStartTime = null;

        if ($request->has('from_app_start_date')) {
            $fromAppStartTime = new Carbon($request->from_app_start_date . ' ' . ($request->from_app_start_hour ?? '10') . ':' . ($request->from_app_start_minute ?? '00'));
        }

        if ($request->has('to_app_start_date')) {
            $toAppStartTime = new Carbon($request->to_app_start_date . ' ' . ($request->to_app_start_hour ?? '10') . ':' . ($request->to_app_start_minute ?? '00'));
        }

        if ($fromAppStartTime != null && $toAppStartTime != null) {
            if ($toAppStartTime < $fromAppStartTime) {
                return redirect()->back()->with('error','The end time must be after or equal to the start date!');
            }
        }


        $appointments = Appointment::withoutTimeOffs()
            ->when($request->cancelled, function ($q) use ($request) {
                switch ($request->cancelled) {
                    case 1:
                        $q->withTrashed();
                        break;
                    case 2:
                        $q->onlyTrashed();
                        break;
                    default:
                        $q;
                        break;
                }
            })
            ->when($request->barber, function ($q) use ($request) {
                $barber = Barber::find($request->barber);
                $q->barberFilter($barber);
            })
            ->when($request->service, function ($q) use ($request) {
                $service = Service::find($request->service);
                $q->serviceFilter($service);
            })
            ->when($request->user, function ($q) use ($request) {
                $user = User::find($request->user);
                $q->userFilter($user);
            })
            ->when($request->from_app_start_date || $request->to_app_start_date || $request->time_window, function ($q) use ($request, $fromAppStartTime, $toAppStartTime) {
                switch ($request->time_window) {
                    case 'upcoming':
                        $q->upcoming();
                        break;
                    case 'previous':
                        $q->previous();
                        break;
                    default:
                        if ($request->from_app_start_date) {
                            
                            
                            $q->startLaterThan($fromAppStartTime);
                        }
                        
                        if ($request->to_app_start_date) {
                            $toAppStartTime = new Carbon($request->to_app_start_date . ' ' . ($request->to_app_start_hour ?? '20') . ':' . ($request->to_app_start_minute ?? '00'));
                            
                            $q->startEarlierThan($toAppStartTime);
                        }
                }
                
            })
            ->orderBy('app_start_time')->paginate(10);

        $barbers = Barber::where('is_visible','=',1)->get();
        $services = Service::where('is_visible','=',1)->get();
        $users = User::orderBy('first_name')->get();
     
        return view('admin.appointment.index',[
            'appointments' => $appointments,
            'barbers' => $barbers,
            'services' => $services,
            'users' => $users
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Appointment $booking)
    {
        $upcoming = Appointment::userFilter($booking->user)->upcoming()->count();
        $previous = Appointment::userFilter($booking->user)->previous()->count();
        $cancelled = Appointment::onlyTrashed()->userFilter($booking->user)->count();

        $barber = Appointment::select('barber_id',DB::raw('COUNT(barber_id) as selection_count'))->userFilter($booking->user)->groupBy('barber_id')->orderByDesc('selection_count')->first();

        $favBarber = Barber::find($barber->barber_id);
        $numBarber = $barber->selection_count;

        $service = Appointment::select('service_id',DB::raw('COUNT(service_id) as selection_count'))->userFilter($booking->user)->groupBy('service_id')->orderByDesc('selection_count')->first();

        $favService = Service::find($service->service_id);
        $numService = $service->selection_count;

        return view('appointment.show',[
            'appointment' => $booking,
            'upcoming' => $upcoming,
            'previous' => $previous,
            'cancelled' => $cancelled,
            'favBarber' => $favBarber,
            'numBarber' => $numBarber,
            'favService' => $favService,
            'numService' => $numService,
            'view' => 'admin'
        ]);
    }

    public function edit(Appointment $booking)
    {
        if ($booking->app_start_time <= now()) {
            return redirect()->route('bookings.show',$booking)->with('error',"You can't edit bookings from the past.");
        } elseif ($booking->deleted_at) {
            return redirect()->route('bookings.show',$booking)->with('error',"You can't edit cancelled bookings.");
        }

        $previousAppointment = Appointment::barberFilter($booking->barber)->endEarlierThan(Carbon::parse($booking->app_start_time))->orderByDesc('app_end_time')->first();

        $nextAppointment = Appointment::barberFilter($booking->barber)->startLaterThan(Carbon::parse($booking->app_end_time))->orderBy('app_start_time')->first();

        $services = Service::where('is_visible','=',1)->get();
        $barbers = Barber::all();

        return view('appointment.edit', [
            'appointment' => $booking,
            'services' => $services,
            'barbers' => $barbers,
            'previous' => $previousAppointment,
            'next' => $nextAppointment,
            'view' => 'admin'
        ]);
    }

    public function update(Request $request, Appointment $booking)
    {
        $request->validate([
            'service' => 'required|integer|exists:services,id',
            'barber' => 'required|integer|exists:barbers,id',
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

        $barber = Barber::find($request->barber);

        if ($app_start_time >= $app_end_time) {
            return redirect()->route('appointments.edit',$booking)->with('error',"The booking's ending time has to be later than its starting time");
        }

        // foglalások amik az új foglalás alatt kezdődnek
        $appointmentsStart = Appointment::barberFilter($barber)
        ->startLaterThan($app_start_time)
        ->startEarlierThan($app_end_time,false)
        ->where('id','!=',$booking->id)->get();

        // foglalások amik az új foglalás alatt végződnek
        $appointmentsEnd = Appointment::barberFilter($barber)
        ->endLaterThan($app_start_time,false)
        ->endEarlierThan($app_end_time)
        ->where('id','!=',$booking->id)->get();

        // foglalások amik az új foglalás előtt kezdődnek de utána végződnek
        $appointmentsBetween = Appointment::barberFilter($barber)
        ->startEarlierThan($app_start_time)
        ->endLaterThan($app_end_time)
        ->where('id','!=',$booking->id)->get();

        if ($appointmentsStart->count() + $appointmentsEnd->count() + $appointmentsBetween->count() != 0) {
            return redirect()->route('appointments.edit',$booking)->with('error','You have another bookings clashing with the selected timeslot. Please choose another one!');
        }

        $booking->update([
            'service_id' => $request->service,
            'barber_id' => $request->barber,
            'comment' => $request->comment,
            'price' => $request->price,
            'app_start_time' => $app_start_time,
            'app_end_time' => $app_end_time
        ]);

        return redirect()->route('bookings.show',$booking)->with('success','Booking has been updated successfully!');
    }

    public function destroy(Appointment $booking)
    {
        if ($booking->app_start_time > now()) {
            return redirect()->back()->with('error',"You can't cancel a previous booking!");
        }

        $booking->user->notify(
            new BookingCancellationNotification($booking,'barber')
        );

        $booking->delete();

        return redirect()->route('bookings.show',$booking)
            ->with('success','Booking cancelled successfully! Be sure to set up a new booking with your client!');
    }
}
