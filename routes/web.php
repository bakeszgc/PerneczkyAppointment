<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MyAppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Models\Appointment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

// AUTH
Route::get('login', [AuthController::class, 'create'])->name('login');
Route::resource('auth', AuthController::class)->only(['store']);

Route::get('register',[UserController::class,'create'])->name('register');
Route::resource('user',UserController::class)->only('store');

// AUTH REQUIRED ROUTES
Route::middleware('auth')->group(function(){

    // LOGOUT
    Route::delete('logout',[AuthController::class, 'destroy'])->name('logout');

    // USER
    Route::resource('users',UserController::class)->only('show','update');

    // EMAIL VERIFICATION
    Route::get('/email/verify', [AuthController::class,'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}',[AuthController::class,'verify'])->middleware('signed')->name('verification.verify');
    Route::post('/email/verification-notification',[AuthController::class,'send'])->name('verification.send');
    Route::get('/email/resend',[AuthController::class,'resend'])->name('verification.resend');

    // CUSTOMER INDEX APPOINTMENTS
    Route::get('my-appointments/previous',[MyAppointmentController::class,'indexPrevious'])->name('my-appointments.index.previous');
    Route::resource('my-appointments',MyAppointmentController::class)->except('edit','update');
});

// AUTH + VERIFIED ROUTES
Route::middleware(['auth','verified'])->group(function() {

    // CUSTOMER CREATE APPOINTMENTS
    Route::get('my-appointments/create/selectBarber',[MyAppointmentController::class,'createBarber'])->name('my-appointments.create.barber');
    Route::get('my-appointments/create/selectService',[MyAppointmentController::class,'createService'])->name('my-appointments.create.service');
    Route::get('my-appointments/create/selectDate',[MyAppointmentController::class,'createDate'])->name('my-appointments.create.date');

});

// BARBER ROUTES
Route::middleware('barber')->group(function() {

    // BARBER APPOINTMENTS
    Route::get('appointments/upcoming',[AppointmentController::class,'indexUpcoming'])->name('appointments.upcoming');
    Route::get('appointments/previous',[AppointmentController::class,'indexPrevious'])->name('appointments.previous');
    Route::get('appointments/cancelled',[AppointmentController::class,'indexCancelled'])->name('appointments.cancelled');
    Route::get('appointments/createService',[AppointmentController::class,'createService'])->name('appointments.createService');
    Route::resource('appointments',AppointmentController::class);

});


// DASHBOARD
Route::get('dashboard',function () {

    $upcomingAppointments = Appointment::where('app_start_time','>=',now('Europe/Budapest'))->orderBy('app_start_time')->limit(5)->get();

    $todayIncome = Appointment::whereDate('app_start_time',Carbon::today())->sum('price');

    $past7DaysIncome = Appointment::whereBetween('app_start_time',[
        Carbon::now('Europe/Budapest')->subDays(7),
        Carbon::now('Europe/Budapest')
    ])->sum('price');

    $past30DaysIncome = Appointment::whereBetween('app_start_time',[
        Carbon::now('Europe/Budapest')->subDays(30),
        Carbon::now('Europe/Budapest')
    ])->sum('price');

    return view('dashboard',[
        'upcomingAppointments' => $upcomingAppointments,
        'todayIncome' => $todayIncome,
        'past7DaysIncome' => $past7DaysIncome,
        'past30DaysIncome' => $past30DaysIncome
    ]);

})->name('dashboard');

// DEV HOME
Route::get('/',fn() => view('home'))->name('home');

