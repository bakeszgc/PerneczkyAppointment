<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Str;
use Auth;
use Hash;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Rules\ValidAppointmentTime;
use Illuminate\Auth\Events\Verified;
use App\Rules\RegisteredEmailAddress;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Laravel\Socialite\Facades\Socialite;
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
        if (request('from') == 'appConfirm') {
            $attributesArray = [];
            $attributes = explode('&',explode('?',url()->previous())[1]);

            foreach ($attributes as $attribute) {
                $key = explode('=',$attribute)[0];
                $value = explode('=',$attribute)[1];

                if ($key == 'date') {
                    $value = str_replace('+',' ',$value);
                    $value = str_replace('%3A',':',$value);
                    session([$key => $value]);
                } elseif ($key != 'day') {
                    session([$key => $value]);
                }
            }
        }        
        
        return view('auth.create');
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

        if (session('barber_id') && session('service_id') && session('date')) {
            $barberId = session('barber_id');
            $serviceId = session('service_id');
            $date = session('date');
            $comment = session('comment');

            $bookingData = [
                'barber_id' => $barberId,
                'service_id' => $serviceId,
                'date' => $date,
                'comment' => $comment,
            ];

            $request->session()->forget(['barber_id', 'service_id', 'date', 'comment']);
        }
    
        $credentials = $request->only('email','password');
        $remember = Cookies::hasConsentFor('remember_web') ? $request->filled('remember') : false;
    
        if(Auth::attempt($credentials, $remember)) {
            if (isset($bookingData)) {
                return redirect()->route('my-appointments.create.confirm',$bookingData);
            } else {
                return redirect()->intended(route('my-appointments.index'));
            }
        } else {
            $userQuery = User::withTrashed()->where('email','=',$request->email)->get();
            $error = __('auth.error_auth_email_or_pw');

            if ($userQuery->count() > 0) {
                $user = $userQuery->first();
                if ($user->deleted_at) {
                    $error = __('auth.error_auth_user_deleted');
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
    }

    public function verify(EmailVerificationRequest $request) {
        $request->fulfill();
        event(new Verified($request->user()));
        return redirect()->route('my-appointments.index')->with('success',__('auth.success_auth_email_verified'));
    }

    public function send(Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success',__('auth.success_verification_link_sent_1') . auth()->user()->email . __('auth.success_verification_link_sent_2'));
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

    // SOCIALITE AUTHENTICATION
    public function authProviderRedirect($provider) {
        if ($provider) {
            return Socialite::driver($provider)->redirect();
        } else {
            return redirect()->back()->with('error',__('auth.error_auth_provider'));
        }
        
    }

    public function socialAuth($provider, Request $request)
    {
        try {
            if (!$provider) {
                return redirect()->route('login')->with('error',__('auth.error_auth_provider'));
            }

            if (session('barber_id') && session('service_id') && session('date')) {
                $barberId = session('barber_id');
                $serviceId = session('service_id');
                $date = session('date');
                $comment = session('comment');

                $bookingData = [
                    'barber_id' => $barberId,
                    'service_id' => $serviceId,
                    'date' => $date,
                    'comment' => $comment,
                ];

                $request->session()->forget(['barber_id', 'service_id', 'date', 'comment']);
            }
            
            if ($provider == 'facebook') {
                $socialUser = Socialite::driver($provider)->fields(['name','first_name','last_name','email'])->user();
            } else {
                $socialUser = Socialite::driver($provider)->user();
            }

            $user = User::where($provider.'_id',$socialUser->getId())->first();

            if ($user) {
                Auth::login($user);
            } else {
                $userWithMail = User::whereEmail($socialUser->getEmail())->first();
                
                if ($userWithMail) {
                    $userWithMail->update([
                        $provider.'_id' => $socialUser->getId()
                    ]);
                    Auth::login($userWithMail);

                    if (!$userWithMail->email_verified_at) {
                        $userWithMail->notify(new VerifyEmail());
                    }

                } else {
                    if ($provider == 'google') {
                        $firstName = $socialUser->user['given_name'];
                        $lastName = $socialUser->user['family_name'];
                    } else {
                        $firstName = $socialUser->user['first_name'];
                        $lastName = $socialUser->user['last_name'];
                    }

                    $newUser = User::create([
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $socialUser->getEmail(),
                        $provider.'_id' => $socialUser->getId(),
                        'is_admin' => false
                    ]);
                    
                    Auth::login($newUser);
                    $newUser->notify(new VerifyEmail());
                }
            }

            if (isset($bookingData)) {
                return redirect()->route('my-appointments.create.confirm',$bookingData)->with('success',__('auth.social_login_success_message_1') . ' ' . ucfirst($provider) . ' ' . __('auth.social_login_success_message_2'));
            } else {
                return redirect()->route('my-appointments.index')->with('success',__('auth.social_login_success_message_1') . ' ' . ucfirst($provider) . ' ' . __('auth.social_login_success_message_2'));
            }

        } catch (Exception $e) {
            return redirect()->route('debugpage',['message' => $e]);
        }
    }
}
