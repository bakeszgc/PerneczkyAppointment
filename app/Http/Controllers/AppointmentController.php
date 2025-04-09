<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\User;
use App\Models\Service;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function create(Request $request)
    {
        $query = $request->input('query');

        $users = User::query()
        ->when($query, function ($q) use ($query) {
            $q->where('first_name','like',"%$query%")
            ->orWhere('last_name','like',"%$query%")
            ->orWhere('email','like',"%$query%")
            ->orWhere('tel_number','like',"%$query%");
        })->paginate(10);

        return view('appointment.create',['users' => $users]);
    }

    public function createService(Request $request)
    {
        if (!$request->user_id) {
            return redirect()->route('appointments.create')->with('error','You need to select a customer first!');
        }
        $services = Service::all();
        return view('appointment.create_service',['services' => $services]);
    }

    public function createDate(Request $request)
    {

    }

    public function store(Request $request)
    {
        //
    }
    
    public function show(Appointment $appointment)
    {
        $upcoming = Appointment::where('user_id','=',$appointment->user_id)->where('app_start_time','>=',now())->count();
        $previous = Appointment::where('user_id','=',$appointment->user_id)->where('app_start_time','<=',now())->count();
        $cancelled = Appointment::onlyTrashed()->where('user_id','=',$appointment->user_id)->count();

        $barber = Appointment::select('barber_id',DB::raw('COUNT(barber_id) as selection_count'))->where('user_id','=',$appointment->user_id)->groupBy('barber_id')->orderByDesc('selection_count')->first();

        $favBarber = Barber::find($barber->barber_id);
        $numBarber = $barber->selection_count;

        $service = Appointment::select('service_id',DB::raw('COUNT(service_id) as selection_count'))->where('user_id','=',$appointment->user_id)->groupBy('service_id')->orderByDesc('selection_count')->first();

        $favService = Service::find($service->service_id);
        $numService = $service->selection_count;

        return view('appointment.show',[
            'appointment' => $appointment,
            'upcoming' => $upcoming,
            'previous' => $previous,
            'cancelled' => $cancelled,
            'favBarber' => $favBarber,
            'numBarber' => $numBarber,
            'favService' => $favService,
            'numService' => $numService
        ]);
    }
    
    public function edit(Appointment $appointment)
    {
        if ($appointment->barber->id != auth()->user()->barber->id) {
            return redirect()->route('appointments.index')->with('error',"You can't edit other barbers' bookings.");
        } elseif ($appointment->app_start_time <= now()) {
            return redirect()->route('appointments.show',$appointment)->with('error',"You can't edit bookings from the past.");
        } elseif ($appointment->deleted_at) {
            return redirect()->route('appointments.show',$appointment)->with('error',"You can't edit cancelled bookings.");
        }

        $previousAppointment = Appointment::where('barber_id','=',auth()->user()->barber->id)->where('app_end_time','<=',$appointment->app_start_time)->orderByDesc('app_end_time')->first();

        $nextAppointment = Appointment::where('barber_id','=',auth()->user()->barber->id)->where('app_start_time','>=',$appointment->app_end_time)->orderBy('app_start_time')->first();

        // can't edit deleted or previous records
        return view('appointment.edit', [
            'appointment' => $appointment,
            'services' => Service::all(),
            'previous' => $previousAppointment,
            'next' => $nextAppointment
        ]);
    }
    
    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'service' => 'required',
            'price' => 'required|integer|min:0',
            'app_start_date' => ['required','date','after_or_equal:today'],
            'app_start_hour' => ['required','integer','between:10,19'],
            'app_start_minute' => 'required|integer|multiple_of:15',
            'app_end_date' => ['required','date','after_or_equal:app_start_date'],
            'app_end_hour' => ['required','between:10,19','integer','gte:app_start_hour'],
            'app_end_minute' => 'required|integer|multiple_of:15',
            'comment' => 'nullable|max:255',
        ]);

        $app_start_time = Carbon::parse($request->app_start_date . " " . $request->app_start_hour . ":" . $request->app_start_minute);
        $app_end_time = Carbon::parse($request->app_end_date . " " . $request->app_end_hour . ":" . $request->app_end_minute);

        if ($app_start_time >= $app_end_time) {
            return redirect()->route('appointments.edit',$appointment)->with('error',"The booking's ending time has to be later than its starting time");
        }

        // időpont validation kell
        
        $newPrice = Service::find($request->service)->price;

        $appointment->update([
            'service_id' => $request->service,
            'comment' => $request->comment,
            'price' => $newPrice,
            'app_start_time' => $app_start_time,
            'app_end_time' => $app_end_time
        ]);

        return redirect()->route('appointments.show',['appointment' => $appointment])->with('success','Booking has been updated successfully!');
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
