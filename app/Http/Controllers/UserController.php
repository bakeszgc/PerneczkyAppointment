<?php

namespace App\Http\Controllers;

use Hash;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Validator;

class UserController extends Controller
{
    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|min:2|max:255',
            'last_name' => 'required|string|min:2|max:255',
            'date_of_birth' => 'required|date|before_or_equal:today',
            'telephone_number' => 'required|starts_with:+,0|numeric|unique:users,tel_number',
            'email' => 'required|email|unique:users,email',
            'password' => ['required',Password::min(8)->mixedCase()->numbers()],
            'password_confirmation' => 'required|same:password'
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'tel_number' => $request->telephone_number,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        event(new Registered($user));

        Auth::login($user);
        return redirect()->route('my-appointments.index')->with('success','Your account has been created successfully! Please verify your email address before booking an appointment!');
    }

    public function show(Request $request, User $user)
    {
        if (auth()->user()->id != $user->id) {
            return redirect()->route('users.show',auth()->user())->with('error','Sorry! You are not authorized to access that page.');
        }

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

        return view('user.show',[
            'user' => $user,
            'showPassword' => $showPassword,
            'showProfile' => $showProfile,
            'showPicture' => $showPicture,
            'showDestroy' => $showDestroy
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string|min:2|max:255',
            'last_name' => 'required|string|min:2|max:255',
            'display_name' => ['nullable','string','max:255',Rule::unique('barbers','display_name')->ignore($user->barber?->id)],
            'description' => ['nullable','string'],
            'date_of_birth' => 'required|date|before_or_equal:today',
            'telephone_number' => ['required','starts_with:+,0','numeric',Rule::unique('users','tel_number')->ignore($user->id)],
            'email' => ['required','email',Rule::unique('users','email')->ignore($user->id)]
        ]);

        $isEmailDifferent = $user->email !== $request->email;

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'tel_number' => $request->telephone_number,
            'email' => $request->email
        ]);

        if ($user->barber()) {
            $user->barber()->update([
                'display_name' => $request->display_name,
                'description' => $request->description
            ]);
        }

        if ($isEmailDifferent) {
            $user->update([
                'email_verified_at' => null
            ]);
            return redirect()->route('users.show',$user)->with('success','Account updated successfully! Please verify your new email address!');

        }

        return redirect()->route('users.show',$user)->with('success','Account updated successfully!');

    }

    public function updatePassword(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|current_password:web',
            'new_password' => ['required',Password::min(8)->mixedCase()->numbers()],
            'new_password_confirmation' => 'required|same:new_password'
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.show',['user' => $user, 'showPassword' => true])->withErrors($validator);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('users.show',['user' => $user->id])->with('success','Your password has been changed successfully!');
    }

    
}
