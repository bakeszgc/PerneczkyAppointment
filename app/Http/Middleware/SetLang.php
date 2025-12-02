<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Whitecube\LaravelCookieConsent\Facades\Cookies;

class SetLang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Cookies::hasConsentFor('lang_pref') && Cookie::get('lang_pref')) {
            App::setLocale(Cookie::get('lang_pref',env('APP_LOCALE')));
        } else {
            if ($request->session()->has('lang')) {
                App::setLocale($request->session()->get('lang',env('APP_LOCALE')));
            }
        }

        // if ($request->session()->has('lang')) {
        //     App::setLocale($request->session()->get('lang',env('APP_LOCALE')));
        // }

        return $next($request);
    }
}
