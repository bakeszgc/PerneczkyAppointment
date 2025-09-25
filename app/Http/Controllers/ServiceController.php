<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Notifications\BookingCancellationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
        $response = Gate::inspect('view',$service);

        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        $appointments = Appointment::serviceFilter($service);

        $previousStats = [
            'numBookings' => $appointments->clone()->previous()->count(),
            'sumPrice' => $appointments->clone()->previous()->sum('price'),
            'avgPrice' => $appointments->clone()->previous()->avg('price')
        ];

        $upcomingStats = [
            'numBookings' => $appointments->clone()->upcoming()->count(),
            'sumPrice' => $appointments->clone()->upcoming()->sum('price'),
            'avgPrice' => $appointments->clone()->upcoming()->avg('price')
        ];

        return view('service.show',[
            'service' => $service,
            'previousStats' => $previousStats,
            'upcomingStats' => $upcomingStats
        ]);
    }

    public function update(Request $request, Service $service)
    {
        $response = Gate::inspect('update',$service);
        
        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
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
        $response = Gate::inspect('delete',$service);
        
        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        $upcomingBookings = Appointment::where('service_id','=',$service->id)->upcoming()->get();

        foreach ($upcomingBookings as $booking) {
            $booking->user->notify(
                new BookingCancellationNotification($booking,'admin')
            );
            $booking->delete();
        }

        $service->update([
            'is_visible' => 0
        ]);

        $service->delete();
        return redirect()->route('services.show',$service)->with('success', $service->name . " has been deleted successfully!");
    }

    public function restore(Service $service)
    {
        $response = Gate::inspect('restore',$service);
        
        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        $service->restore();
        return redirect()->route('services.show',$service)->with('success',$service->name . " has been restored successfully!");
    }
}
