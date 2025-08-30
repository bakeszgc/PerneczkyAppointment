<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\Barber;
use App\Models\User;
use App\Models\Service;

class AdminTimeOffController extends Controller
{
    public function index(Request $request) {
        $request->validate([
            'barber' => 'integer|exists:barbers,id',
            'from_app_start_date' => 'date',
            'from_app_start_hour' => 'int|between:10,20',
            'from_app_start_minute' => 'int|between:0,45|multiple_of:15',
            'to_app_start_date' => ['date', function ($attribute, $value, $fail) use ($request) {
                if ($request->filled('from_app_start_date') && $value < $request->input('from_app_start_date')) {
                    $fail('The end date must be after or equal to the start date.');
                }
            }],
            'to_app_start_hour' => 'int|between:10,20',
            'to_app_start_minute' => 'int|between:0,45|multiple_of:15'
        ]);

        $fromAppStartTime = null;
        $toAppStartTime = null;

        if ($request->has('from_app_start_date')) {
            $fromAppStartTime = new Carbon($request->from_app_start_date . ' ' . ($request->from_app_start_hour ?? '10') . ':' . ($request->from_app_start_minute ?? '00'));
        }

        if ($request->has('to_app_start_date')) {
            $toAppStartTime = new Carbon($request->to_app_start_date . ' ' . ($request->to_app_start_hour ?? '10') . ':' . ($request->to_app_start_minute ?? '00'));
        }

        if ($fromAppStartTime != null && $toAppStartTime != null) {
            if ($toAppStartTime < $fromAppStartTime) {
                return redirect()->back()->with('error','The end time must be after or equal to the start date!');
            }
        }

        $appointments = Appointment::onlyTimeOffs()
            ->when($request->barber, function ($q) use ($request) {
                $barber = Barber::withTrashed()->find($request->barber);
                $q->barberFilter($barber);
            })
            ->when($request->from_app_start_date || $request->to_app_start_date || $request->time_window, function ($q) use ($request, $fromAppStartTime, $toAppStartTime) {
                switch ($request->time_window) {
                    case 'upcoming':
                        $q->upcoming();
                        break;
                    case 'previous':
                        $q->previous();
                        break;
                    default:
                        if ($request->from_app_start_date) {  
                            $q->startLaterThan($fromAppStartTime);
                        }
                        
                        if ($request->to_app_start_date) {
                            $toAppStartTime = new Carbon($request->to_app_start_date . ' ' . ($request->to_app_start_hour ?? '20') . ':' . ($request->to_app_start_minute ?? '00'));
                            
                            $q->startEarlierThan($toAppStartTime);
                        }
                }
                
            })->orderBy('app_start_time')->paginate(10);

        $barbers = Barber::withTrashed()->get();
        $services = Service::withTrashed()->get();
        $users = User::withTrashed()->orderBy('first_name')->get();
     
        return view('admin.appointment.index',[
            'appointments' => $appointments,
            'barbers' => $barbers,
            'services' => $services,
            'users' => $users,
            'view' => 'Time Off'
        ]);
    }

    public function create()
    {
        return view('appointment.edit',[
            'barbers' => Barber::all(),
            'view' => 'Time Off',
            'action' => 'create',
            'access' => 'admin'
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
            'barber' => 'required|exists:barbers,id',
            'full_day' => 'nullable'
        ]);

        // SETTING START AND END TIMES TO 10 AND 20 IF THEY ARE NOT SENT THROUGH REQUEST
        $app_start_time = Carbon::parse($request->app_start_date . " " . ($request->app_start_hour ?? 10) . ":" . ($request->app_start_minute ?? 00));
        $app_end_time = Carbon::parse($request->app_end_date . " " . ($request->app_end_hour ?? 20) . ":" . ($request->app_end_minute ?? 00));

        // APP START TIME IS LATER THAN APP END TIME
        if ($app_start_time > $app_end_time) {
            return redirect()->route('admin-time-offs.create')->with('error',"The ending time of your time off has to be later than its starting time");
        }

        // APP START TIME OR APP END TIME IN THE PAST
        if ($app_start_time < now()) {
            return redirect()->route('admin-time-offs.create')->with('error',"The starting time of your time off cannot be in the past!");
        } elseif ($app_end_time < now()) {
            return redirect()->route('admin-time-offs.create')->with('error',"The ending time of your time off cannot be in the past!");
        }

        $barber = Barber::find($request->barber);

        if (!Appointment::checkAppointmentClashes($app_start_time,$app_end_time,$barber)) {
            return redirect()->route('admin-time-offs.create')->with('error','You have bookings clashing with the selected timeframe.');
        }

        // CREATING A TIME OFF FOR EACH DAY
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

        return redirect()->route('admin-time-offs.show',$time_off)->with('success', 'Time off for ' . $barber->getName() . ' has been created successfully!');
    }

