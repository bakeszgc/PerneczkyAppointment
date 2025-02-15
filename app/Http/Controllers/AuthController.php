<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function create()
    {
        //ha be van jelentkezve akkor irányítsa máshova
        if (auth()->user()) {
            return redirect()->route('my-appointments.index');
        }
        return view('auth.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        $credentials = $request->only('email','password');
        $remember = $request->filled('remember');
    
        if(Auth::attempt($credentials, $remember)) {
            return redirect()->intended(route('my-appointments.index'));
        } else {
            return redirect()->back()->with('error','Your email or password is invalid.');
        }
    }

    public function destroy()
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }
}
