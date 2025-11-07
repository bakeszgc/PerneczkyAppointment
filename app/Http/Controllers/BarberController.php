<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barber;
use App\Models\Appointment;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use App\Notifications\BookingCancellationNotification;

class BarberController extends Controller
{
    public function index()
    {
        $barbers = Barber::withTrashed()->get();
        return view('barber.index',['barbers' => $barbers]);
    }

    public function create(Request $request)
    {
        $query = $request->input('query');

        $users = User::registered()->when($query, function ($q) use ($query) {
            $q->where('first_name','like',"%$query%")
            ->orWhere('last_name','like',"%$query%")
            ->orWhere('email','like',"%$query%")
            ->orWhere('tel_number','like',"%$query%");
        })->orderBy('first_name')->paginate(10);

        return view('barber.create',['users' => $users]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['required','integer','exists:users,id']
        ]);

        $barber = Barber::create([
            'user_id' => $request->user_id,
            'is_visible' => false
        ]);

        return redirect()->route('barbers.show',$barber)->with('success','New barber has been created successfully!');
    }

    public function show(Request $request, Barber $barber)
    {
        $request->validate([
            'showProfile' => 'nullable|boolean',
            'showPicture' => 'nullable|boolean',
            'showBookings' => 'nullable|boolean',
            'showTimeOffs' => 'nullable|boolean'
        ]);

        $showCalendar = $request->showCalendar ?? true;
        $showProfile = $request->showProfile ?? true;
        $showPicture = $request->showPicture ?? false;
        $showBookings = $request->showBookings ?? false;
        $showTimeOffs = $request->showTimeOffs ?? false;

        $calAppointments = Appointment::with('user')->get();
        $barbers = Barber::with('user')->get();

        $sumOfBookings = Appointment::getSumOfBookings($barber);
        $sumOfTimeOffs = Appointment::getSumOfTimeOffs($barber);

        return view('barber.show',[
            'barber' => $barber,
            'barbers' => $barbers,
            'calAppointments' => $calAppointments,
            'sumOfBookings' => $sumOfBookings,
            'sumOfTimeOffs' => $sumOfTimeOffs,
            'showCalendar' => $showCalendar,
            'showProfile' => $showProfile,
            'showPicture' => $showPicture,
            'showBookings' => $showBookings,
            'showTimeOffs' => $showTimeOffs
        ]);
    }

    public function update(Request $request, Barber $barber)
    {
        $response = Gate::inspect('update',$barber);

        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        $user = $barber->user;

        $request->validate([
            'first_name' => ['required','string','max:255'],
            'last_name' => ['required','string','max:255'],
            'display_name' => ['nullable','string','max:255',Rule::unique('barbers','display_name')->ignore($barber->id)],
            'description' => ['nullable','string','max:500'],
            'email' => ['required','email',Rule::unique('users','email')->ignore($user->id)],
            'date_of_birth' => ['required','date','before_or_equal:today'],
            'telephone_number' => ['required','starts_with:+,0','numeric',Rule::unique('users','tel_number')->ignore($user->id)]
        ]);

        $isEmailDifferent = $barber->user->email !== $request->email;

        $barber->update([
            'display_name' => $request->display_name,
            'description' => $request->description,
            'is_visible' => $request->is_visible ? true : false
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'date_of_birth' => $request->date_of_birth,
            'tel_number' => $request->telephone_number
        ]);

        if ($isEmailDifferent) {
            $user->update([
                'email_verified_at' => null
            ]);

            event(new Registered($user));

            return redirect()->route('barbers.show',$barber)->with('success',$barber->getName() . "'s personal details have been updated successfully! The new email address has to be verified!");
        }

        return redirect()->route('barbers.show',['barber' => $barber,'showProfile' => true])->with('success',$barber->getName() . "'s personal details have been updated successfully!");
    }

    public function destroy(Barber $barber)
    {
        $response = Gate::inspect('delete',$barber);

        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        // sets barber visibility to false
        $barber->update([
            'is_visible' => false
        ]);

        // cancelling upcoming bookings of the barber
        $upcomingBookings = Appointment::barberFilter($barber)->upcoming()->get();
        foreach ($upcomingBookings as $booking) {
            if ($booking->service_id != 1) {
                $booking->user->notify(new BookingCancellationNotification($booking,'admin'));
            }           
            
            $booking->delete();
        }

        $barber->delete();
        return redirect()->route('barbers.show',$barber)->with('success',$barber->getName() . "'s barber access has been removed successfully!");
    }

    public function restore(Barber $barber)
    {
        $response = Gate::inspect('restore',$barber);

        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        $barber->restore();
        return redirect()->route('barbers.show',$barber)->with('success',$barber->getName() . "'s barber access has been restored successfully!");
    }
}
