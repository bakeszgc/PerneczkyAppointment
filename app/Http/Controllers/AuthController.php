<?php

namespace App\Http\Controllers;

use App\Rules\RegisteredEmailAddress;
use Str;
use Auth;
use Hash;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Rules\ValidAppointmentTime;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Whitecube\LaravelCookieConsent\Facades\Cookies;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class AuthController extends Controller
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
        
        return view('auth.create',['prevAttributes' => $attributesArray]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'remember' => 'nullable',
            'date' => ['nullable','date','after_or_equal:now','date_format:Y-m-d G:i',new ValidAppointmentTime],
            'barber_id' => ['nullable','exists:barbers,id'],
            'service_id' => ['nullable','gt:1','exists:services,id'],
            'comment' => ['nullable'],
            'from' => ['nullable','string'],
        ]);
    
        $credentials = $request->only('email','password');
        $remember = Cookies::hasConsentFor('remember_web') ? $request->filled('remember') : false;
    
        if(Auth::attempt($credentials, $remember)) {
            if ($request->has(['from','date','barber_id','service_id','comment']) && $request->from == 'appConfirm') {
                return redirect()->route('my-appointments.create.confirm',[
                    'barber_id' => $request->barber_id,
                    'service_id' => $request->service_id,
                    'comment' => $request->comment,
                    'date' => $request->date
                ]);
            } else {
                return redirect()->intended(route('my-appointments.index'));
            }
        } else {
            $userQuery = User::withTrashed()->where('email','=',$request->email)->get();
            $error = 'Your email or password is invalid.';

            if ($userQuery->count() > 0) {
                $user = $userQuery->first();
                if ($user->deleted_at) {
                    $error = 'The account using this email address has been removed. If you think it was done by mistake please contact us!';
                }
            }

            return redirect()->back()->with('error',$error);
        }
    }

    public function destroy()
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('home');
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
            'email' => ['required','email',new RegisteredEmailAddress()]
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
