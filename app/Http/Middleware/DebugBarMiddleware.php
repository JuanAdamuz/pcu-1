<?php

namespace App\Http\Middleware;

use Closure;

class DebugBarMiddleware
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
        if (\Auth::check()) {
            $user = $request->user();
            if (! $user->isAdmin()) {
                \Debugbar::disable();
            }
        } else {
            \Debugbar::disable();
        }

        return $next($request);
    }
}
