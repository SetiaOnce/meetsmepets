<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if ($role == 1 && Auth::user()->is_admin != 1 ) {
            return redirect('/login'); 
        }else if ($role == 0 && Auth::user()->is_admin != 0 ) {
            return redirect('/login'); 
        }else{
            return $next($request);
        }
        
        
    }
}
