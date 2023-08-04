<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class Vendor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if (Auth::check() && (Auth::user()->user_type_id == '1' || Auth::user()->user_type_id == '2' || Auth::user()->user_type_id == '3' || Auth::user()->user_type_id == '4' || Auth::user()->user_type_id == '5' || Auth::user()->user_type_id == '6')) {
            return $next($request);
        }
        return redirect()->route('vendor.login');
    }
}
