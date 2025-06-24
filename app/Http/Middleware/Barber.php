<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Barber
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() === null) {
            return redirect()->route('login');
        } elseif ($request->user()->barber === null && $request->user()->isAdmin === 0) {
            return redirect()->route('my-appointments.index');
        }

        return $next($request);
    }
}
