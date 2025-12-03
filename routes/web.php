<?php

use App\Http\Controllers\LanguageController;
use App\Models\User;
use App\Models\Barber;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BarberController;
use App\Http\Controllers\PictureController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TimeOffController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AdminTimeOffController;
use App\Http\Controllers\MyAppointmentController;
use App\Http\Controllers\AdminAppointmentController;

// SWITCH LANGUAGE
Route::get('change', [LanguageController::class,'change'])->name('lang.change');

// PASSWORD RESET
Route::get('/forgot-password',[AuthController::class, 'forgotPassword'])->name('password.request');
Route::post('/forgot-password',[AuthController::class,'sendPasswordResetEmail'])->name('password.email');
Route::get('/reset-password/{token}',[AuthController::class,'resetPassword'])->name('password.reset');
Route::post('/reset-password',[AuthController::class,'updatePassword'])->name('password.update');

// GUEST ROUTES
Route::middleware('guest')->group(function() {

    // AUTH
    Route::get('login', [AuthController::class, 'create'])->name('login');
    Route::resource('auth', AuthController::class)->only(['store']);

    // AUTH WITH GOOGLE
    Route::get('auth/{provider}/redirect', [AuthController::class,'authProviderRedirect'])->name('auth.redirect');
    Route::get('auth/{provider}/callback',[AuthController::class, 'socialAuth'])->name('auth.callback');

    // REGISTER
    Route::get('register',[UserController::class,'create'])->name('register');
    Route::post('register',[UserController::class,'store'])->name('user.store');

    // SUCCESSFUL APPOINTMENT
    Route::get('my-appointments/create/success',[MyAppointmentController::class,'createSuccess'])->name('my-appointments.create.success');
});

// APPOINTMENT BOOKING ROUTES FOR USERS AND GUESTS
Route::get('my-appointments/create/selectBarberService',[MyAppointmentController::class,'createBarberService'])->name('my-appointments.create.barber.service');
Route::get('my-appointments/create/earliest',[MyAppointmentController::class,'createGetEarliestBarber'])->name('my-appointments.create.earliest');
Route::get('my-appointments/create/selectDate',[MyAppointmentController::class,'createDate'])->name('my-appointments.create.date');
Route::get('my-appointments/create/confirm', [MyAppointmentController::class,'createConfirm'])->name('my-appointments.create.confirm');
Route::resource('my-appointments',MyAppointmentController::class)->only('create','store');


