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
            return redirect()->route('login')->with('error','Please sign in before accessing that page!');
        } elseif (!isset($request->user()->barber) || isset($request->user()->barber->deleted_at)) {
            return back()->with('error','You are not authorized to access that page!');
        }

        return $next($request);
    }
}
