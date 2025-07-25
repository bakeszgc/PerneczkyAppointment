<?php

namespace App\Http\Controllers;

use Str;
use Auth;
use Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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

    public function forgotPassword() {
        return view('auth.forgot-password');
    }

    public function sendPasswordResetEmail(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::ResetLinkSent
            ? back()->with(['success' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword(string $token, Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        return view('auth.reset-password',[
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function updatePassword(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => ['required',\Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers()],
            'password_confirmation' => 'required|same:password'
        ]);

        $status = Password::reset(
            $request->only('email','password','password_confirmation','token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PasswordReset
            ? redirect()->route('login')->with('success',__($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
