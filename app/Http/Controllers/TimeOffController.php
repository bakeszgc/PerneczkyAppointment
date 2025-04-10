<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TimeOffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            return redirect()->route('time-off.create')->with('error',"The ending time of your time off has to be later than its starting time");
        }

        // app start time vagy app end time kisebb mint most
        if ($app_start_time < now()) {
            return redirect()->route('time-off.create')->with('error',"The starting time of your time off cannot be in the past!");
        } elseif ($app_end_time < now()) {
            return redirect()->route('time-off.create')->with('error',"The ending time of your time off cannot be in the past!");
        }

        $barber = auth()->user()->barber;

        // check hogy ne ütközzön semmivel
        $appointmentsStart = Appointment::where('barber_id','=',$barber->id)
        ->where('app_start_time','>=',$app_start_time)
        ->where('app_start_time','<',$app_end_time)->get();

        $appointmentsEnd = Appointment::where('barber_id','=',$barber->id)
        ->where('app_end_time','>',$app_start_time)
        ->where('app_end_time','<=',$app_end_time)->get();

        if ($appointmentsStart->count() + $appointmentsEnd->count() != 0) {
            return redirect()->route('time-off.create')->with('error','You have bookings clashing with the selected timeframe.');
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

            Appointment::create([
                'user_id' => $barber->user_id,
                'barber_id' => $barber->id,
                'service_id' => 1,
                'app_start_time' => $timeOffStart,
                'app_end_time' => $timeOffEnd,
                'price' => 0
            ]);
        }

        return redirect()->route('appointments.index')->with('success', 'Time off created successfully! Enjoy your well deserved rest!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
