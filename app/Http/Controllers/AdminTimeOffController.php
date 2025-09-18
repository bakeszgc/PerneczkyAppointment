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
        $appointments = Appointment::with('user')->get();
        $barbers = Barber::all();

        return view('appointment.edit',[
            'appointments' => $appointments,
            'barbers' => $barbers,
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

        $barber = Barber::find($request->barber);

        $time_off = Appointment::createTimeOff($request->only('app_start_date','app_start_hour','app_start_minute','app_end_date','app_end_hour','app_end_minute'),$barber);

        if (get_class($time_off) != "App\Models\Appointment") {
            return $time_off;
        } else {
            return redirect()->route('admin-time-offs.show',$time_off)->with('success', 'Time off for ' . $barber->getName() . ' has been created successfully!');
        }
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

        $appointments = Appointment::with('user')->get();

        return view('appointment.edit',[
            'appointments' => $appointments,
            'appointment' => $time_off,
            'view' => 'Time Off',
            'access' => 'admin',
            'action' => 'edit'
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

        $barber = $time_off->barber;

        $time_off = Appointment::createTimeOff($request->only('app_start_date','app_start_hour','app_start_minute','app_end_date','app_end_hour','app_end_minute'),$barber,$time_off);

        if (get_class($time_off) != "App\Models\Appointment") {
            return $time_off;
        } else {
            if (!isset($time_off->deleted_at)) {
                return redirect()->route('admin-time-offs.show',$time_off)->with('success',$time_off->barber->getName() . '\'s time off has been updated successfully!');
            } else {
                return redirect()->route('admin-time-offs.index',$time_off)->with('success',$time_off->barber->getName() . '\'s time off has been cancelled successfully!');
            }
        }
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
