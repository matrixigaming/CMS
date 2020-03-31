<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Closure;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];
    public function handle($request, Closure $next)
    {
        if(!$request->isXmlHttpRequest()){
            if($request->input('_token')){
                if( \Session::getToken() != $request->input('_token')){
                  //notify()->flash('Your session has expired. Please try logging in again.', 'warning');
                  // return redirect()->guest('/login');
                  return redirect('/login')->with('message', 'Something went wrong, please try again.');;
                }
              }
        }
      
      return parent::handle($request, $next);
    }
}
