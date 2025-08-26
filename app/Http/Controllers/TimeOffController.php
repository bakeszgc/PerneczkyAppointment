<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TimeOffController extends Controller
{
    public function index()
    {
        $timeoffs = Appointment::where('service_id','=',1)
            ->barberFilter(auth()->user()->barber)
            ->latest()
        ->paginate(10);

        $calAppointments = Appointment::barberFilter(auth()->user()->barber)
        ->whereBetween('app_start_time',[date("Y-m-d", strtotime('monday this week')),date("Y-m-d", strtotime('monday next week'))])->get();

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
        return view('time-off.create');
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

        // start és end timeok + ha full day akkor 10 és 20 óra
        $app_start_time = Carbon::parse($request->app_start_date . " " . ($request->app_start_hour ?? 10) . ":" . ($request->app_start_minute ?? 00));
        $app_end_time = Carbon::parse($request->app_end_date . " " . ($request->app_end_hour ?? 20) . ":" . ($request->app_end_minute ?? 00));

        // app start time nagyobb mint az app end time
        if ($app_start_time > $app_end_time) {
            return redirect()->route('time-offs.create')->with('error',"The ending time of your time off has to be later than its starting time");
        }

        // app start time vagy app end time kisebb mint most
        if ($app_start_time < now()) {
            return redirect()->route('time-offs.create')->with('error',"The starting time of your time off cannot be in the past!");
        } elseif ($app_end_time < now()) {
            return redirect()->route('time-offs.create')->with('error',"The ending time of your time off cannot be in the past!");
        }

        $barber = auth()->user()->barber;

        if (!Appointment::checkAppointmentClashes($app_start_time,$app_end_time,$barber)) {
            return redirect()->route('time-offs.create')->with('error','You have bookings clashing with the selected timeframe.');
        }

        // minden napra külön létrehozni time off appointmentet
        $numOfDays = $app_start_time->clone()->startOfDay()->diffInDays($app_end_time->clone()->startOfDay())+1;
        for ($i=0; $i < $numOfDays; $i++) { 

            $timeOffStart = $app_start_time;
            $timeOffEnd = $app_end_time;
            
            if ($i != 0) {
                $timeOffStart = $app_start_time->clone()->startOfDay()->addHours(10)->addDays($i);
            }
            if ($i != $numOfDays-1) {
                $timeOffEnd = $app_start_time->clone()->startOfDay()->addHours(20)->addDays($i);
            }

            $time_off = Appointment::create([
                'user_id' => $barber->user_id,
                'barber_id' => $barber->id,
                'service_id' => 1,
                'app_start_time' => $timeOffStart,
                'app_end_time' => $timeOffEnd,
                'price' => 0
            ]);
        }

        return redirect()->route('time-offs.show',$time_off)->with('success', 'Time off created successfully! Enjoy your well deserved rest!');
    }

    public function show(Appointment $time_off)
    {
        return view('time-off.show',['appointment' => $time_off]);
    }

    public function edit(Appointment $time_off)
    {
        //előző és következő időpontok
        $previousAppointment = Appointment::barberFilter(auth()->user()->barber)->endEarlierThan($time_off->app_start_time)->orderByDesc('app_end_time')->first();

        $nextAppointment = Appointment::barberFilter(auth()->user()->barber)->startLaterThan($time_off->app_end_time)->orderBy('app_start_time')->first();

        return view('time-off.edit',[
            'appointment' => $time_off,
            'previous' => $previousAppointment,
            'next' => $nextAppointment
        ]);
    }

    public function update(Request $request, Appointment $time_off)
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

        // setting start and end times, if hour and minute are not sent through then considering as a full day
        $app_start_time = Carbon::parse($request->app_start_date . " " . ($request->app_start_hour ?? 10) . ":" . ($request->app_start_minute ?? 00));
        $app_end_time = Carbon::parse($request->app_end_date . " " . ($request->app_end_hour ?? 20) . ":" . ($request->app_end_minute ?? 00));

        // handling when app start time is later than app end time
        if ($app_start_time > $app_end_time) {
            return redirect()->route('time-offs.edit',$time_off)->with('error',"The ending time of your time off has to be later than its starting time");
        }

        // handling when app start time or app end time is in the past
        if ($app_start_time < now()) {
            return redirect()->route('time-offs.edit',$time_off)->with('error',"The starting time of your time off cannot be in the past!");
        } elseif ($app_end_time < now()) {
            return redirect()->route('time-offs.edit',$time_off)->with('error',"The ending time of your time off cannot be in the past!");
        }

        $barber = auth()->user()->barber;

        if (!Appointment::checkAppointmentClashes($app_start_time,$app_end_time,$barber,$time_off)) {
            return redirect()->route('time-offs.edit',$time_off)->with('error','You have bookings clashing with the selected timeframe.');
        }

        // handling when time off takes more than one day
        $numOfDays = $app_start_time->clone()->startOfDay()->diffInDays($app_end_time->clone()->startOfDay())+1;

        if ($numOfDays > 1) {
            for ($i=1; $i < $numOfDays; $i++) { 
                $timeOffStart = $app_start_time->clone()->startOfDay()->addHours(10)->addDays($i);
                $timeOffEnd = $app_end_time;

                if ($i != $numOfDays-1) {
                    $timeOffEnd = $app_start_time->clone()->startOfDay()->addHours(20)->addDays($i);
                }

                Appointment::create([
                    'user_id' => $barber->user_id,
                    'barber_id' => $barber->id,
                    'service_id' => 1,
                    'app_start_time' => $timeOffStart,
                    'app_end_time' => $timeOffEnd,
                    'price' => 0
                ]);
            }

            $app_end_time = $app_start_time->clone()->startOfDay()->addHours(20);
        }

        // updating time off and redirecting
        $time_off->update([
            'app_start_time' => $app_start_time,
            'app_end_time' => $app_end_time
        ]);

        return redirect()->route('time-offs.show',$time_off)->with('success','Time off has been updated successfully!');
    }

    public function destroy(Appointment $time_off)
    {
        $time_off->delete();
        return redirect()->route('time-offs.index')->with('success','Time off has been cancelled successfully!');
    }
}
