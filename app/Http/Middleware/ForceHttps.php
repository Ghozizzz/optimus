<?php
namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (($request->server('HTTP_X_FORWARDED_PROTO') != 'https') && app()->environment('production') && !$request->isSecure()) {
                     // production is not currently secure
            return redirect()->secure($request->getRequestUri());
    }

    return $next($request);
    }
}