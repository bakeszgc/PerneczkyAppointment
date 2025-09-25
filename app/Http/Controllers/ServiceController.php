<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::withTrashed()->where('id','!=',1)->withCount('appointments')->get();
        return view('service.index',['services' => $services]);
    }

    public function create()
    {
        return view('service.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required','string','min:3','max:255'],
            'price' => ['required','integer','min:100','max:100000'],
            'duration' => ['required','integer','multiple_of:15','min:15']
        ]);

        $service = Service::create([
            'name' => $request->name,
            'price' => $request->price,
            'duration' => $request->duration,
            'is_visible' => $request->is_visible ? true : false
        ]);

        return redirect()->route('services.show',$service)->with('success',$service->name . " has been created successfully!");
    }

    public function show(Service $service)
    {
        // ne lehessen megnézni a timeoffot
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
        if (isset($service->deleted_at)) {
            return redirect()->route('services.show',$service)->with('error','You cannot edit deleted services. If you wish to proceed please restore ' . $service->name . ' first!');
        }

        $request->validate([
            'name' => ['required','string','min:3','max:255'],
            'price' => ['required','integer','min:100','max:100000'],
            'duration' => ['required','integer','multiple_of:15','min:15']
        ]);

        $service->update([
            'name' => $request->name,
            'price' => $request->price,
            'duration' => $request->duration,
            'is_visible' => isset($request->is_visible)
        ]);

        return redirect()->route('services.show',$service)->with('success',$service->name . " has been updated successfully!");
    }

    public function destroy(Service $service)
    {
        $name = $service->name;
        $service->update([
            'is_visible' => 0
        ]);
        $service->delete();
        return redirect()->route('services.show',$service)->with('success', $name . " has been deleted successfully!");
    }

    public function restore(Service $service)
    {
        $service->restore();
        return redirect()->route('services.show',$service)->with('success',$service->name . " has been restored successfully!");
    }
}
