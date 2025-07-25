<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index() {
        $barbers = Barber::limit(6)->get();
        $services = Service::all();

        $sumOfBookings = [
            'previous' => [
                'count' => Appointment::previous()->count(),
                'income' => Appointment::previous()->sum('price')
            ],
            'upcoming' => [
                'count' => Appointment::upcoming()->count(),
                'income' => Appointment::upcoming()->sum('price')
            ],
            'cancelled' => [
                'count' => Appointment::onlyTrashed()->count(),
                'income' => Appointment::onlyTrashed()->sum('price')
            ]
        ];

        return view('admin/admin',[
            'barbers' => $barbers,
            'services' => $services,
            'sumOfBookings' => $sumOfBookings
        ]);
    }
}