    public function show(Appointment $time_off)
    {
        if ($time_off->service_id !== 1) {
            return redirect()->route('bookings.show',$time_off);
        }

        return view('time-off.show',[
            'appointment' => $time_off,
            'access' => 'admin'
        ]);
    }

    public function edit(Appointment $time_off)
    {
        if ($time_off->deleted_at) {
            return redirect()->route('admin-time-offs.show',$time_off)->with('error',"You can't edit cancelled time offs!");
        } elseif ($time_off->app_start_time <= now()) {
            return redirect()->route('admin-time-offs.show',$time_off)->with('error',"You can't edit time offs from the past!");
        } elseif ($time_off->service_id !== 1) {
            return redirect()->route('bookings.edit',$time_off);
        }

        // PREVIOUS AND UPCOMING APPOINTMENTS
        $previousAppointment = Appointment::barberFilter($time_off->barber)->endEarlierThan(Carbon::parse($time_off->app_start_time))->orderByDesc('app_end_time')->first();

        $nextAppointment = Appointment::barberFilter($time_off->barber)->startLaterThan(Carbon::parse($time_off->app_end_time))->orderBy('app_start_time')->first();

        return view('appointment.edit',[
            'appointment' => $time_off,
            'previous' => $previousAppointment,
            'next' => $nextAppointment,
            'view' => 'Time Off',
            'access' => 'admin'
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

        if ($time_off->deleted_at) {
            return redirect()->route('admin-time-offs.show',$time_off)->with('error',"You can't edit cancelled time offs!");
        } elseif ($time_off->app_start_time <= now()) {
            return redirect()->route('admin-time-offs.show',$time_off)->with('error',"You can't edit time offs from the past!");
        } elseif ($time_off->service_id !== 1) {
            return redirect()->route('bookings.show',$time_off);
        }

        // setting start and end times, if hour and minute are not sent through then considering as a full day
        $app_start_time = Carbon::parse($request->app_start_date . " " . ($request->app_start_hour ?? 10) . ":" . ($request->app_start_minute ?? 00));
        $app_end_time = Carbon::parse($request->app_end_date . " " . ($request->app_end_hour ?? 20) . ":" . ($request->app_end_minute ?? 00));

        // handling when app start time is later than app end time
        if ($app_start_time > $app_end_time) {
            return redirect()->route('admin-time-offs.edit',$time_off)->with('error',"The ending time of your time off has to be later than its starting time");
        }

        // handling when app start time or app end time is in the past
        if ($app_start_time < now()) {
            return redirect()->route('admin-time-offs.edit',$time_off)->with('error',"The starting time of your time off cannot be in the past!");
        } elseif ($app_end_time < now()) {
            return redirect()->route('admin-time-offs.edit',$time_off)->with('error',"The ending time of your time off cannot be in the past!");
        }

        $barber = $time_off->barber;

        if (!Appointment::checkAppointmentClashes($app_start_time,$app_end_time,$barber,$time_off)) {
            return redirect()->route('admin-time-offs.edit',$time_off)->with('error','You have bookings clashing with the selected timeframe.');
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

        return redirect()->route('admin-time-offs.show',$time_off)->with('success','Time off has been updated successfully!');
    }

    public function destroy(Appointment $time_off)
    {
        if ($time_off->app_start_time < now()) {
            return redirect()->back()->with('error',"You can't cancel a previous time off!");
        } elseif (isset($time_off->deleted_at)) {
            return redirect()->back()->with('error',"You can't cancel an already cancelled time off!");
        } elseif ($time_off->service_id !== 1) {
            return redirect()->route('bookings.show',$time_off)->with('error', "You can't cancel a booking as a time off. Please try again here!");
        }

        $time_off->delete();
        return redirect()->route('admin-time-offs.index')->with('success',$time_off->barber->getName() . '\'s time off has been cancelled successfully!');
    }
}
