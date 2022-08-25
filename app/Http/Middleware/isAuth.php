<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isAuth
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
        if (auth()->guard('karyawan')->user()) {
            return $next($request);
        } else {
            return redirect()->route('home')->with('error', 'Silahkan Login Terlebih Dahulu');
        }
    }
}
