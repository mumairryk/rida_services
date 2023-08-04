<?php

namespace App\Http\Middleware;

use Closure;

class IsUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!auth()->check()){
            return redirect()->route('login',['link'=>url()->full()]);
        }
        if(auth()->check() && auth()->user()->role != '2'){
            return redirect()->back();
        }
        return $next($request);
    }
}