// AUTH REQUIRED ROUTES
Route::middleware('auth')->group(function(){

    // LOGOUT
    Route::delete('logout',[AuthController::class, 'destroy'])->name('logout');

    // USER
    Route::put('users/{user}/update-password',[UserController::class,'updatePassword'])->name('users.update-password');
    Route::put('users/{user}/update-mailing',[UserController::class,'updateMailing'])->name('users.update-mailing');
    Route::resource('users',UserController::class)->only('show','update');

    // EMAIL VERIFICATION
    Route::get('/email/verify', [AuthController::class,'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}',[AuthController::class,'verify'])->middleware('signed')->name('verification.verify');
    Route::post('/email/verification-notification',[AuthController::class,'send'])->name('verification.send');
    Route::get('/email/resend',[AuthController::class,'resend'])->name('verification.resend');

    // CUSTOMER APPOINTMENTS
    Route::get('my-appointments/previous',[MyAppointmentController::class,'indexPrevious'])->name('my-appointments.index.previous');
    Route::get('my-appointments/{my_appointment}/edit/selectBarberService',[MyAppointmentController::class,'editBarberService'])->name('my-appointments.edit.barber.service');
    Route::get('my-appointments/{my_appointment}/edit/earliest',[MyAppointmentController::class,'editGetEarliestBarber'])->name('my-appointments.edit.earliest');
    Route::get('my-appointments/{my_appointment}/edit/selectDate',[MyAppointmentController::class,'editDate'])->name('my-appointments.edit.date');
    Route::get('my-appointments/{my_appointment}/edit/confirm',[MyAppointmentController::class,'editConfirm'])->name('my-appointments.edit.confirm');
    Route::resource('my-appointments',MyAppointmentController::class)->except('create','store');
});

// BARBER ROUTES
Route::middleware(['auth','verified','barber'])->group(function() {

    // BARBER APPOINTMENTS
    Route::get('appointments/upcoming',[AppointmentController::class,'indexUpcoming'])->name('appointments.upcoming');
    Route::get('appointments/previous',[AppointmentController::class,'indexPrevious'])->name('appointments.previous');
    Route::get('appointments/cancelled',[AppointmentController::class,'indexCancelled'])->name('appointments.cancelled');

    Route::get('appointments/create/selectService',[AppointmentController::class,'createService'])->name('appointments.create.service');
    Route::get('appointments/create/selectDate',[AppointmentController::class,'createDate'])->name('appointments.create.date');
    Route::get('appointments/create/selectCustomer',[AppointmentController::class,'createCustomer'])->name('appointments.create.customer');
    Route::get('appointments/create/confirm',[AppointmentController::class,'createConfirm'])->name('appointments.create.confirm');

    Route::resource('appointments',AppointmentController::class)->withTrashed(['show']);

    // BARBER TIME OFF
    Route::get('time-offs/upcoming', [TimeOffController::class,'indexUpcoming'])->name('time-offs.upcoming');
    Route::get('time-offs/previous', [TimeOffController::class,'indexPrevious'])->name('time-offs.previous');
    Route::resource('time-offs',TimeOffController::class);

    // BARBER PROFILE PICTURE
    Route::post('/upload-cropped/{user}',[PictureController::class,'uploadCropped'])->name('upload-cropped');
});

// ADMIN ROUTES
Route::middleware(['auth','verified','admin'])->group(function() {

    // DASHBOARD
    Route::get('/admin', [AdminController::class,'index'])->name('admin');

    // SERVICES
    Route::resource('/admin/services',ServiceController::class)->withTrashed(['show'])->except(['edit']);
    Route::put('/admin/services/{service}/restore',[ServiceController::class,'restore'])->withTrashed()->name('services.restore');

    // BARBERS
    Route::resource('/admin/barbers',BarberController::class)->withTrashed(['show'])->except(['edit']);
    Route::put('/admin/barbers/{barber}/restore',[BarberController::class,'restore'])->withTrashed()->name('barbers.restore');

    // BARBER PROFILE PICTURE
    Route::post('/upload-cropped-admin/{user}',[PictureController::class,'uploadCropped'])->name('upload-cropped-admin');

    // ADMIN BOOKINGS
    Route::get('/admin/bookings/create/selectBarberService',[AdminAppointmentController::class,'createBarberService'])->name('bookings.create.barber.service');
    Route::get('admin/bookings/create/earliest',[AdminAppointmentController::class,'createGetEarliestBarber'])->name('bookings.create.earliest');
    Route::get('/admin/bookings/create/selectDate',[AdminAppointmentController::class,'createDate'])->name('bookings.create.date');
    Route::get('/admin/bookings/create/selectCustomer',[AdminAppointmentController::class,'createCustomer'])->name('bookings.create.customer');
    Route::get('/admin/bookings/create/confirm',[AdminAppointmentController::class,'createConfirm'])->name('bookings.create.confirm');
    Route::resource('/admin/bookings',AdminAppointmentController::class)->withTrashed(['show']);

    // ADMIN USERS
    Route::resource('/admin/customers',CustomerController::class)->except(['create','store','edit'])->withTrashed(['show','destroy']);
    Route::put('/admin/customers/{customer}/restore',[CustomerController::class,'restore'])->withTrashed()->name('customers.restore');

    //ADMIN TIME OFFS
    Route::resource('/admin/time-offs',AdminTimeOffController::class)->names('admin-time-offs');
});

// HOMEPAGE
Route::get('/',function() {

    $barbers = Barber::where('is_visible','=',1)->get();
    $services = Service::withoutTimeoff()->where('is_visible','=',1)->get();

    return view('home',[
        'barbers' => $barbers,
        'services' => $services
    ]);
})->name('home');

// COOKIE POLICY
Route::get('/cookies', function() {
    return view('policy.cookies');
})->name('cookies');

// PRIVACY POLICY
Route::get('/privacy',function() {
    return view('policy.privacy');
})->name('privacy');

// TERMS AND CONDITIONS
Route::get('terms', function(){
    return view('policy.terms');
})->name('terms');

// DEBUG
Route::get('debug-page/{message}',function(string $message){
    return view('debugpage',['message' => $message]);
})->name('debugpage');

//MAILCHECK
Route::get('mail-booked', function() {
    $app = Appointment::find(210);
    return view('emails.booking_stored',['appointment' => $app, 'notifiable' => $app->user]);
});

Route::get('mail-updated', function() {
    $app = Appointment::find(210);
    $oldAppointment = $app->only([
        'barber_id',
        'service_id',
        'comment',
        'price',
        'app_start_time',
        'app_end_time'
    ]);
    return view('emails.booking_updated',['newAppointment' => $app, 'oldAppointment' => $oldAppointment, 'notifiable' => $app->user, 'updatedBy' => $app->barber]);
});

Route::get('mail-cancelled', function() {
    $app = Appointment::find(210);
    return view('emails.booking_cancelled',['appointment' => $app, 'notifiable' => $app->barber->user, 'cancelledBy' => $app->user]);
});

Route::get('mail-verif', function(){
    return view('emails.email_verification',['notifiable' => auth()->user(), 'url' => '', 'newUser' => false]);
});


// 404
Route::fallback(function() {
    return view('404');
});