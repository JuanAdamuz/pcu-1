<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SetupCheck
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
        if (Auth::check()) {
            $finished = $request->user()->hasFinishedSetup();
            $step = $request->user()->getSetupStep();

            // -1. Comprobar si ha terminado ya el setup
            if ($finished && $request->is('setup/*')) {
                return redirect(route('home'));
            }

            // 0. Welcome
            if (('setup/welcome' == $request->route()->uri || 'setup/rules/check' == $request->route()->uri) && ! $finished) {
                return $next($request);
            }

            // 0. Check game
            if (0 == $step && ('setup/checkgame' != $request->route()->uri)) {
                return redirect(route('setup-checkgame'));
            }

            // 1. Datos
            if (1 == $step && 'setup/info' != $request->route()->uri) {
                return redirect(route('setup-info'));
            }

            // 2. Email
            if (2 == $step && 'setup/email' != $request->route()->uri) {
                return redirect(route('setup-email'));
            }

            // 3. Name
            if (3 == $step && ! ('setup/intro' == $request->route()->uri || 'setup/name' == $request->route()->uri || 'setup/name/check' == $request->route()->uri)) {
                return redirect(route('setup-name'));
            }

            // 4. Rules
            if (4 == $step && ! ('setup/rules' == $request->route()->uri)) {
                if (! is_null(\auth()->user()->rules_seen_at) && \auth()->user()->rules_seen_at->addMinutes(30) < Carbon::now()) {
                    Cache::forget('user.'.\auth()->user()->id.'.getSetupStep');

                    return $next($request);
                }

                return redirect(route('setup-rules'));
            }

            // 5. Exam
            if (5 == $step && ! ($request->is('setup/exam/*') || $request->is('setup/exam') || $request->is('setup/rules'))) {
                return redirect(route('setup-exam'));
            }

            // 5. Exam (rules)
            if (5 == $step && $request->is('setup/rules')) {
                if ($request->is('setup/rules') && ! is_null($request->user()->getOngoingExam())) {
                    return redirect(route('setup-exam'))->with('status', 'Ahora mismo no puedes ver las normas');
                }
            }

            // 6. Forum bridge
            if (6 == $step && ! ($request->is('setup/forum/*') || $request->is('setup/forum'))) {
                return redirect(route('setup-forum'));
            }

            // 7. Entrevista
            if (7 == $step && ! ('setup/interview' == $request->route()->uri || $request->is('setup/rules'))) {
                return redirect(route('setup-interview'));
            }

            // 5. Interview (rules)
            if (7 == $step && $request->is('setup/rules')) {
                if ($request->is('setup/rules') && $request->user()->hasInterviewOngoing()) {
                    return redirect(route('setup-interview'))->with('status', 'Ahora mismo no puedes ver las normas');
                }
            }
        }

        return $next($request);
    }
}
