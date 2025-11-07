<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Barber;
use App\Models\Service;
use App\Models\Appointment;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Rules\ValidAppointmentTime;
use App\Notifications\BookingUpdateNotification;
use App\Notifications\BookingCancellationNotification;
use App\Notifications\BookingConfirmationNotification;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::barberFilter(auth()->user()->barber)->withoutTimeOffs()->withTrashed()->orderByDesc('id')->paginate(10);

        $calAppointments = Appointment::with('user')->get();
        $barbers = Barber::with('user')->get();
     
        return view('appointment.index',[
            'appointments' => $appointments,
            'calAppointments' => $calAppointments,
            'barbers' => $barbers,
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
        return redirect()->route('appointments.create.service');
    }

    public function createService(Request $request)
    {
        $request->validate([
            'service_id' => 'nullable|integer|gt:1|exists:services,id'
        ]);

        $services = Service::withoutTimeoff()->get();
        return view('my-appointment.create_barber_service',[
            'services' => $services,
            'view' => 'barber'
        ]);
    }

    public function createDate(Request $request)
    {
        $request->validate([
            'service_id' => ['required','integer','exists:services,id','gt:1'],
        ]);

        $barber = auth()->user()->barber;
        $service = Service::find($request->service_id);

        $availableSlotsByDate = Appointment::getFreeTimeSlots($barber,$service);

        return view('my-appointment.create_date',[
            'availableSlotsByDate' => $availableSlotsByDate,
            'barber' => $barber,
            'service' => $service,
            'view' => 'barber'
        ]);
    }

    public function createCustomer(Request $request) {

        $request->validate([
            'query' => 'nullable|string|max:255',
            'service_id' => ['required','integer','exists:services,id','gt:1'],
            'date' => ['required','date','after_or_equal:now','date_format:Y-m-d G:i',new ValidAppointmentTime],
            'comment' => ['nullable','string']
        ]);
        
        $query = $request->input('query');

        $users = User::registered()->where('id','!=',auth()->user()->id)
        ->when($query, function ($q) use ($query) {
            $q->where('first_name','like',"%$query%")
            ->orWhere('last_name','like',"%$query%")
            ->orWhere('email','like',"%$query%")
            ->orWhere('tel_number','like',"%$query%");
        })->orderBy('first_name')->paginate(10);

        return view('appointment.create',[
            'users' => $users,
            'service' => Service::find($request->service_id)
        ]);
    }

    public function createConfirm(Request $request) {
        $request->validate([
            'date' => ['required','date','after_or_equal:now','date_format:Y-m-d G:i',new ValidAppointmentTime],
            'user_id' => ['nullable','integer','exists:users,id'],
            'service_id' => ['required','exists:services,id','gt:1'],
            'comment' => ['nullable','string']
        ]);

        $startTime = Carbon::parse($request->date);

        $data = [
            'barber' => auth()->user()->barber,
            'service' => Service::find($request->service_id),
            'startTime' => $startTime,
            'comment' => $request->comment,
            'view' => 'barber'
        ];

        if ($request->has('user_id')) {
            $data['user'] = User::find($request->user_id);
        }

        return view('my-appointment.create_confirm', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => ['required','date','after_or_equal:now','date_format:Y-m-d G:i:s',new ValidAppointmentTime],
            'user_id' => ['nullable','integer','exists:users,id'],
            'service_id' => ['required','exists:services,id','gt:1'],
            'comment' => ['nullable','string'],
            'first_name' => ['nullable','string','min:1'],
            'email' => ['nullable','email','min:1'],
            'policy_checkbox' => ['required'],
            'confirmation_checkbox' => ['required']
        ]);

        // HANDLING AUTHLESS CASES
        if ($request->has(['first_name']) && !request()->has('user_id')) {
            $email = $request->email;
            $firstName = $request->first_name;

            if ($email) {
                $users = User::whereEmail($email)->get();

                if ($users->count() == 1) {
                    $user = $users->first();
                    if (!isset($user->last_name)) {
                        if ($firstName != $user->first_name) {
                            $user->update([
                                'first_name' => $firstName
                            ]);
                        }
                    } else {
                        return redirect()->back()->with('error',"This email address (" . $email . ") belongs to an already registered account. Please choose it on the Customers page or use a different email address.");
                    }
                } else {
                    $user = User::create([
                        'first_name' => $request->first_name,
                        'email' => $email,
                        'is_admin' => false
                    ]);
                }
            } else {
                $user = User::create([
                    'first_name' => $request->first_name,
                    'is_admin' => false
                ]);
            }
        } else {
            $user = User::find($request->user_id);
        }

        $app_start_time = Carbon::parse($request->date);
        $service = Service::findOrFail($request->service_id);
        $duration = $service->duration;
        $app_end_time = $app_start_time->clone()->addMinutes($duration);
        $barber = auth()->user()->barber;

        if (!Appointment::checkAppointmentClashes($app_start_time,$app_end_time,$barber)) {
            return redirect()->route('appointments.create.date',['service_id' => $service->id, 'comment' => $request->comment])->with('error','You have another bookings clashing with the selected timeslot. Please choose another one!');
        }

        $appointment = Appointment::create([
            'user_id' => $user->id,
            'barber_id' => $barber->id,
            'service_id' => $service->id,
            'app_start_time' => $app_start_time,
            'app_end_time' => $app_end_time,
            'price' => $service->price,
            'comment' => $request->comment,
        ]);

        if ($user->email) {
            $appointment->user->notify(
                new BookingConfirmationNotification($appointment)
            );
        }

        return redirect()->route('appointments.show',['appointment' =>  $appointment])->with('success','New booking has been created successfully!');
    }
    
    public function show(Appointment $appointment)
    {
        $response = Gate::inspect('view', $appointment);
        if ($response->denied()) {
            return redirect()->route('appointments.index')->with('error',$response->message());
        }
        
        if (Gate::allows('isTimeOff',$appointment)) {
            return redirect()->route('time-offs.show',$appointment);
        }

        $upcoming = Appointment::userFilter($appointment->user)->upcoming()->count();
        $previous = Appointment::userFilter($appointment->user)->previous()->count();
        $cancelled = Appointment::onlyTrashed()->userFilter($appointment->user)->count();

        $barber = Appointment::select('barber_id',DB::raw('COUNT(barber_id) as selection_count'))->userFilter($appointment->user)->groupBy('barber_id')->orderByDesc('selection_count')->first();

        $favBarber = Barber::withTrashed()->find($barber->barber_id);
        $numBarber = $barber->selection_count;

        $service = Appointment::select('service_id',DB::raw('COUNT(service_id) as selection_count'))->userFilter($appointment->user)->groupBy('service_id')->orderByDesc('selection_count')->first();

        $favService = Service::withTrashed()->find($service->service_id);
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
        $response = Gate::inspect('update',$appointment);
        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        if (Gate::allows('isTimeOff',$appointment)) {
            return redirect()->route('time-offs.edit',$appointment);
        }

        $appointments = Appointment::with('user')->get();
        $services = Service::withoutTimeoff()->get();
        $barbers = Barber::all();

        return view('appointment.edit', [
            'appointment' => $appointment,
            'services' => $services,
            'appointments' => $appointments,
            'barbers' => $barbers
        ]);
    }
    
    public function update(Request $request, Appointment $appointment)
    {
        $response = Gate::inspect('update',$appointment);
        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        if (Gate::allows('isTimeOff',$appointment)) {
            return redirect()->route('time-offs.edit',$appointment);
        }

        $request->validate([
            'service' => 'required|integer|exists:services,id|gt:1',
            'barber' => 'required|integer|exists:barbers,id',
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
        $barber = Barber::find($request->barber);

        if ($app_start_time >= $app_end_time) {
            return redirect()->route('appointments.edit',$appointment)->with('error',"The booking's ending time has to be later than its starting time");
        }

        if (!Appointment::checkAppointmentClashes($app_start_time,$app_end_time,$barber,$appointment)) {
            return redirect()->route('appointments.edit',$appointment)->with('error','You have another bookings clashing with the selected timeslot. Please choose another one!');
        }

        $oldAppointment = $appointment->only([
            'barber_id',
            'service_id',
            'comment',
            'price',
            'app_start_time',
            'app_end_time'
        ]);

        $appointment->update([
            'service_id' => $request->service,
            'barber_id' => $barber->id,
            'comment' => $request->comment,
            'price' => $request->price,
            'app_start_time' => $app_start_time,
            'app_end_time' => $app_end_time
        ]);

        $appointment->user->notify(
            new BookingUpdateNotification($oldAppointment,$appointment,updatedBy: Barber::find($oldAppointment['barber_id']))
        );

        if (auth()->user()->barber->id != $barber->id) {
            return redirect()->route('appointments.index')->with('success',"Your booking has been assigned to " . $barber->getName() . " successfully!");
        } else {
            return redirect()->route('appointments.show',['appointment' => $appointment])->with('success','Booking has been updated successfully!');
        }
    }

    public function destroy(Appointment $appointment)
    {
        $response = Gate::inspect('delete',$appointment);
        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        if (Gate::allows('isTimeOff',$appointment)) {
            return redirect()->route('time-offs.show',$appointment);
        }

        $appointment->user->notify(
            new BookingCancellationNotification($appointment,Barber::find($appointment->barber_id))
        );
        $appointment->delete();
        return redirect()->route('appointments.show',$appointment)
            ->with('success','Appointment has been cancelled successfully! Be sure to set up a new booking with your client!');
    }
}
