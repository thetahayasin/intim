<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class AuthenticateAssociate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //check if authenticated
        if (!Auth::guard('associate')->check()) {
            return redirect()->route('ass.login')->with('message', 'Please Login with Your Provided Credentials');
        }
        //check if active
        if(Auth::guard('associate')->check() )
        {
            if(!Auth::guard('associate')->user()->active)
            {
                //logout
                Auth::guard('associate')->logout();

                return redirect()->route('ass.login')->with('message', 'You will be remembered :)');
            }
            else
            {
                return $next($request);
            }
            
        }

        
    }
}
