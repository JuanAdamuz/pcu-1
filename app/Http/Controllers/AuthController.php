<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Invisnik\LaravelSteamAuth\SteamAuth;

class AuthController extends Controller
{
    /**
     * @var SteamAuth
     */
    private $steam;
    public function __construct(SteamAuth $steam)
    {
        $this->middleware('guest');
        $this->steam = $steam;
    }

    public function loginPage(Request $request)
    {
        if (!is_null($request->session()->get('status'))) {
            return view('disabled')->with('reason', $request->session()->get('status'));
        }
        return view('login');
    }

    public function login()
    {
        if ($this->steam->validate()) {
            $info = $this->steam->getUserInfo();
            if (!is_null($info)) {
                $user = User::where('steamid', $info->steamID64)->first();
                if (is_null($user)) {
                    if (!config('pcu.registrations_enabled')) {
                        return redirect('/')->with('status', 'Los registros de nuevas cuentas están desactivados en este momento.');
                    }
                    $user = new User();
                    $user->steamid = $info->steamID64;
                    $guid = $user->guid; // para generar la guid
                    $user->created_at = Carbon::now();
                    $user->save();
                    Auth::login($user, true);
                    return redirect(route('setup-welcome')); // redirect new user
                }
                if ($user->isDisabled()) {
                    if ($user->disabled_reason == "@pegi") {
                        return redirect(route('pegi'));
                    }
                    if (key_exists($user->disabled_reason, config('pcu.disabled_reasons'))) {
                        return redirect('/')->with('status', config('pcu.disabled_reasons')[$user->disabled_reason]);
                    }
                    return redirect('/')->with('status', $user->disabled_reason);
                }
                // Iniciar sesión
                Auth::login($user, true);
                if (is_null($user->active_at)) {
                    return redirect(route('setup-welcome'));
                }
                return redirect()->intended('home'); // redirect a donde tenía que ir
            }
        }
        return $this->steam->redirect(); // redirect to Steam login page
    }

    public function pegi()
    {
        return view('pegi');
    }

    public function altis()
    {
        // Si no está activado
        if (!config('pcu.altis_enabled')) {
            abort(404);
        }
        return view('altis');
    }
}
