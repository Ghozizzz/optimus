<?php

namespace Modules\LOGISTIC\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Route;
use Session;

class AuthenticateLOGISTIC
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
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('/logistic/signin')->with('message','Session Expired');
            }
        }
        return $next($request);
    }
}
