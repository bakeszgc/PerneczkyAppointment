<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barber;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index() {
        $barbers = Barber::limit(6)->get();

        $services = Service::where('is_visible','=',1)->withCount(['appointments as appointments_count' => function ($q) {
            $q->withoutTrashed();
        }])->orderByDesc('appointments_count')->limit(5)->get();

        $users = User::hasStoredEmail()->withCount(['appointments as appointments_count' => function ($q) {
            $q->withoutTimeOffs()->withoutTrashed();
        }])->orderByDesc('appointments_count')->limit(5)->get();

        $sumOfBookings = Appointment::getSumOfBookings();
        $sumOfTimeOffs = Appointment::getSumOfTimeOffs();

        $allBarbers = Barber::with('user')->get();
        $calAppointments = Appointment::with('user')->get();

        return view('admin/admin',[
            'users' => $users,
            'barbers' => $barbers,
            'allBarbers' => $allBarbers,
            'services' => $services,
            'calAppointments' => $calAppointments,
            'sumOfBookings' => $sumOfBookings,
            'sumOfTimeOffs' => $sumOfTimeOffs
        ]);
    }
}
