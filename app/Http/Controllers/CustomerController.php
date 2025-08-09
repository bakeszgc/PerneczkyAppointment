<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
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

    public function show(Request $request, User $customer)
    {
        $request->validate([
            'showProfile' => 'nullable|boolean',
            'showPicture' => 'nullable|boolean',
            'showPassword' => 'nullable|boolean',
            'showDestroy' => 'nullable|boolean'
        ]);

        $showProfile = $request->showProfile ?? true;
        $showPicture = $request->showPicture ?? false;
        $showPassword = $request->showPassword ?? false;
        $showDestroy = $request->showDestroy ?? false;
        $showBookings = $request->showBookings ?? false;

        $sumOfBookings = [
            'previous' => [
                'count' => Appointment::userFilter($customer)->previous()->count(),
                'income' => Appointment::userFilter($customer)->previous()->sum('price')
            ],
            'upcoming' => [
                'count' => Appointment::userFilter($customer)->upcoming()->count(),
                'income' => Appointment::userFilter($customer)->upcoming()->sum('price')
            ],
            'cancelled' => [
                'count' => Appointment::onlyTrashed()->userFilter($customer)->count(),
                'income' => Appointment::onlyTrashed()->userFilter($customer)->sum('price')
            ]
        ];

        return view('user.show',[
            'user' => $customer,
            'showPassword' => $showPassword,
            'showProfile' => $showProfile,
            'showPicture' => $showPicture,
            'showDestroy' => $showDestroy,
            'showBookings' => $showBookings,
            'sumOfBookings' => $sumOfBookings,
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
