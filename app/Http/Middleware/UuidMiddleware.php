<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Guard;
use Closure;


class UuidMiddleware
{

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->hasCookie('ams_cookies')) {
            $cookies = request()->cookies->all()['ams_cookies'];
            if (!SessionController::isExpired()) {
                return $next($request);
            }
        } else {
            return redirect()->guest('auth/login');
        }

        return $next($request);
    }

}
