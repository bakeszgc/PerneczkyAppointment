<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Notifications\BookingCancellationNotification;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::where('barber_id','=',auth()->user()->barber->id)->with([
            'user','service','barber'
        ])->withTrashed()->latest()->paginate(10);

        $calAppointments = Appointment::where('barber_id','=',auth()->user()->barber->id)->with([
            'user','service','barber'
        ])->whereBetween('app_start_time',[date("Y-m-d", strtotime('monday this week')),date("Y-m-d", strtotime('monday next week'))])->get();
     
        return view('appointment.index',[
            'appointments' => $appointments,
            'calAppointments' => $calAppointments,
            'type' => 'All'
        ]);
    }

    public function indexUpcoming() {
        $upcomingAppointments = Appointment::with([
            'user','service','barber'
        ])->where('app_start_time','>=',now('Europe/Budapest'))
        ->where('barber_id','=',auth()->user()->barber->id)
        ->orderBy('app_start_time')->paginate(10);
        
        return view('appointment.index',[
            'appointments' => $upcomingAppointments,
            'type' => 'Upcoming'
        ]);
    }

    public function indexPrevious() {
        $previousAppointments = Appointment::with([
            'user','service','barber'
        ])->where('app_start_time','<=',now('Europe/Budapest'))
        ->where('barber_id','=',auth()->user()->barber->id)
        ->orderBy('app_start_time','desc')->paginate(10);
        
        return view('appointment.index',[
            'appointments' => $previousAppointments,
            'type' => 'Previous'
        ]);
    }

    public function indexCancelled() {
        $cancelledAppointments = Appointment::onlyTrashed()->with(['user','service','barber'])
        ->where('barber_id','=',auth()->user()->barber->id)
        ->orderBy('app_start_time','desc')->paginate(10);

        return view('appointment.index',[
            'appointments' => $cancelledAppointments,
            'type' => 'Cancelled'
        ]);
    }

    public function create()
    {
        return view('appointment.create',['users' => User::orderBy('first_name')->get()]);
    }

    public function store(Request $request)
    {
        //
    }
    
    public function show(Appointment $appointment)
    {
        return view('appointment.show',[
            'appointment' => $appointment
        ]);
    }
    
    public function edit(Appointment $appointment)
    {
        // can't edit deleted or previous records
        return view('appointment.edit', [
            'appointment' => $appointment,
            'services' => Service::all()
        ]);
    }
    
    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'service' => 'required',
            'comment' => 'max:255'
        ]);

        //ez még nem működik
        $newPrice = Service::where('id','=',$request->service)->firstOrFail()->price;

        $appointment->update([
            'service_id' => $request->service,
            'comment' => $request->comment,
            'price' => $newPrice
        ]);

        return redirect()->route('appointments.show',['appointment' => $appointment]);
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
