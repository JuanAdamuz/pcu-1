<?php

namespace App\Http\Middleware;

use Closure;

class LogOutDisabledUsers
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
            if ($user->isDisabled()) {
                \Auth::logout();
                if ('@pegui' == $user->disabled_reason) {
                    return redirect(route('pegui'));
                }
                if (key_exists($user->disabled_reason, config('pcu.disabled_reasons'))) {
                    return redirect('/')->with('status', config('pcu.disabled_reasons')[$user->disabled_reason]);
                }

                return redirect('/')->with('status', $user->disabled_reason);
            }
        }

        return $next($request);
    }
}
