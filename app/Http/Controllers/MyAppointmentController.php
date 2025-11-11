<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Barber;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Rules\ValidAppointmentTime;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Collection;
use App\Notifications\BookingUpdateNotification;
use App\Notifications\BookingCancellationNotification;
use App\Notifications\BookingConfirmationNotification;

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
        return redirect()->route('my-appointments.create.barber.service');
    }

    public function createBarberService(Request $request)
    {
        $request->validate([
            'barber_id' => 'nullable|integer|exists:barbers,id',
            'service_id' => 'nullable|integer|gt:1|exists:services,id'
        ]);
        
        $selectedServiceId = $request->service_id;
        $selectedBarberId = $request->barber_id;

        $barbers = Barber::when(auth()->user()?->barber != null, function($q) {
            return $q->where('id','!=',auth()->user()->barber->id);
        })->where('is_visible','=',1)->get();

        $services = Service::withoutTimeoff()->where('is_visible','=',1)->get();

        return view('my-appointment.create_barber_service',[
            'barbers' => $barbers,
            'services' => $services,
            'service_id' => $selectedServiceId,
            'barber_id' => $selectedBarberId
        ]);
    }

    public function createGetEarliestBarber(Request $request) {
        $request->validate([
            'barber_id' => 'required',
            'service_id' => 'required|integer|gt:1|exists:services,id'
        ]);

        if ($request->barber_id == 'earliest') {
            $service = Service::find($request->service_id);
            $barbers = Barber::where('is_visible','=',1)->get();

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

        return redirect()->route('my-appointments.create.date',[
            'barber_id' => $earliestBarberId,
            'service_id' => $request->service_id
        ]);
    }

    public function createDate(Request $request)
    {
        if (!Barber::find($request->barber_id) || auth()->user()?->barber && $request->barber_id == auth()->user()?->barber->id) {
            return redirect()->route('my-appointments.create.barber.service',['service_id' => $request->service_id])->with('error',__('appointments.barber_error'));
        }

        if (!Service::find($request->service_id) || $request->service_id == 1) {
            return redirect()->route('my-appointments.create.barber.service',['barber_id' => $request->barber_id])->with('error',__('appointments.service_error'));
        }

        $request->validate([
            'barber_id' => 'required|integer|exists:barbers,id',
            'service_id' => 'required|integer|gt:1|exists:services,id',
            'date' => ['nullable','date','after_or_equal:now','date_format:Y-m-d G:i',new ValidAppointmentTime],
            'comment' => 'nullable|string'
        ]);

        $barber = Barber::find($request->barber_id);
        $service = Service::find($request->service_id);

        $availableSlotsByDate = Appointment::getFreeTimeSlots($barber,$service);

        return view('my-appointment.create_date',[
            'availableSlotsByDate' => $availableSlotsByDate,
            'barber' => $barber,
            'service' => $service
        ]);
    }

    public function createConfirm(Request $request) {

        if (!Barber::find($request->barber_id) || auth()->user()?->barber && $request->barber_id == auth()->user()?->barber->id) {
            return redirect()->route('my-appointments.create.barber.service',['service_id' => $request->service_id])->with('error',__('appointments.barber_error'));
        }

        if (!Service::find($request->service_id) || $request->service_id == 1) {
            return redirect()->route('my-appointments.create.barber.service',['barber_id' => $request->barber_id])->with('error',__('appointments.service_error'));
        }
        
        $request->validate([
            'barber_id' => 'required|integer|exists:barbers,id',
            'service_id' => 'required|integer|gt:1|exists:services,id',
            'date' => ['required','date','after_or_equal:now','date_format:Y-m-d G:i',new ValidAppointmentTime],
            'comment' => 'nullable|string'
        ]);

        $startTime = Carbon::parse($request->date);

        $data = [
            'barber' => Barber::find($request->barber_id),
            'service' => Service::find($request->service_id),
            'startTime' => $startTime,
            'comment' => $request->comment
        ];

        return view('my-appointment.create_confirm', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => ['required','date','after_or_equal:now','date_format:Y-m-d G:i:s',new ValidAppointmentTime],
            'barber_id' => ['required','integer','exists:barbers,id'],
            'service_id' => ['required','integer','gt:1','exists:services,id'],
            'comment' => ['nullable','string'],
            'first_name' => ['nullable','string','min:1'],
            'email' => ['nullable','email','min:1'],
            'policy_checkbox' => ['required'],
            'confirmation_checkbox' => ['required']
        ]);

        // HANDLING AUTHLESS CASES
        if ($request->has(['first_name','email'])) {
            $email = $request->email;
            $firstName = $request->first_name;

            $users = User::where('email','=',$email)->get();

            if ($users->count() == 1) {
                $user = $users->first();
                if (!isset($user->last_name)) {
                    if ($firstName != $user->first_name) {
                        $user->update([
                            'first_name' => $firstName
                        ]);
                    }
                } else {
                    return redirect()->back()->with('error',__('appointments.invalid_email_1') . " (" . $email . ") " . __('appointments.invalid_email_2') );
                }
            } else {
                $user = User::create([
                    'first_name' => $request->first_name,
                    'email' => $email,
                    'is_admin' => false
                ]);
            }
        }

        $user = auth()->user() ?? $user;
        $barber = Barber::find($request->barber_id);

        if ($user->id == $barber->user_id) {
            return redirect()->route('my-appointments.create.barber.service',['service_id' => $request->service_id])->with('error',__('appointments.user_barber_error'));
        }

        $app_start_time = Carbon::parse($request->date);
        $duration = Service::findOrFail($request->service_id)->duration;
        $app_end_time = $app_start_time->clone()->addMinutes($duration);

        if (!Appointment::checkAppointmentClashes($app_start_time,$app_end_time,$barber)) {
            return redirect()->route('my-appointments.create.date',['barber_id' => $request->barber_id, 'service_id' => $request->service_id])->with('error',__('appointments.clashing_error'));
        }

        $appointment = Appointment::create([
            'user_id' => $user->id,
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

        if (auth()->user()) {
            return redirect()->route('my-appointments.show',['my_appointment' =>  $appointment])->with('success',__('appointments.store_success'));
        } else {
            return redirect()->route('my-appointments.create.success')->with('user',$user->id);
        }
    }

    public function createSuccess() {
        if (!session('user')) {
            return redirect()->route('home');
        }

        $user = User::find(session('user'));

        return view('my-appointment.create_success',['user' => $user]);
    }

    public function show(Appointment $my_appointment)
    {
        $response = Gate::inspect('userView',$my_appointment);
        if ($response->denied()) {
            return redirect()->route('my-appointments.index')->with('error',$response->message());
        }
        
        if (Gate::allows('isTimeOff',$my_appointment)) {
            return redirect()->route('my-appointments.index')->with('error', __('appointments.timeoff_show_error'));
        }

        return view('my-appointment.show',[
            'appointment' => $my_appointment
        ]);
    }

    public function edit(Appointment $my_appointment)
    {
        $response = Gate::inspect('userEdit',$my_appointment);
        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        return redirect()->route('my-appointments.edit.barber.service',$my_appointment);
    }

    public function editBarberService(Appointment $my_appointment, Request $request) 
    {
        $response = Gate::inspect('userEdit',$my_appointment);
        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        $request->validate([
            'barber_id' => 'nullable|integer|exists:barbers,id',
            'service_id' => 'nullable|integer|gt:1|exists:services,id'
        ]);
        
        $selectedServiceId = $request->service_id;
        $selectedBarberId = $request->barber_id;

        $barbers = Barber::when(auth()->user()?->barber != null, function($q) {
            return $q->where('id','!=',auth()->user()->barber->id);
        })->where('is_visible','=',1)->get();

        $services = Service::withoutTimeoff()->where('is_visible','=',1)->get();

        return view('my-appointment.create_barber_service',[
            'barbers' => $barbers,
            'services' => $services,
            'service_id' => $selectedServiceId,
            'barber_id' => $selectedBarberId,
            'appointment' => $my_appointment,
            'action' => 'edit'
        ]);
    }

    public function editGetEarliestBarber(Appointment $my_appointment, Request $request) 
    {
        $response = Gate::inspect('userEdit',$my_appointment);
        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        $request->validate([
            'barber_id' => 'required',
            'service_id' => 'required|integer|gt:1|exists:services,id'
        ]);

        if ($request->barber_id == 'earliest') {
            $service = Service::find($request->service_id);
            $barbers = Barber::where('is_visible','=',1)->get();

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

        return redirect()->route('my-appointments.edit.date',[
            'barber_id' => $earliestBarberId,
            'service_id' => $request->service_id,
            'my_appointment' => $my_appointment
        ]);
    }

    public function editDate(Appointment $my_appointment, Request $request) 
    {
        $response = Gate::inspect('userEdit',$my_appointment);
        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        if (!Barber::find($request->barber_id) || auth()->user()?->barber && $request->barber_id == auth()->user()?->barber->id) {
            return redirect()->route('my-appointments.edit.barber.service',['my_appointment' => $my_appointment, 'service_id' => $request->service_id])->with('error',__('appointments.barber_error'));
        }

        if (!Service::find($request->service_id) || $request->service_id == 1) {
            return redirect()->route('my-appointments.edit.barber.service',['my_appointment' => $my_appointment, 'barber_id' => $request->barber_id])->with('error',__('appointments.service_error'));
        }

        $request->validate([
            'barber_id' => 'required|integer|exists:barbers,id',
            'service_id' => 'required|integer|gt:1|exists:services,id',
            'date' => ['nullable','date','after_or_equal:now','date_format:Y-m-d G:i',new ValidAppointmentTime],
            'comment' => 'nullable|string'
        ]);

        $barber = Barber::find($request->barber_id);
        $service = Service::find($request->service_id);

        $availableSlotsByDate = Appointment::getFreeTimeSlots($barber,$service, except:$my_appointment);

        return view('my-appointment.create_date',[
            'availableSlotsByDate' => $availableSlotsByDate,
            'barber' => $barber,
            'service' => $service,
            'appointment' => $my_appointment,
            'action' => 'edit'
        ]);
    }

    public function editConfirm(Appointment $my_appointment, Request $request) 
    {
        $response = Gate::inspect('userEdit',$my_appointment);
        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        if (!Barber::find($request->barber_id) || auth()->user()?->barber && $request->barber_id == auth()->user()?->barber->id) {
            return redirect()->route('my-appointments.edit.barber.service',['service_id' => $request->service_id, 'my_appointment' => $my_appointment])->with('error',__('appointments.barber_error'));
        }

        if (!Service::find($request->service_id) || $request->service_id == 1) {
            return redirect()->route('my-appointments.edit.barber.service',['barber_id' => $request->barber_id, 'my_appointment' => $my_appointment])->with('error',__('appointments.service_error'));
        }

        $request->validate([
            'barber_id' => 'required|integer|exists:barbers,id',
            'service_id' => 'required|integer|gt:1|exists:services,id',
            'date' => ['required','date','after_or_equal:now','date_format:Y-m-d G:i',new ValidAppointmentTime],
            'comment' => 'nullable|string'
        ]);

        $startTime = Carbon::parse($request->date);

        $data = [
            'barber' => Barber::find($request->barber_id),
            'service' => Service::find($request->service_id),
            'startTime' => $startTime,
            'appointment' => $my_appointment,
            'action' => 'edit'
        ];

        if ($request->comment) {
            $data['comment'] = $request->comment;
        }

        return view('my-appointment.create_confirm', $data);
    }

    public function update(Appointment $my_appointment, Request $request) {
        $response = Gate::inspect('userEdit',$my_appointment);
        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        $request->validate([
            'date' => ['required','date','after_or_equal:now','date_format:Y-m-d G:i:s',new ValidAppointmentTime],
            'barber_id' => ['required','exists:barbers,id'],
            'service_id' => ['required','gt:1','exists:services,id'],
            'comment' => ['nullable','string'],
            'policy_checkbox' => ['required'],
            'confirmation_checkbox' => ['required']
        ]);

        $user = auth()->user();
        $barber = Barber::find($request->barber_id);

        if ($user->id == $barber->user_id) {
            return redirect()->route('my-appointments.edit.barber.service',['service_id' => $request->service_id,'my_appointment'=>$my_appointment])->with('error',__('appointments.user_barber_error'));
        }

        $service = Service::find($request->service_id);
        $comment = $request->comment;

        $app_start_time = Carbon::parse($request->date);
        $duration = $service->duration;
        $app_end_time = $app_start_time->clone()->addMinutes($duration);
        
        if (!Appointment::checkAppointmentClashes($app_start_time,$app_end_time,$barber, $my_appointment)) {
            return redirect()->route('my-appointments.edit.date',['barber_id' => $request->barber_id, 'service_id' => $request->service_id, 'date' => $app_start_time->format('Y-m-d G:i'), 'comment' => $comment])->with('error',__('appointments.clashing_error'));
        }

        $oldAppointment = $my_appointment->only([
            'barber_id',
            'service_id',
            'comment',
            'price',
            'app_start_time',
            'app_end_time'
        ]);

        $my_appointment->update([
            'barber_id' => $barber->id,
            'service_id' => $service->id,
            'app_start_time' => $app_start_time,
            'app_end_time' => $app_end_time,
            'price' => $service->price,
            'comment' => $comment
        ]);

        $my_appointment->user->notify(
            new BookingUpdateNotification($oldAppointment,$my_appointment,$my_appointment->user)
        );

        return redirect()->route('my-appointments.show',['my_appointment' =>  $my_appointment])->with('success',__('appointments.update_success'));
    }

    public function destroy(Appointment $my_appointment)
    {
        $response = Gate::inspect('userDelete',$my_appointment);
        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        if (Gate::allows('isTimeOff',$my_appointment)) {
            return redirect()->route('my-appointments.index')->with('error', __('appointments.timeoff_cancel_error'));
        }

        $my_appointment->barber->user->notify(
            new BookingCancellationNotification($my_appointment,$my_appointment->user)
        );
        $my_appointment->delete();
        return redirect()->route('my-appointments.index')
            ->with('success',__('appointments.cancel_success'));
    }
}