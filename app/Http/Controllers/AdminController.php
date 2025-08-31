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
        $services = Service::where('is_visible','=',1)->get();
        $users = User::withCount('appointments')->orderByDesc('appointments_count')->limit(5)->get();

        $sumOfBookings = [
            'previous' => [
                'all time' => [
                    'count' => Appointment::withoutTimeOffs()->previous()->count(),
                    'value' => Appointment::withoutTimeOffs()->previous()->sum('price')
                ],
                'past month' => [
                    'count' => Appointment::withoutTimeOffs()->previous()->startLaterThan(now()->subMonth())->count(),
                    'value' => Appointment::withoutTimeOffs()->previous()->startLaterThan(now()->subMonth())->sum('price')
                ],
                'past week' => [
                    'count' => Appointment::withoutTimeOffs()->previous()->startLaterThan(now()->subWeek())->count(),
                    'value' => Appointment::withoutTimeOffs()->previous()->startLaterThan(now()->subWeek())->sum('price')
                ],
                'past day' => [
                    'count' => Appointment::withoutTimeOffs()->previous()->startLaterThan(now()->subDay())->count(),
                    'value' => Appointment::withoutTimeOffs()->previous()->startLaterThan(now()->subDay())->sum('price')
                ]
            ],
            'upcoming' => [
                'all time' => [
                    'count' => Appointment::withoutTimeOffs()->upcoming()->count(),
                    'value' => Appointment::withoutTimeOffs()->upcoming()->sum('price')
                ],
                'next month' => [
                    'count' => Appointment::withoutTimeOffs()->upcoming()->startEarlierThan(now()->addMonth())->count(),
                    'value' => Appointment::withoutTimeOffs()->upcoming()->startEarlierThan(now()->addMonth())->sum('price')
                ],
                'next week' => [
                    'count' => Appointment::withoutTimeOffs()->upcoming()->startEarlierThan(now()->addWeek())->count(),
                    'value' => Appointment::withoutTimeOffs()->upcoming()->startEarlierThan(now()->addWeek())->sum('price')
                ],
                'next day' => [
                    'count' => Appointment::withoutTimeOffs()->upcoming()->startEarlierThan(now()->addDay())->count(),
                    'value' => Appointment::withoutTimeOffs()->upcoming()->startEarlierThan(now()->addDay())->sum('price')
                ]
            ],
            'cancelled' => [
                'all time' => [
                    'count' => Appointment::withoutTimeOffs()->onlyTrashed()->count(),
                    'value' => Appointment::withoutTimeOffs()->onlyTrashed()->sum('price')
                ],
                'previous' => [
                    'count' => Appointment::withoutTimeOffs()->onlyTrashed()->previous()->count(),
                    'value' => Appointment::withoutTimeOffs()->onlyTrashed()->previous()->sum('price')
                ],
                'upcoming' => [
                    'count' => Appointment::withoutTimeOffs()->onlyTrashed()->upcoming()->count(),
                    'value' => Appointment::withoutTimeOffs()->onlyTrashed()->upcoming()->sum('price')
                ]
            ]
        ];

        $sumOfTimeOffs = [
            'previous' => [
                'all time' => [
                    'count' => Appointment::onlyTimeOffs()->previous()->count(),
                    'value' => Appointment::onlyTimeOffs()->previous()->get()->sum(function ($appointment) {
                        return $appointment->getDuration();
                    })
                ],
                'past month' => [
                    'count' => Appointment::onlyTimeOffs()->previous()->startLaterThan(now()->subMonth())->count(),
                    'value' => Appointment::onlyTimeOffs()->previous()->startLaterThan(now()->subMonth())->get()->sum(function ($appointment) {
                        return $appointment->getDuration();
                    })
                ],
                'past week' => [
                    'count' => Appointment::onlyTimeOffs()->previous()->startLaterThan(now()->subWeek())->count(),
                    'value' => Appointment::onlyTimeOffs()->previous()->startLaterThan(now()->subWeek())->get()->sum(function ($appointment) {
                        return $appointment->getDuration();
                    })
                ],
                'past day' => [
                    'count' => Appointment::onlyTimeOffs()->previous()->startLaterThan(now()->subDay())->count(),
                    'value' => Appointment::onlyTimeOffs()->previous()->startLaterThan(now()->subDay())->get()->sum(function ($appointment) {
                        return $appointment->getDuration();
                    })
                ]
            ],
            'upcoming' => [
                'all time' => [
                    'count' => Appointment::onlyTimeOffs()->upcoming()->count(),
                    'value' => Appointment::onlyTimeOffs()->upcoming()->get()->sum(function ($appointment) {
                        return $appointment->getDuration();
                    })
                ],
                'next month' => [
                    'count' => Appointment::onlyTimeOffs()->upcoming()->startEarlierThan(now()->addMonth())->count(),
                    'value' => Appointment::onlyTimeOffs()->upcoming()->startEarlierThan(now()->addMonth())->get()->sum(function ($appointment) {
                        return $appointment->getDuration();
                    })
                ],
                'next week' => [
                    'count' => Appointment::onlyTimeOffs()->upcoming()->startEarlierThan(now()->addWeek())->count(),
                    'value' => Appointment::onlyTimeOffs()->upcoming()->startEarlierThan(now()->addWeek())->get()->sum(function ($appointment) {
                        return $appointment->getDuration();
                    })
                ],
                'next day' => [
                    'count' => Appointment::onlyTimeOffs()->upcoming()->startEarlierThan(now()->addDay())->count(),
                    'value' => Appointment::onlyTimeOffs()->upcoming()->startEarlierThan(now()->addDay())->get()->sum(function ($appointment) {
                        return $appointment->getDuration();
                    })
                ]
            ]
        ];

        return view('admin/admin',[
            'users' => $users,
            'barbers' => $barbers,
            'services' => $services,
            'sumOfBookings' => $sumOfBookings,
            'sumOfTimeOffs' => $sumOfTimeOffs
        ]);
    }
}
