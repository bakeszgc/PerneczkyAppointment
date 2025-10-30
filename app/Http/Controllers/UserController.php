<?php

namespace App\Http\Controllers;

use Hash;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Rules\ValidAppointmentTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function create()
    {
        // REDIRECTS IF THE USER IS LOGGED IN ALREADY
        if (auth()->user()) {
            return redirect()->route('my-appointments.index');
        }
        
        // HANDLING POSSIBLE ATTRIBUTES
        $attributesArray = [];
        if (request('from') == 'appConfirm') {
            $attributes = explode('&',explode('?',url()->previous())[1]);

            foreach ($attributes as $attribute) {
                $key = explode('=',$attribute)[0];
                $value = explode('=',$attribute)[1];

                if ($key == 'date') {
                    $value = str_replace('+',' ',$value);
                    $value = str_replace('%3A',':',$value);
                    $attributesArray[$key] = $value;
                } elseif ($key != 'day') {
                    $attributesArray[$key] = $value;
                }
            }
        }

        return view('user.create',['prevAttributes' => $attributesArray]);
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
            'password_confirmation' => 'required|same:password',
            'date' => ['nullable','date','after_or_equal:now','date_format:Y-m-d G:i',new ValidAppointmentTime],
            'barber_id' => ['nullable','exists:barbers,id'],
            'service_id' => ['nullable','gt:1','exists:services,id'],
            'comment' => ['nullable'],
            'from' => ['nullable','string'],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'tel_number' => $request->telephone_number,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_admin' => false
        ]);

        event(new Registered($user));

        Auth::login($user);

        if ($request->has(['from','date','barber_id','service_id','comment']) && $request->from == 'appConfirm') {
            return redirect()->route('my-appointments.create.confirm',[
                'barber_id' => $request->barber_id,
                'service_id' => $request->service_id,
                'comment' => $request->comment,
                'date' => $request->date
            ])->with('success','Your account has been created successfully!');
        } else {
            return redirect()->route('my-appointments.index')->with('success','Your account has been created successfully!');
        }        
    }

    public function show(Request $request, User $user)
    {
        $response = Gate::inspect('view',$user);

        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
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
        $response = Gate::inspect('update',$user);

        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

        $request->validate([
            'first_name' => 'required|string|min:2|max:255',
            'last_name' => 'required|string|min:2|max:255',
            'display_name' => ['nullable','string','max:255',Rule::unique('barbers','display_name')->ignore($user->barber?->id)],
            'description' => ['nullable','string','max:500'],
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

            event(new Registered($user));

            return redirect()->route('users.show',$user)->with('success','Account updated successfully! Please verify your new email address!');
        }

        return redirect()->route('users.show',$user)->with('success','Account updated successfully!');

    }

    public function updatePassword(Request $request, User $user)
    {
        $response = Gate::inspect('updatePassword',$user);

        if ($response->denied()) {
            return redirect()->back()->with('error',$response->message());
        }

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
