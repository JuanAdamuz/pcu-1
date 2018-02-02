<?php

namespace App\Http\Controllers;

use App\Name;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth'])->except('verifyCode');
    }

    public function accountPage()
    {
        $user = User::with(['roles', 'permissions', 'exams', 'names'])->findOrFail(Auth::user()->id);
        return view('account')->with('user', $user);
    }

    public function verifyCode($code)
    {
        $user = User::where('email_verified_token', $code)->firstOrFail();
        if (!$user->email_verified) {
            $user->email_verified = true;
            $user->email_verified_token = null;
            $user->email_verified_token_at = null;
            $user->email_verified_at = Carbon::now();
            $user->timestamps = false;
            $user->save();
            $user->timestamps = true;
            Cache::forget('user.'. $user->id . '.getSetupStep');
            if (Auth::check()) {
                return redirect(route('setup-name'))->with('status', 'Correo verificado correctamente');
            }
            return view('verified');
        }
    }

    public function namePage(Request $request)
    {
        if ($request->user()->name_changes_remaining == 0) {
            abort(403);
        }
        $user = Auth::user();
        return view('user.namechange')
            ->with('user', $user);
    }

    public function nameCheck(Request $request)
    {
        if ($request->user()->name_changes_remaining == 0) {
            abort(403);
        }
        $this->validate($request, [
            'firstName' => 'required|min:3|max:14',
            'lastName' => 'required|min:3|max:14'
        ]);
        $name = trim($request->input('firstName')) . " " . trim($request->input('lastName'));
        if (User::where('name', 'LIKE', $name)->count() > 0
            || Name::where('name', 'LIKE', $name)->count() > 0) {
            return "taken";
        }
        return "OK";
    }

    /**
     * POST cambiar el nombre
     * @param Request $request
     * @return string
     */
    public function name(Request $request)
    {
        if ($request->user()->name_changes_remaining == 0) {
            abort(403);
        }
        // Comprueba que haya nombre y apellidos enviados
        $this->validate($request, [
            'firstName' => 'required|min:3|max:14',
            'lastName' => 'required|min:3|max:14'
        ]);

        // Limpia el nombre completo de espacios innecesarios y añade un espacio entre nombre y apellido
        $fullName = trim($request->input('firstName')) . " " . trim($request->input('lastName'));

        // Comprueba con correspondencia "poco estricta" LIKE a ver si hay algún nombre parecido en POP o
        // haciendo la entrevista
        if (User::where('name', 'LIKE', $fullName)->count() > 0
            || Name::where('name', 'LIKE', $fullName)->count() > 0) {
            // Devolvemos que ya está cogido para que la página informe al usuario
            return "taken";
        }

        $user = Auth::user();

        $name = new Name();

        $correctedName = rtrim($this->correctSpelling($this->titleCase(str_replace('´', '', $fullName))));
        if ($correctedName != $fullName) {
            $name->original_name = $fullName;
        }

        $name->name = $correctedName;
        $name->type = "change";
        $name->needs_review = true;
        $user->names()->save($name);

        // Quitarle el namechange remaining para que no se lo vuelva a cambiar
        $user->name_changes_remaining = 0; // por ahora lo ponemos a cero, más adelante podríamos restarle
        $user->name_changes_reason = null;
        $user->save();
        Cache::forget('user.'. $user->id . '.getSetupStep');

//        $user->name = $name;
//        $user->timestamps = false; // Como es un cambio que no lo ha iniciado nadie ni importa realmente actualizar
//                                   // la fecha, no guardamos los timestamps.
//        $user->save();
//        $user->timestamps = true;

        // Informamos a la página que no hay problema y que puede continuar con el proceso.
        return "OK";
    }

    public function resetEmail(Request $request)
    {
        $user = $request->user();

        if (!$user->canEnableEmail()) {
            abort(403, 'No puedes cambiar tu email');
        }

        $user->email = null;
        $user->email_verified = false;
        $user->email_verified_token = null;
        $user->email_verified_at = null;
        if ($request->has('disable')) {
            // El usuario quiere desactivar.
            $user->email_enabled = false;
            $user->email_disabled_at = Carbon::now();
            $user->save();
            Cache::forget('user.'. $user->id . '.getSetupStep');
            return redirect(route('account'));
        } else {
            // El usuario quiere activar. Le redireccionamos a la página.
            $user->email_enabled = null;
            $user->save();
            Cache::forget('user.'. $user->id . '.getSetupStep');
            return redirect(route('setup-email'));
        }
    }

    public function correctSpelling($name)
    {
        return strtr(strtr($name, config('pcu.nombres')), config('pcu.except'));
    }

    /**
     * http://php.net/manual/es/function.ucwords.php#112795
     * @param $string
     * @param array $delimiters
     * @param array $exceptions
     * @return mixed|string
     */
    function titleCase($string, $delimiters = [" ", "-", ".", "'", "O'", "Mc"], $exceptions = ["de", "da", "dos", "das", "do", "del", "I", "II", "III", "IV", "V", "VI"])
    {
        /*
         * Exceptions in lower case are words you don't want converted
         * Exceptions all in upper case are any words you don't want converted to title case
         *   but should be converted to upper case, e.g.:
         *   king henry viii or king henry Viii should be King Henry VIII
         */
        $string = mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
        foreach ($delimiters as $dlnr => $delimiter) {
            $words = explode($delimiter, $string);
            $newwords = [];
            foreach ($words as $wordnr => $word) {
                if (in_array(mb_strtoupper($word, "UTF-8"), $exceptions)) {
                    // check exceptions list for any words that should be in upper case
                    $word = mb_strtoupper($word, "UTF-8");
                } elseif (in_array(mb_strtolower($word, "UTF-8"), $exceptions)) {
                    // check exceptions list for any words that should be in upper case
                    $word = mb_strtolower($word, "UTF-8");
                } elseif (!in_array($word, $exceptions)) {
                    // convert to uppercase (non-utf8 only)
                    $word = ucfirst($word);
                }
                array_push($newwords, $word);
            }
            $string = join($delimiter, $newwords);
        }//foreach
        return $string;
    }
}
