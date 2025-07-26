<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Barber;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminAppointmentController extends Controller
{
    public function index(Request $request) {
        $request->validate([
            'barber' => 'integer|exists:barbers,id',
            'service' => 'integer|exists:services,id',
            'user' => 'integer|exists:users,id',
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


        $appointments = Appointment::withoutTimeOffs()
            ->when($request->cancelled, function ($q) use ($request) {
                switch ($request->cancelled) {
                    case 1:
                        $q->withTrashed();
                        break;
                    case 2:
                        $q->onlyTrashed();
                        break;
                    default:
                        $q;
                        break;
                }
            })
            ->when($request->barber, function ($q) use ($request) {
                $barber = Barber::find($request->barber);
                $q->barberFilter($barber);
            })
            ->when($request->service, function ($q) use ($request) {
                $service = Service::find($request->service);
                $q->serviceFilter($service);
            })
            ->when($request->user, function ($q) use ($request) {
                $user = User::find($request->user);
                $q->userFilter($user);
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
                
            })
            ->orderBy('app_start_time')->paginate(10);

        $barbers = Barber::where('is_visible','=',1)->get();
        $services = Service::where('is_visible','=',1)->get();
        $users = User::orderBy('first_name')->get();
     
        return view('admin.appointment.index',[
            'appointments' => $appointments,
            'barbers' => $barbers,
            'services' => $services,
            'users' => $users
        ]);
    }

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
    public function show(Appointment $booking)
    {
        $upcoming = Appointment::userFilter($booking->user)->upcoming()->count();
        $previous = Appointment::userFilter($booking->user)->previous()->count();
        $cancelled = Appointment::onlyTrashed()->userFilter($booking->user)->count();

        $barber = Appointment::select('barber_id',DB::raw('COUNT(barber_id) as selection_count'))->userFilter($booking->user)->groupBy('barber_id')->orderByDesc('selection_count')->first();

        $favBarber = Barber::find($barber->barber_id);
        $numBarber = $barber->selection_count;

        $service = Appointment::select('service_id',DB::raw('COUNT(service_id) as selection_count'))->userFilter($booking->user)->groupBy('service_id')->orderByDesc('selection_count')->first();

        $favService = Service::find($service->service_id);
        $numService = $service->selection_count;

        return view('appointment.show',[
            'appointment' => $booking,
            'upcoming' => $upcoming,
            'previous' => $previous,
            'cancelled' => $cancelled,
            'favBarber' => $favBarber,
            'numBarber' => $numBarber,
            'favService' => $favService,
            'numService' => $numService,
            'view' => 'admin'
        ]);
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
