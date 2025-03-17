<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::withTrashed()->with([
            'user','service','barber'
        ])->latest()->paginate(10);
        return view('appointment.index',[
            'appointments' => $appointments,
            'type' => 'All'
        ]);
    }

    public function indexUpcoming() {
        $upcomingAppointments = Appointment::with([
            'user','service','barber'
        ])->where('app_start_time','>=',now('Europe/Budapest'))->orderBy('app_start_time')->paginate(10);
        
        return view('appointment.index',[
            'appointments' => $upcomingAppointments,
            'type' => 'Upcoming'
        ]);
    }

    public function indexPrevious() {
        $previousAppointments = Appointment::with([
            'user','service','barber'
        ])->where('app_start_time','<=',now('Europe/Budapest'))->orderBy('app_start_time','desc')->paginate(10);
        
        return view('appointment.index',[
            'appointments' => $previousAppointments,
            'type' => 'Previous'
        ]);
    }

    public function indexCancelled() {
        $cancelledAppointments = Appointment::onlyTrashed()->with(['user','service','barber'])
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
