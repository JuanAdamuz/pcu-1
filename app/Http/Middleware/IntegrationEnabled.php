<?php

namespace App\Http\Middleware;

use Closure;

class IntegrationEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! config('pcu.enable_integration')) {
            abort(404);
        }

        return $next($request);
    }
}
