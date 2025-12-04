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
use Illuminate\Support\Facades\Gate;
use App\Notifications\BookingUpdateNotification;
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
                return redirect()->back()->with('error',__('admin.error_end_start_time'));
            }
        }


        $appointments = Appointment::withoutTimeOffs()
            ->when(true, function ($q) use ($request) {
                switch ($request->cancelled ?? 1) {
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
            ->when($request->from_app_start_date || $request->to_app_start_date || ($request->time_window ?? 'custom'), function ($q) use ($request, $fromAppStartTime, $toAppStartTime) {
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

                        $q->orderByDesc('id');
                }
                
            })
            ->orderBy('app_start_time')->paginate(10);

        $barbers = Barber::withTrashed()->get();
        $services = Service::withTrashed()->withoutTimeoff()->get();
        $users = User::withTrashed()->hasStoredEmail()->orderBy('first_name')->get();
     
        return view('admin.appointment.index',[
            'appointments' => $appointments,
            'barbers' => $barbers,
            'services' => $services,
            'users' => $users
        ]);
    }

    public function create(Request $request)
    {
        return redirect()->route('bookings.create.barber.service');
    }

    public function createBarberService(Request $request)
    {
        $request->validate([
            'service_id' => 'nullable|integer|gt:1|exists:services,id',
            'barber_id' => 'nullable|integer|exists:barbers,id'
        ]);

        $selectedServiceId = $request->service_id;
        $selectedBarberId = $request->barber_id;

        $barbers = Barber::all();

        $services = Service::withoutTimeoff()->get();

        return view('my-appointment.create_barber_service',[
            'barbers' => $barbers,
            'services' => $services,
            'service_id' => $selectedServiceId,
            'barber_id' => $selectedBarberId,
            'view' => 'admin'
        ]);
    }

    public function createGetEarliestBarber(Request $request)
    {
        $request->validate([
            'service_id' => 'required|integer|gt:1|exists:services,id',
            'barber_id' => 'required'
        ]);

        if ($request->barber_id == 'earliest') {
            $service = Service::find($request->service_id);
            $barbers = Barber::all();

            $earliestTimeslot = '';
            $earliestBarberId = '';

            foreach ($barbers as $barber) {
                $freeSlots = Appointment::getFreeTimeSlots($barber,$service,3);

                $earliestTimeslotOfBarber = Carbon::parse(array_key_first($freeSlots) . ' ' . $freeSlots[array_key_first($freeSlots)][0]);
                
                if ($earliestTimeslot == '' || $earliestTimeslotOfBarber < $earliestTimeslot) {
                    $earliestTimeslot = $earliestTimeslotOfBarber;
                    $earliestBarberId = $barber->id;
                }
            }

        } else {
            $request->validate(['barber_id' => 'required|integer|exists:barbers,id']);
            $earliestBarberId = $request->barber_id;
        }

        return redirect()->route('bookings.create.date',[
            'barber_id' => $earliestBarberId,
            'service_id' => $request->service_id
        ]);
    }

    public function createDate(Request $request)
    {
        $request->validate([
            'service_id' => 'required|integer|gt:1|exists:services,id|gt:1',
            'barber_id' => 'required|integer|exists:barbers,id'
        ]);

        $barber = Barber::find($request->barber_id);
        $service = Service::find($request->service_id);

        $availableSlotsByDate = Appointment::getFreeTimeSlots($barber,$service);

        return view('my-appointment.create_date',[
            'availableSlotsByDate' => $availableSlotsByDate,
            'barber' => $barber,
            'service' => $service,
            'view' => 'admin'
        ]);
    }

    public function createCustomer(Request $request) {
        $request->validate([
            'query' => 'nullable|string|max:255',
            'service_id' => ['required','integer','exists:services,id','gt:1'],
            'barber_id' => 'required|integer|exists:barbers,id',
            'date' => ['required','date','after_or_equal:now','date_format:Y-m-d G:i',new ValidAppointmentTime],
            'comment' => ['nullable','string']
        ]);
        
        $query = $request->input('query');
        $barber = Barber::find($request->barber_id);

        $users = User::registered()->where('id','!=',$barber->user_id)
        ->when($query, function ($q) use ($query) {
            $q->where('first_name','like',"%$query%")
            ->orWhere('last_name','like',"%$query%")
            ->orWhere('email','like',"%$query%")
            ->orWhere('tel_number','like',"%$query%");
        })->orderBy('first_name')->paginate(10);

        return view('appointment.create',[
            'users' => $users,
            'service' => Service::find($request->service_id),
            'barber' => $barber,
            'view' => 'admin'
        ]);
    }

    public function createConfirm(Request $request)  {
        $request->validate([
            'date' => ['required','date','after_or_equal:now','date_format:Y-m-d G:i',new ValidAppointmentTime],
            'user_id' => ['nullable','integer','exists:users,id'],
            'barber_id' => ['required','integer','exists:barbers,id'],
            'service_id' => ['required','integer','exists:services,id','gt:1'],
            'comment' => ['nullable','string']
        ]);

        $startTime = Carbon::parse($request->date);

        $barber = Barber::find($request->barber_id);
        
        $service = Service::find($request->service_id);
        $comment = $request->comment;

        $data = [
            'barber' => Barber::find($request->barber_id),
            'service' => Service::find($request->service_id),
            'startTime' => $startTime,
            'comment' => $request->comment,
            'view' => 'admin'
        ];

        if ($request->has('user_id')) {
            $user = User::find($request->user_id);

            if ($barber->user_id == $user->id) {
                return redirect()->route('bookings.create.customer',['service_id' => $service->id, 'comment' => $comment, 'date' => $startTime->format('Y-m-d G:i'), 'barber_id' => $barber->id]);
            }

            $data['user'] = $user;
        }

        return view('my-appointment.create_confirm', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => ['required','date','after_or_equal:now','date_format:Y-m-d G:i:s',new ValidAppointmentTime],
            'user_id' => ['nullable','integer','exists:users,id'],
            'service_id' => ['required','integer','gt:1','exists:services,id','gt:1'],
            'barber_id' => ['required','integer','exists:barbers,id'],
            'comment' => ['nullable','string'],
            'first_name' => ['nullable','string','min:1'],
            'email' => ['nullable','email','min:1'],
            'policy_checkbox' => ['required'],
            'confirmation_checkbox' => ['required']
        ]);

        // HANDLING AUTHLESS CASES
        if ($request->has(['first_name']) && !request()->has('user_id')) {
            $email = $request->email;
            $firstName = $request->first_name;

            if ($email) {
                $users = User::whereEmail($email)->get();

                if ($users->count() == 1) {
                    $user = $users->first();

                    if (!isset($user->last_name)) {
                        if ($firstName != $user->first_name) {
                            $user->update([
                                'first_name' => $firstName
                            ]);
                        }
                    } else {
                        return redirect()->back()->with('error',__('barber.error_email_1') . $email . __('barber.error_email_2'));
                    }
                    
                } else {
                    $user = User::create([
                        'first_name' => $request->first_name,
                        'email' => $email,
                        'is_admin' => false,
                        'lang_pref' => 'en',
                        'subbed_to_mailing_list' => false
                    ]);
                }
            } else {
                $user = User::create([
                    'first_name' => $request->first_name,
                    'is_admin' => false,
                    'lang_pref' => 'en',
                    'subbed_to_mailing_list' => false
                ]);
            }
        } else {
            $user = User::find($request->user_id);
        }

        $barber = Barber::find($request->barber_id);
        $service = Service::find($request->service_id);
        $comment = $request->comment;

        $app_start_time = Carbon::parse($request->date);
        $duration = $service->duration;
        $app_end_time = $app_start_time->clone()->addMinutes($duration);

        if ($barber->user_id == $user->id) {
            return redirect()->route('bookings.create.customer',['service_id' => $service->id, 'comment' => $comment, 'date' => $app_start_time->format('Y-m-d G:i'), 'barber_id' => $barber->id])->with('error',__('admin.error_customer_barber'));
        }

        if (!Appointment::checkAppointmentClashes($app_start_time,$app_end_time,$barber)) {
            return redirect()->route('bookings.create.date',['service_id' => $service->id, 'barber_id' => $barber->id, 'comment' => $comment, 'date' => $app_start_time->format('Y-m-d G:i')])->with('error',__('admin.error_barber_clashing'));
        }

        $appointment = Appointment::create([
            'user_id' => $user->id,
            'barber_id' => $barber->id,
            'service_id' => $service->id,
            'app_start_time' => $app_start_time,
            'app_end_time' => $app_end_time,
            'price' => $service->price,
            'comment' => $comment
        ]);

        if ($user->email) {
            $appointment->user->notify(
                new BookingConfirmationNotification($appointment)
            );
        }

        return redirect()->route('bookings.show',['booking' =>  $appointment])->with('success',__('barber.success_new_booking'));
    }

    public function show(Appointment $booking)
    {
        if (Gate::allows('isTimeOff',$booking)) {
            return redirect()->route('admin-time-offs.show',$booking);
        }

        $upcoming = Appointment::userFilter($booking->user)->upcoming()->count();
        $previous = Appointment::userFilter($booking->user)->previous()->count();
        $cancelled = Appointment::onlyTrashed()->userFilter($booking->user)->count();

        $barber = Appointment::where('barber_id', '!=',$booking->user?->barber?->id)->select('barber_id',DB::raw('COUNT(barber_id) as selection_count'))->userFilter($booking->user)->groupBy('barber_id')->orderByDesc('selection_count')->first();

        $favBarber = Barber::withTrashed()->find($barber->barber_id);
        $numBarber = $barber->selection_count;

        $service = Appointment::withoutTimeOffs()->select('service_id',DB::raw('COUNT(service_id) as selection_count'))->userFilter($booking->user)->groupBy('service_id')->orderByDesc('selection_count')->first();

        $favService = Service::withTrashed()->find($service->service_id);
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
        $response = Gate::inspect('adminUpdate',$booking);
        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        if (Gate::allows('isTimeOff',$booking)) {
            return redirect()->route('admin-time-offs.edit',$booking);
        }

        $services = Service::withoutTimeoff()->get();
        $barbers = Barber::all();
        $appointments = Appointment::with('user')->get();

        return view('appointment.edit', [
            'appointment' => $booking,
            'services' => $services,
            'barbers' => $barbers,
            'appointments' => $appointments,
            'access' => 'admin',
            'view' => 'Booking'
        ]);
    }

    public function update(Request $request, Appointment $booking)
    {
        $response = Gate::inspect('adminUpdate',$booking);
        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        if (Gate::allows('isTimeOff',$booking)) {
            return redirect()->route('admin-time-offs.edit',$booking)->with('error',__('admin.error_edit_timeoff_booking'));
        }

        $request->validate([
            'service' => 'required|integer|exists:services,id|gt:1',
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
            return redirect()->route('bookings.edit',$booking)->with('error',__('barber.error_ending_time'));
        }

        if (!Appointment::checkAppointmentClashes($app_start_time,$app_end_time,$barber,$booking)) {
            return redirect()->route('bookings.edit',$booking)->with('error',__('admin.error_barber_clashing'));
        }

        $oldAppointment = $booking->only([
            'barber_id',
            'service_id',
            'comment',
            'price',
            'app_start_time',
            'app_end_time'
        ]);

        $booking->update([
            'service_id' => $request->service,
            'barber_id' => $request->barber,
            'comment' => $request->comment,
            'price' => $request->price,
            'app_start_time' => $app_start_time,
            'app_end_time' => $app_end_time
        ]);

        if ($booking->user->email) {
            $booking->user->notify(
                new BookingUpdateNotification($oldAppointment,$booking,'admin')
            );
        }

        return redirect()->route('bookings.show',$booking)->with('success',__('barber.success_updated_booking'));
    }

    public function destroy(Appointment $booking)
    {
        $response = Gate::inspect('adminUpdate',$booking);
        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        if (Gate::allows('isTimeOff',$booking)) {
            return redirect()->route('admin-time-offs.show',$booking)->with('error',__('admin.error_destroy_timeoff_booking'));
        }        

        $booking->user->notify(
            new BookingCancellationNotification($booking,'Admin')
        );
        $booking->barber->user->notify(
            new BookingCancellationNotification($booking,'admin')
        );

        $booking->delete();

        return redirect()->route('bookings.show',$booking)
            ->with('success',__('barber.success_booking_destroyed'));
    }
}
