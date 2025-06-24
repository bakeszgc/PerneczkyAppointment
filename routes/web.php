<?php

use App\Models\Appointment;
use App\Models\Barber;
use App\Models\Service;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PictureController;
use App\Http\Controllers\TimeOffController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MyAppointmentController;

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
    Route::put('users/{user}/update-password',[UserController::class,'updatePassword'])->name('users.update-password');
    Route::resource('users',UserController::class)->only('show','update','destroy');

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
    Route::get('my-appointments/create/selectBarberService',[MyAppointmentController::class,'createBarberService'])->name('my-appointments.create.barber.service');
    Route::get('my-appointments/create/selectDate',[MyAppointmentController::class,'createDate'])->name('my-appointments.create.date');

});

// BARBER ROUTES
Route::middleware('barber')->group(function() {

    // BARBER APPOINTMENTS
    Route::get('appointments/upcoming',[AppointmentController::class,'indexUpcoming'])->name('appointments.upcoming');
    Route::get('appointments/previous',[AppointmentController::class,'indexPrevious'])->name('appointments.previous');
    Route::get('appointments/cancelled',[AppointmentController::class,'indexCancelled'])->name('appointments.cancelled');

    Route::get('appointments/createService',[AppointmentController::class,'createService'])->name('appointments.create.service');
    Route::get('appointments/createDate',[AppointmentController::class,'createDate'])->name('appointments.create.date');

    Route::resource('appointments',AppointmentController::class);

    // BARBER TIME OFF
    Route::get('time-offs/upcoming', [TimeOffController::class,'indexUpcoming'])->name('time-offs.upcoming');
    Route::get('time-offs/previous', [TimeOffController::class,'indexPrevious'])->name('time-offs.previous');
    Route::resource('time-offs',TimeOffController::class);

    // BARBER PROFILE PICTURE
    Route::post('/upload-cropped',[PictureController::class,'uploadCropped'])->name('upload-cropped');
});

// DEV HOME
Route::get('/',function() {

    $barbers = Barber::all();
    $services = Service::where('id','!=',1)->get();

    return view('home',[
        'barbers' => $barbers,
        'services' => $services
    ]);
})->name('home');

// ADMIN - TEMP
Route::get('/admin', function() {
    $barbers = Barber::limit(6)->get();

    $services = Service::all();

    return view('admin',[
        'barbers' => $barbers,
        'services' => $services
    ]);

})->name('admin');