<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Barber;
use App\Models\Service;
use App\Models\Appointment;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Rules\ValidAppointmentTime;
use App\Notifications\BookingUpdateNotification;
use App\Notifications\BookingCancellationNotification;
use App\Notifications\BookingConfirmationNotification;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::barberFilter(auth()->user()->barber)->withoutTimeOffs()->withTrashed()->orderByDesc('id')->paginate(10);

        $calAppointments = Appointment::with('user')->get();
        $barbers = Barber::with('user')->get();
     
        return view('appointment.index',[
            'appointments' => $appointments,
            'calAppointments' => $calAppointments,
            'barbers' => $barbers,
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
        return redirect()->route('appointments.create.service');
    }

    public function createService(Request $request)
    {
        $request->validate([
            'service_id' => 'nullable|integer|gt:1|exists:services,id',
            'barber_id' => 'nullable|integer|exists:barbers,id',
            'user_id' => 'nullable|integer|exists:users,id'
        ]);

        $services = Service::withoutTimeoff()->get();
        $barbers = Barber::all();

        return view('my-appointment.create_barber_service',[
            'services' => $services,
            'barbers' => $barbers,
            'view' => 'barber'
        ]);
    }

    public function createGetEarliestBarber(Request $request)
    {
        $request->validate([
            'service_id' => 'required|integer|gt:1|exists:services,id',
            'barber_id' => 'required',
            'user_id' => 'nullable|integer|exists:users,id'
        ]);

        if ($request->barber_id == 'earliest') {
            $service = Service::find($request->service_id);
            $barbers = Barber::all();

            $earliestTimeslot = '';
            $earliestBarberId = '';

            foreach ($barbers as $barber) {
                $freeSlots = Appointment::getFreeTimeSlots($barber,$service,3);

                if ($freeSlots != []) {
                    $earliestTimeslotOfBarber = Carbon::parse(array_key_first($freeSlots) . ' ' . $freeSlots[array_key_first($freeSlots)][0]);
                    
                    if ($earliestTimeslot == '' || $earliestTimeslotOfBarber < $earliestTimeslot) {
                        $earliestTimeslot = $earliestTimeslotOfBarber;
                        $earliestBarberId = $barber->id;
                    }
                }
            }

            if ($earliestBarberId == '') {
                $earliestBarberId = $barbers->random();
            }

        } else {
            $request->validate(['barber_id' => 'required|integer|exists:barbers,id']);
            $earliestBarberId = $request->barber_id;
        }

        return redirect()->route('appointments.create.date',[
            'barber_id' => $earliestBarberId,
            'service_id' => $request->service_id,
            'user_id' => $request->user_id
        ]);
    }

    public function createDate(Request $request)
    {
        $request->validate([
            'service_id' => ['required','integer','exists:services,id','gt:1'],
            'barber_id' => ['required','integer','exists:barbers,id'],
            'user_id' => ['nullable','integer','exists:users,id']
        ]);

        $barber = Barber::find($request->barber_id);
        $service = Service::find($request->service_id);

        $availableSlotsByDate = Appointment::getFreeTimeSlots($barber,$service);

        return view('my-appointment.create_date',[
            'availableSlotsByDate' => $availableSlotsByDate,
            'barber' => $barber,
            'service' => $service,
            'view' => 'barber'
        ]);
    }

    public function createCustomer(Request $request) {

        $request->validate([
            'query' => 'nullable|string|max:255',
            'service_id' => ['required','integer','exists:services,id','gt:1'],
            'barber_id' => ['required','integer','exists:barbers,id'],
            'date' => ['required','date','after_or_equal:now','date_format:Y-m-d G:i',new ValidAppointmentTime],
            'comment' => ['nullable','string'],
            'user_id' => ['nullable','integer','exists:users,id']
        ]);

        if ($request->has('user_id')) {
            return redirect()->route('appointments.create.confirm',[
                'service_id' => $request->service_id,
                'barber_id' => $request->barber_id,
                'user_id' => $request->user_id,
                'date' => $request->date,
                'comment' => $request->comment
            ]);
        }
        
        $query = $request->input('query');

        $users = User::registered()->where('id','!=',auth()->user()->id)
        ->when($query, function ($q) use ($query) {
            $q->where('first_name','like',"%$query%")
            ->orWhere('last_name','like',"%$query%")
            ->orWhere('email','like',"%$query%")
            ->orWhere('tel_number','like',"%$query%");
        })->orderBy('first_name')->paginate(10);

        return view('appointment.create',[
            'users' => $users,
            'service' => Service::find($request->service_id),
            'barber' => Service::find($request->barber_id),
        ]);
    }

    public function createConfirm(Request $request) {
        $request->validate([
            'date' => ['required','date','after_or_equal:now','date_format:Y-m-d G:i',new ValidAppointmentTime],
            'user_id' => ['nullable','integer','exists:users,id'],
            'service_id' => ['required','exists:services,id','gt:1'],
            'barber_id' => ['required','integer','exists:barbers,id'],
            'comment' => ['nullable','string']
        ]);

        if ($request->user_id == auth()->user()->id) {
            return redirect()->route('appointments.create.customer',['service_id' => $request->service_id,'date' => $request->date, 'comment' => $request->comment])->with('error',__('barber.error_select_customer'));
        }

        $startTime = Carbon::parse($request->date);

        $data = [
            'barber' => Barber::find($request->barber_id),
            'service' => Service::find($request->service_id),
            'startTime' => $startTime,
            'comment' => $request->comment,
            'view' => 'barber'
        ];

        if ($request->has('user_id')) {
            $data['user'] = User::find($request->user_id);
        }

        return view('my-appointment.create_confirm', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => ['required','date','after_or_equal:now','date_format:Y-m-d G:i:s',new ValidAppointmentTime],
            'user_id' => ['nullable','integer','exists:users,id'],
            'service_id' => ['required','exists:services,id','gt:1'],
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

        $app_start_time = Carbon::parse($request->date);
        $service = Service::findOrFail($request->service_id);
        $duration = $service->duration;
        $app_end_time = $app_start_time->clone()->addMinutes($duration);
        $barber = Barber::find($request->barber_id);

        if ($user->id == $barber->user_id) {
            return redirect()->route('appointments.create.customer',['service_id' => $request->service_id,'date' => $request->date, 'comment' => $request->comment])->with('error',__('barber.error_select_customer'));
        }

        if (!Appointment::checkAppointmentClashes($app_start_time,$app_end_time,$barber)) {
            return redirect()->route('appointments.create.date',['service_id' => $service->id, 'comment' => $request->comment])->with('error',__('barber.error_clashing'));
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

        // if ($user->email) {
        //     $appointment->user->notify(
        //         new BookingConfirmationNotification($appointment)
        //     );
        // }

        return redirect()->route('appointments.show',['appointment' =>  $appointment])->with('success',__('barber.success_new_booking'));
    }
    
    public function show(Appointment $appointment)
    {
        $response = Gate::inspect('view', $appointment);
        if ($response->denied()) {
            return redirect()->route('appointments.index')->with('error',$response->message());
        }
        
        if (Gate::allows('isTimeOff',$appointment)) {
            return redirect()->route('time-offs.show',$appointment);
        }

        $upcoming = Appointment::userFilter($appointment->user)->upcoming()->count();
        $previous = Appointment::userFilter($appointment->user)->previous()->count();
        $cancelled = Appointment::onlyTrashed()->userFilter($appointment->user)->count();

        $barber = Appointment::where('barber_id', '!=',$appointment->user?->barber?->id)->select('barber_id',DB::raw('COUNT(barber_id) as selection_count'))->userFilter($appointment->user)->groupBy('barber_id')->orderByDesc('selection_count')->first();
        
        $favBarber = $barber ? Barber::withTrashed()->find($barber->barber_id) : null;
        $numBarber = $barber ? $barber->selection_count : null;

        $service = Appointment::withoutTimeoffs()->select('service_id',DB::raw('COUNT(service_id) as selection_count'))->userFilter($appointment->user)->groupBy('service_id')->orderByDesc('selection_count')->first();

        $favService = $service ? Service::withTrashed()->find($service->service_id) : null;
        $numService = $service ? $service->selection_count : null;

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
        $response = Gate::inspect('update',$appointment);
        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        if (Gate::allows('isTimeOff',$appointment)) {
            return redirect()->route('time-offs.edit',$appointment);
        }

        $appointments = Appointment::with('user')->get();
        $services = Service::withoutTimeoff()->get();
        $barbers = Barber::all();

        return view('appointment.edit', [
            'appointment' => $appointment,
            'services' => $services,
            'appointments' => $appointments,
            'barbers' => $barbers
        ]);
    }
    
    public function update(Request $request, Appointment $appointment)
    {
        $response = Gate::inspect('update',$appointment);
        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        if (Gate::allows('isTimeOff',$appointment)) {
            return redirect()->route('time-offs.edit',$appointment);
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

        if ($appointment->user->id == $barber->user_id) {
            return redirect()->route('appointments.edit.customer',['service_id' => $request->service_id,'date' => $request->date, 'comment' => $request->comment, 'appointment' => $appointment])->with('error',__('barber.error_select_customer'));
        }

        if ($app_start_time >= $app_end_time) {
            return redirect()->route('appointments.edit',$appointment)->with('error',__('barber.error_ending_time'));
        }

        if (!Appointment::checkAppointmentClashes($app_start_time,$app_end_time,$barber,$appointment)) {
            return redirect()->route('appointments.edit',$appointment)->with('error',__('barber.error_clashing'));
        }

        $oldAppointment = $appointment->only([
            'barber_id',
            'service_id',
            'comment',
            'price',
            'app_start_time',
            'app_end_time'
        ]);

        $appointment->update([
            'service_id' => $request->service,
            'barber_id' => $barber->id,
            'comment' => $request->comment,
            'price' => $request->price,
            'app_start_time' => $app_start_time,
            'app_end_time' => $app_end_time
        ]);

        // if ($appointment->user->email) {
        //     $appointment->user->notify(
        //         new BookingUpdateNotification($oldAppointment,$appointment,updatedBy: Barber::find($oldAppointment['barber_id']))
        //     );
        // }

        return redirect()->route('appointments.show',['appointment' => $appointment])->with('success',__('barber.success_updated_booking'));
    }

    public function destroy(Appointment $appointment)
    {
        $response = Gate::inspect('delete',$appointment);
        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        if (Gate::allows('isTimeOff',$appointment)) {
            return redirect()->route('time-offs.show',$appointment);
        }

        $barber = auth()->user()->barber;

        // $appointment->user->notify(
        //     new BookingCancellationNotification($appointment,$barber)
        // );
        $appointment->delete();

        return redirect()->route('appointments.show',$appointment)
            ->with('success',__('barber.success_booking_destroyed'));
    }
}
