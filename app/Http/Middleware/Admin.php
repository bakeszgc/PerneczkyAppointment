<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() === null) {
            return redirect()->route('login')->with('error',__('auth.error_sign_in'));
        } elseif (!isset($request->user()->email_verified_at)) {
            return redirect()->route('verification.notice');
        } elseif (!$request->user()->is_admin) {
            return back()->with('error',__('auth.error_not_authorized'));
        }

        return $next($request);
    }
}
