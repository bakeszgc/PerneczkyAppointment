<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TimeOffController extends Controller
{
    public function index()
    {
        $timeoffs = Appointment::where('service_id','=',1)
            ->barberFilter(auth()->user()->barber)
            ->latest()
        ->paginate(10);

        $calAppointments = Appointment::barberFilter(auth()->user()->barber)
        ->with('user')->get();

        return view('time-off.index',[
            'timeoffs' => $timeoffs,
            'calAppointments' => $calAppointments,
            'type' => 'All']);
    }

    public function indexUpcoming()
    {
        $timeoffs = Appointment::where('service_id','=',1)
            ->barberFilter(auth()->user()->barber)
            ->upcoming()
        ->paginate(10);

        return view('time-off.index',['timeoffs' => $timeoffs, 'type' => 'Upcoming']);
    }

    public function indexPrevious() {
        $timeoffs = Appointment::where('service_id','=',1)
            ->barberFilter(auth()->user()->barber)
            ->previous()
        ->paginate(10);

        return view('time-off.index',['timeoffs' => $timeoffs, 'type' => 'Previous']);
    }

    public function create()
    {
        $appointments = Appointment::barberFilter(auth()->user()->barber)->with('user')->get();

        return view('appointment.edit',[
            'view' => 'Time Off',
            'action' => 'create',
            'appointments' => $appointments
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'app_start_date' => ['required','date','after_or_equal:today'],
            'app_start_hour' => ['nullable','integer','between:10,19'],
            'app_start_minute' => 'nullable|integer|multiple_of:15',
            'app_end_date' => ['required','date','after_or_equal:app_start_date'],
            'app_end_hour' => ['nullable','between:10,21','integer'],
            'app_end_minute' => 'nullable|integer|multiple_of:15',
            'full_day' => 'nullable'
        ]);

        $time_off = Appointment::createTimeOff($request->only('app_start_date','app_start_hour','app_start_minute','app_end_date','app_end_hour','app_end_minute'),auth()->user()->barber);

        if (get_class($time_off) != "App\Models\Appointment") {
            return $time_off;
        } else {
            return redirect()->route('time-offs.show',$time_off)->with('success', 'Time off created successfully! Enjoy your well deserved rest!');
        }
    }

    public function show(Appointment $time_off)
    {
        $response = Gate::inspect('view', $time_off);
        if ($response->denied()) {
            return redirect()->route('time-offs.index')->with('error',$response->message());
        }
        
        if (Gate::denies('isTimeOff',$time_off)) {
            return redirect()->route('appointments.show',$time_off);
        }

        return view('time-off.show',['appointment' => $time_off]);
    }

    public function edit(Appointment $time_off)
    {
        $response = Gate::inspect('update',$time_off);
        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        if (Gate::denies('isTimeOff',$time_off)) {
            return redirect()->route('appointments.edit',$time_off);
        }

        $appointments = Appointment::barberFilter(auth()->user()->barber)->with('user')->get();

        return view('appointment.edit',[
            'appointment' => $time_off,
            'view' => 'Time Off',
            'appointments' => $appointments
        ]);
    }

    public function update(Request $request, Appointment $time_off)
    {
        $response = Gate::inspect('update',$time_off);
        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        if (Gate::denies('isTimeOff',$time_off)) {
            return redirect()->route('appointments.edit',$time_off);
        }

        $request->validate([
            'app_start_date' => ['required','date','after_or_equal:today'],
            'app_start_hour' => ['nullable','integer','between:10,19'],
            'app_start_minute' => 'nullable|integer|multiple_of:15',
            'app_end_date' => ['required','date','after_or_equal:app_start_date'],
            'app_end_hour' => ['nullable','between:10,21','integer'],
            'app_end_minute' => 'nullable|integer|multiple_of:15',
            'full_day' => 'nullable'
        ]);

        $time_off = Appointment::createTimeOff($request->only('app_start_date','app_start_hour','app_start_minute','app_end_date','app_end_hour','app_end_minute'),auth()->user()->barber,$time_off);

        if (get_class($time_off) != "App\Models\Appointment") {
            return $time_off;
        } else {
            return redirect()->route('time-offs.show',$time_off)->with('success','Time off has been updated successfully!');
        }
    }

    public function destroy(Appointment $time_off)
    {
        $response = Gate::inspect('update',$time_off);
        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        if (Gate::denies('isTimeOff',$time_off)) {
            return redirect()->route('appointments.show',$time_off);
        }

        $time_off->delete();
        return redirect()->route('time-offs.index')->with('success','Time off has been cancelled successfully!');
    }
}
