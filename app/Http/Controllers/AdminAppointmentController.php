<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AdminAppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Barber $barber)
    {
        $appointments = Appointment::barberFilter($barber)->withoutTimeOffs()->withTrashed()->latest()->paginate(10);

        $calAppointments = Appointment::barberFilter($barber)->whereBetween('app_start_time',[date("Y-m-d", strtotime('monday this week')),date("Y-m-d", strtotime('monday next week'))])->get();
     
        return view('appointment.index',[
            'appointments' => $appointments,
            'calAppointments' => $calAppointments,
            'type' => 'All',
            'barber' => $barber
        ]);
    }

    public function indexUpcoming (Barber $barber)
    {
        $upcomingAppointments = Appointment::upcoming()->barberFilter($barber)
        ->withoutTimeOffs()->paginate(10);
        
        return view('appointment.index',[
            'appointments' => $upcomingAppointments,
            'type' => 'Upcoming',
            'barber' => $barber
        ]);
    }

    public function indexPrevious(Barber $barber) {
        $previousAppointments = Appointment::previous()->barberFilter($barber)
        ->withoutTimeOffs()->paginate(10);
        
        return view('appointment.index',[
            'appointments' => $previousAppointments,
            'type' => 'Previous',
            'barber' => $barber
        ]);
    }

    public function indexCancelled(Barber $barber) {
        $cancelledAppointments = Appointment::onlyTrashed()->barberFilter($barber)
        ->withoutTimeOffs()->orderBy('app_start_time','desc')->paginate(10);

        return view('appointment.index',[
            'appointments' => $cancelledAppointments,
            'type' => 'Cancelled',
            'barber' => $barber
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
