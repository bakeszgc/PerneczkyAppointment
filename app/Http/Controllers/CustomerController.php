<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barber;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Events\Registered;
use App\Notifications\BookingCancellationNotification;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string|max:255'
        ]);
        
        $query = $request->input('query');

        $users = User::hasStoredEmail()->when($query, function ($q) use ($query) {
            $q->where('first_name','like',"%$query%")
            ->orWhere('last_name','like',"%$query%")
            ->orWhere('email','like',"%$query%")
            ->orWhere('tel_number','like',"%$query%");
        })->orderBy('first_name')->withTrashed()->paginate(10)->through(function ($user) {
            return [
                'user' => $user,
                'previous' => Appointment::userFilter($user)->previous()->withoutTimeOffs()->orderByDesc('app_start_time')->first(),
                'upcoming' => Appointment::userFilter($user)->upcoming()->withoutTimeOffs()->orderBy('app_start_time')->first()
            ];
        });

        return view('admin.customer.index',['users' => $users]);
    }

    public function show(Request $request, User $customer)
    {
        $request->validate([
            'showProfile' => 'nullable|boolean',
            'showPicture' => 'nullable|boolean',
            'showPassword' => 'nullable|boolean',
            'showDestroy' => 'nullable|boolean'
        ]);

        $showProfile = $request->showProfile ?? !isset($customer->deleted_at) ?? true;
        $showPicture = $request->showPicture ?? false;
        $showPassword = $request->showPassword ?? false;
        $showDestroy = $request->showDestroy ?? false;
        $showRestore = $request->showRestore ?? true;
        $showBookings = $request->showBookings ?? false;

        $sumOfBookings = Appointment::getSumOfBookings(user: $customer);

        $data = [
            'user' => $customer,
            'showPassword' => $showPassword,
            'showProfile' => $showProfile,
            'showPicture' => $showPicture,
            'showDestroy' => $showDestroy,
            'showRestore' => $showRestore,
            'showBookings' => $showBookings,
            'sumOfBookings' => $sumOfBookings,
            'view' => 'admin'
        ];

        if (!$customer->hasEmail()) {
            $appointment = Appointment::userFilter($customer)->first();
            $data['appointment'] = $appointment;
        }

        return view('user.show',$data);
    }

    public function update(Request $request, User $customer)
    {
        $response = Gate::inspect('adminUpdate',$customer);

        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        $request->validate([
            'first_name' => 'required|string|min:2|max:255',
            'last_name' => 'required|string|min:2|max:255',
            'display_name' => 'nullable|string',
            'date_of_birth' => 'nullable|date|before_or_equal:today',
            'telephone_number' => ['nullable','starts_with:+,0','numeric',Rule::unique('users','tel_number')->ignore($customer->id)],
            'email' => ['required','email',Rule::unique('users','email')->ignore($customer->id)],
            'is_barber' => 'nullable',
            'is_admin' => 'nullable|boolean'
        ]);

        $isEmailDifferent = $customer->email !== $request->email;
        

        $customer->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'tel_number' => $request->telephone_number,
            'email' => $request->email,
            'is_admin' => $request->boolean('is_admin')
        ]);

        // checks if the customer's relationship to the barber model is different compared to the data sent in request
        // if it's different then creates a new barber/restores it if the user has been a barber before
        // or soft deletes the barber when barber access is being revoked
        $isBarberAccessDifferent = ($customer->barber && !isset($customer->barber->deleted_at)) !== ($request->boolean('is_barber'));

        if ($isBarberAccessDifferent) {
            if ($request->boolean('is_barber')) {
                if ($customer->barber) {
                    $customer->barber->restore();
                } else {
                    Barber::create([
                        'user_id' => $customer->id,
                        'is_visible' => false,
                    ]);
                }
            } else {
                $barber = $customer->barber;
                $barber->is_visible = false;
                $barber->delete();
            }
        }

        if ($isEmailDifferent) {
            $customer->update([
                'email_verified_at' => null
            ]);

            event(new Registered($customer));

            return redirect()->route('customers.show',$customer)->with('success','Account has been updated successfully! The new email address has to be verified!');
        }

        return redirect()->route('customers.show',$customer)->with('success','Account has been updated successfully!');
    }

    public function destroy(User $customer)
    {
        $response = Gate::inspect('adminDelete',$customer);

        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        // deletes upcoming appointments of the user
        $upcomingAppointments = Appointment::userFilter($customer)->upcoming()->get();
        foreach ($upcomingAppointments as $appointment) {
            $appointment->barber->user->notify(
                new BookingCancellationNotification($appointment,'admin')
            );
            $appointment->delete();
        }

        // deletes upcoming bookings if the user is a barber
        // revokes user's barber access
        if ($customer->barber) {
            $upcomingBookings = Appointment::barberFilter($customer->barber)->upcoming()->get();
            foreach ($upcomingBookings as $booking) {
                $booking->delete();
            }

            $customer->barber->delete();
        }

        // revokes user's admin access
        $customer->update([
            'is_admin' => 0
        ]);

        // soft-deletes user
        $customer->delete();

        return redirect()->route('customers.show',$customer)->with('success',$customer->first_name . "'s account has been deleted sucessfully!");
    }

    public function restore(User $customer) {
        $response = Gate::inspect('adminRestore',$customer);

        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        $customer->restore();

        return redirect()->route('customers.show',$customer)->with('success',$customer->first_name . "'s account has been restored sucessfully!");
    }
}
