<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Rules\MultipleOf;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::all();
        return view('service.index',['services' => $services]);
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
    public function show(Service $service)
    {
        $appointments = Appointment::where('service_id','=',$service->id);
        $revenue = $appointments->clone()->sum('price');
        $numberOfBookings = $appointments->clone()->count();

        return view('service.show',[
            'service' => $service,
            'revenue' => $revenue,
            'number' => $numberOfBookings
        ]);
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => ['required','string','min:3','max:255'],
            'price' => ['required','integer','min:100','max:100000'],
            'duration' => ['required','integer','multiple_of:15','min:15']
        ]);

        $service->update([
            'name' => $request->name,
            'price' => $request->price,
            'duration' => $request->duration,
            'is_visible' => $request->is_visible ? true : false
        ]);

        return redirect()->route('services.show',$service)->with('success',$service->name . " has been updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
