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
        $services = Service::where('is_visible','=',1)->withCount('appointments')->orderByDesc('appointments_count')->limit(5)->get();
        $users = User::withCount(['appointments as appointments_count' => function ($q) {
            $q->where('service_id','!=',1);
        }])->orderByDesc('appointments_count')->limit(5)->get();

        $sumOfBookings = Appointment::getSumOfBookings();

        $sumOfTimeOffs = Appointment::getSumOfTimeOffs();

        return view('admin/admin',[
            'users' => $users,
            'barbers' => $barbers,
            'services' => $services,
            'sumOfBookings' => $sumOfBookings,
            'sumOfTimeOffs' => $sumOfTimeOffs
        ]);
    }
}
