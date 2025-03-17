<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
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

    // EMAIL VERIFICATION

    public function notice() {
        if (auth()->user()->email_verified_at !== null) {
            return redirect()->route('my-appointments.index');
        }
        return view('auth.verify-email');
        // return back()->with('error','Please check your inbox to verify your email address! Click here to resend link');
    }

    public function verify(EmailVerificationRequest $request) {
        $request->fulfill();
        event(new Verified($request->user()));
        return redirect()->route('my-appointments.index')->with('success','Your email address has been verified successfully!');
    }

    public function send(Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success','Verification link sent to ' . auth()->user()->email);
    }

    public function resend() {
        if (auth()->user()->email_verified_at !== null) {
            return redirect()->route('my-appointments.index');
        }
        return redirect()->route('verification.send');
    }
}
