<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Barber;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Rules\ValidAppointmentTime;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Collection;
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
        $service = Service::find($request->service_id);

        $availableSlotsByDate = Appointment::getFreeTimeSlots($barber,$service);

        return view('my-appointment.create_date',[
            'availableSlotsByDate' => $availableSlotsByDate,
            'barber' => $barber,
            'service' => $service
        ]);
    }

    public function createConfirm(Request $request) {
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
            'startTime' => $startTime
        ];

        if ($request->comment) {
            $data['comment'] = $request->comment;
        }

        return view('my-appointment.create_confirm', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => ['required','date','after_or_equal:now','date_format:Y-m-d G:i:s',new ValidAppointmentTime],
            'barber_id' => ['required','exists:barbers,id'],
            'service_id' => ['required','gt:1','exists:services,id'],
            'comment' => ['nullable','string'],
            'first_name' => ['nullable','string','min:1'],
            'email' => ['nullable','email','min:1'],
            'policy_checkbox' => ['required'],
            'confirmation_checkbox' => ['required']
        ]);

        // kezelni az auth nélküli esetet

        $barber = Barber::find($request->barber_id);

        $app_start_time = Carbon::parse($request->date);
        $duration = Service::findOrFail($request->service_id)->duration;
        $app_end_time = $app_start_time->clone()->addMinutes($duration);

        if (!Appointment::checkAppointmentClashes($app_start_time,$app_end_time,$barber)) {
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
        $response = Gate::inspect('userView',$my_appointment);
        if ($response->denied()) {
            return redirect()->route('my-appointments.index')->with('error',$response->message());
        }
        
        if (Gate::allows('isTimeOff',$my_appointment)) {
            return redirect()->route('my-appointments.index')->with('error', 'You cannot view your time offs in the customer view. Please switch to barber view to manage your time offs!');
        }

        return view('my-appointment.show',[
            'appointment' => $my_appointment
        ]);
    }

    public function destroy(Appointment $my_appointment)
    {
        $response = Gate::inspect('userDelete',$my_appointment);
        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        if (Gate::allows('isTimeOff',$my_appointment)) {
            return redirect()->route('my-appointments.index')->with('error', "You can't cancel a time off here. Please switch to barber view to manage your time offs!");
        }

        $my_appointment->barber->user->notify(
            new BookingCancellationNotification($my_appointment,$my_appointment->user)
        );
        $my_appointment->delete();
        return redirect()->route('my-appointments.index')
            ->with('success','Appointment cancelled successfully! Don\'t forget to book another one instead!');
    }
}