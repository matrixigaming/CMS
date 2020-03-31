<?php

namespace App\Http\Middleware;

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
        /*if (Auth::guard($guard)->check()) {
            $user = Auth::user()->toArray();
            if(isset($user) && !empty($user['roles'])){
                $roleData = array_shift($user['roles']);
                switch($roleData['name']):
                     case 'Agent':
                     case 'Agency':
                     case 'Buyer':  
                         return redirect()->intended('/profile');
                         break;
                     case 'Admin':  
                         return redirect()->intended('/user/list');
                         break;
                     default : 
                         return redirect()->intended('/');
                         break;
                 endswitch;
            }
            //return redirect('/');
        }

        return $next($request);*/
        if (Auth::guard($guard)->check()) {
            return redirect()->intended('/dashboard');
        }
        return $next($request);
    }
}
