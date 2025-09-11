<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Barber;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Rules\ValidAppointmentTime;
use App\Notifications\BookingCancellationNotification;
use App\Notifications\BookingConfirmationNotification;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::barberFilter(auth()->user()->barber)->withoutTimeOffs()->withTrashed()->latest()->paginate(10);

        $calAppointments = Appointment::barberFilter(auth()->user()->barber)->whereBetween('app_start_time',[date("Y-m-d", strtotime('monday this week')),date("Y-m-d", strtotime('monday next week'))])->get();
     
        return view('appointment.index',[
            'appointments' => $appointments,
            'calAppointments' => $calAppointments,
            'type' => 'All'
        ]);
    }

    public function indexUpcoming() {
        $upcomingAppointments = Appointment::upcoming()->barberFilter(auth()->user()->barber)
        ->withoutTimeOffs()->paginate(10);
        
        return view('appointment.index',[
            'appointments' => $upcomingAppointments,
            'type' => 'Upcoming'
        ]);
    }

    public function indexPrevious() {
        $previousAppointments = Appointment::previous()->barberFilter(auth()->user()->barber)
        ->withoutTimeOffs()->paginate(10);
        
        return view('appointment.index',[
            'appointments' => $previousAppointments,
            'type' => 'Previous'
        ]);
    }

    public function indexCancelled() {
        $cancelledAppointments = Appointment::onlyTrashed()->barberFilter(auth()->user()->barber)
        ->withoutTimeOffs()->orderBy('app_start_time','desc')->paginate(10);

        return view('appointment.index',[
            'appointments' => $cancelledAppointments,
            'type' => 'Cancelled'
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string|max:255'
        ]);
        
        $query = $request->input('query');

        $users = User::where('id','!=',auth()->user()->id)
        ->when($query, function ($q) use ($query) {
            $q->where('first_name','like',"%$query%")
            ->orWhere('last_name','like',"%$query%")
            ->orWhere('email','like',"%$query%")
            ->orWhere('tel_number','like',"%$query%");
        })->orderBy('first_name')->paginate(10);

        return view('appointment.create',['users' => $users]);
    }

    public function createService(Request $request)
    {
        // redirect ha nincs a user_id az adatbázisban vagy pont a barber a user_id
        if (!User::find($request->user_id) || $request->user_id === auth()->user()->id) {
            return redirect()->route('appointments.create')->with('error','Please select a valid user from the list!');
        }

        $services = Service::where('is_visible','=',1)->get();
        return view('my-appointment.create_barber_service',[
            'services' => $services,
            'view' => 'barber'
        ]);
    }

    public function createDate(Request $request)
    {
        $request->validate([
            'service_id' => ['required','integer','exists:services,id','gt:1'],
            'user_id' => ['required','integer','exists:users,id',function($attribute, $value, $fail) {
                if ($value == auth()->user()->id) {
                    $fail("You can't select yourself as a customer.");
                }
            }]
        ]);

        $barber = auth()->user()->barber;
        $service = Service::find($request->service_id);
        $user = User::find($request->user_id);

        $availableSlotsByDate = Appointment::getFreeTimeSlots($barber,$service);

        return view('my-appointment.create_date',[
            'availableSlotsByDate' => $availableSlotsByDate,
            'barber' => $barber,
            'service' => $service,
            'user' => $user,
            'view' => 'barber'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => ['required','date','after_or_equal:now','date_format:Y-m-d G:i',new ValidAppointmentTime],
            'user_id' => ['required','exists:users,id'],
            'service_id' => ['required','exists:services,id'],
            'comment' => ['nullable','string']
        ]);

        $app_start_time = Carbon::parse($request->date);
        $duration = Service::findOrFail($request->service_id)->duration;
        $app_end_time = $app_start_time->clone()->addMinutes($duration);
        $barber = auth()->user()->barber;

        if (!Appointment::checkAppointmentClashes($app_start_time,$app_end_time,$barber)) {
            return redirect()->route('appointments.create.date',['user_id' => $request->user_id, 'service_id' => $request->service_id])->with('error','You have another bookings clashing with the selected timeslot. Please choose another one!');
        }

        $appointment = Appointment::create([
            'user_id' => $request->user_id,
            'barber_id' => auth()->user()->barber->id,
            'service_id' => $request->service_id,
            'app_start_time' => $app_start_time,
            'app_end_time' => $app_end_time,
            'price' => Service::findOrFail($request->service_id)->price,
            'comment' => $request->comment,
        ]);

        $appointment->user->notify(
            new BookingConfirmationNotification($appointment)
        );

        return redirect()->route('appointments.show',['appointment' =>  $appointment])->with('success','New booking has been created successfully!');
    }
    
    public function show(Appointment $appointment)
    {
        if ($appointment->barber_id !== auth()->user()->barber->id) {
            return redirect()->route('appointments.index')->with('error',"You can't view other barbers' bookings.");
        } elseif ($appointment->service_id == 1) {
            return redirect()->route('time-offs.show',$appointment);
        }

        $upcoming = Appointment::userFilter($appointment->user)->upcoming()->count();
        $previous = Appointment::userFilter($appointment->user)->previous()->count();
        $cancelled = Appointment::onlyTrashed()->userFilter($appointment->user)->count();

        $barber = Appointment::select('barber_id',DB::raw('COUNT(barber_id) as selection_count'))->userFilter($appointment->user)->groupBy('barber_id')->orderByDesc('selection_count')->first();

        $favBarber = Barber::find($barber->barber_id);
        $numBarber = $barber->selection_count;

        $service = Appointment::select('service_id',DB::raw('COUNT(service_id) as selection_count'))->userFilter($appointment->user)->groupBy('service_id')->orderByDesc('selection_count')->first();

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
        } elseif ($appointment->service_id == 1) {
            return redirect()->route('time-offs.edit',$appointment);
        }

        $appointments = Appointment::barberFilter(auth()->user()->barber)->with('user')->get();
        $services = Service::where('is_visible','=',1)->get();

        return view('appointment.edit', [
            'appointment' => $appointment,
            'services' => $services,
            'appointments' => $appointments
        ]);
    }
    
    public function update(Request $request, Appointment $appointment)
    {
        if ($appointment->barber->id != auth()->user()->barber->id) {
            return redirect()->route('appointments.index')->with('error',"You can't edit other barbers' bookings.");
        } elseif ($appointment->app_start_time <= now()) {
            return redirect()->route('appointments.show',$appointment)->with('error',"You can't edit bookings from the past.");
        } elseif ($appointment->deleted_at) {
            return redirect()->route('appointments.show',$appointment)->with('error',"You can't edit cancelled bookings.");
        } elseif ($appointment->service_id == 1) {
            return redirect()->route('time-offs.edit',$appointment);
        }

        $request->validate([
            'service' => 'required',
            'price' => 'required|integer|min:0',
            'app_start_date' => ['required','date','after_or_equal:today'],
            'app_start_hour' => ['required','integer','between:10,19'],
            'app_start_minute' => 'required|integer|multiple_of:15',
            'app_end_date' => ['required','date','after_or_equal:app_start_date'],
            'app_end_hour' => ['required','between:10,21','integer','gte:app_start_hour'],
            'app_end_minute' => 'required|integer|multiple_of:15',
            'comment' => 'nullable|max:255',
        ]);

        $app_start_time = Carbon::parse($request->app_start_date . " " . $request->app_start_hour . ":" . $request->app_start_minute);
        $app_end_time = Carbon::parse($request->app_end_date . " " . $request->app_end_hour . ":" . $request->app_end_minute);
        $barber = auth()->user()->barber;

        if ($app_start_time >= $app_end_time) {
            return redirect()->route('appointments.edit',$appointment)->with('error',"The booking's ending time has to be later than its starting time");
        }

        if (!Appointment::checkAppointmentClashes($app_start_time,$app_end_time,$barber,$appointment)) {
            return redirect()->route('appointments.edit',$appointment)->with('error','You have another bookings clashing with the selected timeslot. Please choose another one!');
        }

        $appointment->update([
            'service_id' => $request->service,
            'comment' => $request->comment,
            'price' => $request->price,
            'app_start_time' => $app_start_time,
            'app_end_time' => $app_end_time
        ]);

        return redirect()->route('appointments.show',['appointment' => $appointment])->with('success','Booking has been updated successfully!');
    }

    public function destroy(Appointment $appointment)
    {
        if ($appointment->app_start_time < now()) {
            return redirect()->back()->with('error',"You can't cancel a previous booking!");
        } elseif ($appointment->barber_id != auth()->user()->barber->id) {
            return redirect()->back()->with('error',"You can't cancel other barbers' appointments!");
        } elseif (isset($appointment->deleted_at)) {
            return redirect()->back()->with('error',"You can't cancel an already cancelled appointment!");
        } elseif ($appointment->service_id == 1) {
            return redirect()->route('time-offs.show',$appointment)->with('error', "You can't cancel a time off as a booking. Please try again here!");
        }

        $appointment->user->notify(
            new BookingCancellationNotification($appointment,Barber::find($appointment->barber_id))
        );
        $appointment->delete();
        return redirect()->route('appointments.index')
            ->with('success','Appointment has been cancelled successfully! Be sure to set up a new booking with your client!');
    }
}
