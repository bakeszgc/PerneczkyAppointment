<?php

namespace App\Http\Controllers;

use Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        //
    }

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

        Auth::login($user);
        return redirect()->route('my-appointments.index')->with('success','Your account has been created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // user can only see his
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
