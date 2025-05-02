<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $user = Auth::user();
            
            if ($user->role === 'admin') {
                return redirect(RouteServiceProvider::ADMIN);
            } else if ($user->role === 'guru') {
                return redirect(RouteServiceProvider::GURU);
            } else if ($user->role === 'siswa') {
                return redirect(RouteServiceProvider::SISWA);
            }
            
            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}
