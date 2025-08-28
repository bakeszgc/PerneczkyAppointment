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
                $barber = Barber::withTrashed()->find($request->barber);
                $q->barberFilter($barber);
            })
            ->when($request->service, function ($q) use ($request) {
                $service = Service::withTrashed()->find($request->service);
                $q->serviceFilter($service);
            })
            ->when($request->user, function ($q) use ($request) {
                $user = User::withTrashed()->find($request->user);
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

        $barbers = Barber::withTrashed()->get();
        $services = Service::withTrashed()->get();
        $users = User::withTrashed()->orderBy('first_name')->get();
     
        return view('admin.appointment.index',[
            'appointments' => $appointments,
            'barbers' => $barbers,
            'services' => $services,
            'users' => $users
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string|max:255'
        ]);

        $query = $request->input('query');

        $users = User::when($query, function ($q) use ($query) {
            $q->where('first_name','like',"%$query%")
            ->orWhere('last_name','like',"%$query%")
            ->orWhere('email','like',"%$query%")
            ->orWhere('tel_number','like',"%$query%");
        })->orderBy('first_name')->paginate(10);

        return view('appointment.create',[
            'users' => $users,
            'view' => 'admin'
        ]);
    }

    public function createBarberService(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'service_id' => 'nullable|integer|gt:1|exists:services,id',
            'barber_id' => 'nullable|integer|exists:barbers,id'
        ]);

        $selectedServiceId = $request->service_id;
        $selectedBarberId = $request->barber_id;

        $barbers = Barber::all();

        $services = Service::where('id','!=',1)->get();

        return view('my-appointment.create_barber_service',[
            'barbers' => $barbers,
            'services' => $services,
            'service_id' => $selectedServiceId,
            'barber_id' => $selectedBarberId,
            'view' => 'admin'
        ]);
    }

    public function createDate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'service_id' => 'required|integer|gt:1|exists:services,id',
            'barber_id' => 'required|integer|exists:barbers,id'
        ]);

        $barber = Barber::find($request->barber_id);
        $service = Service::find($request->service_id);
        $user = User::find($request->user_id);

        $availableSlotsByDate = Appointment::getFreeTimeSlots($barber,$service);

        return view('my-appointment.create_date',[
            'availableSlotsByDate' => $availableSlotsByDate,
            'barber' => $barber,
            'service' => $service,
            'user' => $user,
            'view' => 'admin'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => ['required','date','after_or_equal:now','date_format:Y-m-d G:i',new ValidAppointmentTime],
            'user_id' => ['required','integer','exists:users,id'],
            'service_id' => ['required','integer','gt:1','exists:services,id'],
            'barber_id' => ['required','integer','exists:barbers,id'],
            'comment' => ['nullable','string']
        ]);

        $barber = Barber::find($request->barber_id);
        $user = User::find($request->user_id);
        $service = Service::find($request->service_id);

        $app_start_time = Carbon::parse($request->date);
        $duration = $service->duration;
        $app_end_time = $app_start_time->clone()->addMinutes($duration);

        if (!Appointment::checkAppointmentClashes($app_start_time,$app_end_time,$barber)) {
            return redirect()->route('bookings.create.date',['user_id' => $user->id, 'service_id' => $service->id, 'barber_id' => $barber->id])->with('error','You have another bookings clashing with the selected timeslot. Please choose another one!');
        }

        $appointment = Appointment::create([
            'user_id' => $user->id,
            'barber_id' => $barber->id,
            'service_id' => $service->id,
            'app_start_time' => $app_start_time,
            'app_end_time' => $app_end_time,
            'price' => $service->price,
            'comment' => $request->comment,
        ]);

        $appointment->user->notify(
            new BookingConfirmationNotification($appointment)
        );

        return redirect()->route('bookings.show',['booking' =>  $appointment])->with('success','New booking has been created successfully!');
    }

    public function show(Appointment $booking)
    {
        if ($booking->service_id == 1) {
            return redirect()->route('admin-time-offs.show',$booking);
        }

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
        } elseif ($booking->service_id == 1) {
            return redirect()->route('admin-time-offs.edit',$booking);
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

        if ($booking->app_start_time <= now()) {
            return redirect()->route('bookings.show',$booking)->with('error',"You can't edit bookings from the past!");
        } elseif ($booking->deleted_at) {
            return redirect()->route('bookings.show',$booking)->with('error',"You can't edit cancelled bookings!");
        } elseif ($booking->service_id == 1) {
            return redirect()->route('admin-time-offs.edit',$booking)->with('error',"You can't edit a time off as a booking. Please try again here!");
        }

        $app_start_time = Carbon::parse($request->app_start_date . " " . $request->app_start_hour . ":" . $request->app_start_minute);
        $app_end_time = Carbon::parse($request->app_end_date . " " . $request->app_end_hour . ":" . $request->app_end_minute);

        $barber = Barber::find($request->barber);

        if ($app_start_time >= $app_end_time) {
            return redirect()->route('appointments.edit',$booking)->with('error',"The booking's ending time has to be later than its starting time");
        }

        if (!Appointment::checkAppointmentClashes($app_start_time,$app_end_time,$barber,$booking)) {
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
        if ($booking->app_start_time < now()) {
            return redirect()->back()->with('error',"You can't cancel a previous booking!");
        } elseif ($booking->deleted_at) {
            return redirect()->route('bookings.show',$booking)->with('error',"You can't cancel an already cancelled booking!");
        } elseif ($booking->service_id == 1) {
            return redirect()->route('admin-time-offs.show',$booking)->with('error',"You can't cancel a time off as a booking. Please try again here!");
        }

        $booking->user->notify(
            new BookingCancellationNotification($booking,'barber')
        );

        $booking->delete();

        return redirect()->route('bookings.show',$booking)
            ->with('success','Booking cancelled successfully! Be sure to set up a new booking with your client!');
    }
}
