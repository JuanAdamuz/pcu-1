<?php

/**
 * This file is part of the "PCU" system
 *
 * (c) Apecengo <apecengo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * ----------------------------------------
 *
 * Este archivo es parte del sistema "PCU".
 *
 * (c) Apecengo <apecengo@gmail.com>
 *
 * Para más información acerca de la licencia y derechos de autor, ver archivo LICENSE
 * que se distribuyó junto al código fuente de este código fuente.
 */

namespace App\Http\Controllers;

use App\Answer;
use App\Exam;
use App\Jobs\GradeExam;
use App\Mail\VerifyEmail;
use App\Name;
use App\Page;
use App\Question;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Syntax\SteamApi\Facades\SteamApi;

class SetupController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'setup_required']); // Todas estas rutas necesitan setup-required
    }

    public function welcome()
    {
        $user = Auth::user();
        return view('setup.welcome')->with('user', $user);
    }

    public function checkGamePage()
    {
        return view('setup.checkgame');
    }

    public function checkGame()
    {

        $user = Auth::user();
        // Si ya sabemos que el usuario tenga el juego comprado, ¿para qué lo tenemos que comprobar?
        if ($user->has_game) {
            return "true";
        }

        $game = Cache::remember('setup.'.$user->id . 'checkGame.game', 1, function () use ($user) {
            return sizeof(SteamApi::player($user->steamid)->getOwnedGames(false, false, [107410]));
        });
        // Comprobar si no tiene el juego
        if ($game == 0) {
            return "false";
        }

        $sharing = Cache::remember('setup.'.$user->id . 'checkGame.sharing', 1, function () use ($user) {
            return SteamApi::player($user->steamid)->IsPlayingSharedGame(107410);
        });
        // Comprobar si tiene Family Sharing
        if ($sharing == 1) {
            return "false";
        }

        // Si pasa los filtros, lo tiene. Lo actualizamos en la db.
        $user->has_game = true;
        $user->timestamps = false;
        $user->save();
        $user->timestamps = true;
        Cache::forget('user.'. $user->id . '.getSetupStep');


        return "true";
    }

    public function infoPage()
    {
        return view('setup.info');
    }

    public function info(Request $request)
    {
        $this->validate($request, [
            'birth_date' => 'nullable|date_format:d/m/Y',
            'country' => 'nullable|cca2',
            'timezone' => 'nullable|timezone'
        ], [
            'birth_date.required' => 'La fecha de nacimiento es obligatoria.',
            'birth_date.date_format' => 'El formato de la fecha de nacimiento es inválido. (debe ser dd/mm/aaa)',
            'country.required' => 'El país de residencia es obligatorio.',
            'country.cca2' => 'El país es inválido.',
            'timezone.required' => 'La zona horaria es obligatoria.',
            'timezone.timezone' => 'La zona horaria es inválida.'
        ]);

        $user = Auth::user();
        if (is_null($user->birth_date)) {
            if (!$request->has('birth_date') || trim($request->input('birth_date') == '')) {
                return redirect(route('setup-info'))->withErrors(['birth_date' => 'La fecha de nacimiento es obligatoria.']);
            }
            $user->birth_date = Carbon::createFromFormat('d/m/Y', $request->input('birth_date'));
        }
        if (is_null($user->country)) {
            if (!$request->has('country')) {
                return redirect(route('setup-info'))->withErrors(['country' => 'El país de nacimiento es obligatorio.']);
            }
            $user->country = $request->input('country');
        }
        if (is_null($user->attributes['timezone'])) {
            if (!$request->has('timezone')) {
                return redirect(route('setup-info'))->withErrors(['timezone' => 'La zona horaria es obligatoria.']);
            }
            $user->timezone = $request->input('timezone');
        }

        // Si tiene menos de 16 años... (o si es un listo y ha puesto una fecha en el futuro)
        if ($user->birth_date->age < 16 || $user->birth_date->isFuture()) {
            // Le bloqueamos instantáneamente la cuenta con motivo especial @pegi
            $user->disabled = true;
            $user->disabled_reason = "@pegi"; // Motivo especial que le indica al login que debe mostrar la pág del pegi
            $user->disabled_at = Carbon::now();
            Auth::logout(); // Le cerramos sesión
            $user->timestamps = false;
            $user->save();
            $user->timestamps = true;
            // Y le redireccionamos, claro, si no no se enteraría de su edad
            return redirect(route('pegi'));
        }

        $user->timestamps = false;
        $user->save();
        $user->timestamps = true;
        Cache::forget('user.'. $user->id . '.getSetupStep');
        Cache::forget('user.' . $user->id . '.attributes.timezone');

        // Comprobamos la fecha de nacimiento

        return back();
    }

    public function emailPage()
    {
        return view('setup.email');
    }

    public function email(Request $request)
    {
        $this->validate($request, [
            'email' => 'nullable|email|unique:users,email',
            'enable' => 'boolean'
        ]);

        if ($request->input('enable')) {
            // El usuario ha decidido activar su email
            $user = Auth::user();
            $user->email_enabled = true;
            $user->email = $request->input('email');
            $user->email_verified_token_at = Carbon::now();
            $user->timestamps = false;
            $user->save();
            $user->timestamps = true;
            Cache::forget('user.'. $user->id . '.getSetupStep');
            Mail::to($user)->send(new VerifyEmail($user));
            return "next"; // Le indicamos a la página que todavía hay que verificar...
        } else {
            // El usuario no ha activado su email
            $user = Auth::user();
            $user->email = null; // Por si acaso se lo borramos
            $user->email_verified = false;
            $user->email_verified_token = null;
            $user->email_verified_at = null;
            $user->email_enabled = false;
            $user->timestamps = false;
            $user->save();
            $user->timestamps = true;
            Cache::forget('user.'. $user->id . '.getSetupStep');
            return "next";
        }
    }

//    public function emailCode() {
//        $user = Auth::user();
//        if(is_null($user->email) || is_null($user->email_enabled) || !$user->email_enabled)
//    }

    public function introPage()
    {
        return view('setup.intro');
    }

    public function namePage()
    {
        $user = Auth::user();
        return view('setup.name')
            ->with('user', $user);
    }

    public function nameCheck(Request $request)
    {
        $this->validate($request, [
           'firstName' => 'required|min:3|max:14',
           'lastName' => 'required|min:3|max:14'
        ]);
        $name = trim($request->input('firstName')) . " " . trim($request->input('lastName'));

        if(!preg_match('/^[ a-záéíóúñ]+$/iu', $name)) {
            abort(412, "El nombre solo puede contener letras en castellano y espacios.");
        }

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
        $name->type = "setup";
        $name->needs_review = true;
        $user->names()->save($name);
        Cache::forget('user.'. $user->id . '.getSetupStep');

//        $user->name = $name;
//        $user->timestamps = false; // Como es un cambio que no lo ha iniciado nadie ni importa realmente actualizar
//                                   // la fecha, no guardamos los timestamps.
//        $user->save();
//        $user->timestamps = true;

        // Informamos a la página que no hay problema y que puede continuar con el proceso.
        return "OK";
    }

    /**
     * Página de las normas.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function rulesPage()
    {
        $user = Auth::user();

        // Si el usuario no había leído las normas hasta ahora le ponemos en base de datos la fecha
        // principalmente para saber cuándo dejarle hacer el examen
        if (is_null($user->rules_seen_at)) {
            $user->rules_seen_at = Carbon::now();
            $user->timestamps = false;
            $user->save();
            $user->timestamps = true;
            Cache::forget('user.'. $user->id . '.getSetupStep');
        }

        $rules = Page::where('slug', 'normas')->first();

        return view('setup.rules')->with('rules', $rules);
    }

    public function rulesCheck(Request $request)
    {
        if (!$request->user()->canSeeRules()) {
            abort(403, 'No puedes ver las normas ahora');
        }
    }

    /**
     * Generar un examen y redireccionar.
     * @param Request $request
     */
    public function generateExam(Request $request)
    {
        if (!config('exam.enabled') || $request->user()->hasExamCooldown()) {
            abort(403, 'No puedes generar otro examen');
        }
        $user = $request->user();
        if (is_null($user->getOngoingExam())) {
            $config = config('exam.structure');
            $structure  = $config;
            $groupCount = 0;
            $questionIds = [];
            foreach ($config as $group) {
                $questionCount = 0;
                foreach ($group['questions'] as $question) {
                    if ($question['type'] == 'question') {
                        $structure[$groupCount]['questions'][$questionCount]['answer_id'] = null;
                    } elseif ($question['type'] == 'category') {
                        $questionModel = Question::where('enabled', true)
                            ->where('category_id', $question['id'])
                            ->orderByRaw('RAND()')
                            ->get()->reject(function ($question) use ($questionIds) {
                                return in_array($question->id, $questionIds); // Si no está en el array le dejamos
                            })->first();
                        $questionIds[] = $questionModel->id;
                        $structure[$groupCount]['questions'][$questionCount]['type'] = 'question';
                        $structure[$groupCount]['questions'][$questionCount]['answer_id'] = null;
                        $structure[$groupCount]['questions'][$questionCount]['id'] = $questionModel->id;
                    }
                    $questionCount++;
                }
                $groupCount++;
            }
            $exam = new Exam();
            $exam->user_id = $user->id;
            $exam->start_at = Carbon::now();
            $exam->end_at = Carbon::now()->addMinutes(30);
            $exam->expires_at = Carbon::now()->addDays(7);
            $exam->finished = false;
            $exam->structure = $structure;
            $exam->save();
            Cache::forget('user.'. $user->id . '.getSetupStep');
            return redirect(route('setup-exam'));
        }
        // si el usuario está haciendo un examen en el momento
        abort(403, 'Examen en curso');
    }

    public function examPage($page = null)
    {

        $user = Auth::user();
        // Si el usuario tiene un examen en curso
        if (!is_null($user->getOngoingExam())) {
            // ¿Ha pedido una página en concreto?
            if (! isset($page)) {
                $exam = $user->getOngoingExam();

                // Si intenta acceder sin página y debería estar en una página > 1, redir a la correcta
                if ($exam->getCurrentQuestionNumber() > 1) {
                    return redirect(route('setup-exam', ['page' => $exam->getCurrentQuestionNumber()]));
                }

                // De lo contrario, le mostramos la página de bienvenida al examen
                return view('setup.exam')
                    ->with('exam', $exam)
                    ->with('group', null)
                    ->with('question', null)
                    ->with('type', 'first')
                    ->with('pageNumber', 0);
            } else {
                // El usuario nos ha pasado una página en concreto

                $exam = $user->getOngoingExam();

                // Si la página que ha pasado no corresponde con la que debería ver, redir. (para los listillos)
                if ($exam->getCurrentQuestionNumber() != $page) {
                    return redirect(route('setup-exam', ['id' => $exam->getCurrentQuestionNumber()]));
                }

                // Obtenemos la estructura y la pregunta que toque para el número de pregunta
                $structure = $exam->structure;
                $pageNumber = $page;
                $count = 1;
                foreach ($structure as $group) {
                    foreach ($group['questions'] as $question) {
                        if ($pageNumber == $count) {
                            // Si la pregunta no existe, mejor fallamos aquí
                            // Y avisamos al usuario pertinentemente, que mostrar
                            // un error random
                            if (is_null(Question::find($question['id']))) {
                                abort(500, 'No se encuentra la pregunta');
                            }
                            return view('setup.exam')
                                ->with('exam', $exam)
                                ->with('group', $group)
                                ->with('question', $question)
                                ->with('type', 'question')
                                ->with('pageNumber', $pageNumber);
                        }
                        $count++;
                    }
                }

                // Si no encontramos pregunta... TODO cambiar esto
                return view('setup.exam')
                    ->with('exam', $exam)
                    ->with('group', null)
                    ->with('question', null)
                    ->with('type', 'first')
                    ->with('pageNumber', $pageNumber);
            }
        } else {
            // El usuario no tiene ningún examen en curso.

            // El usuario tiene exámenes sin corregir
            if ($user->exams()->whereNull('passed')->count() > 0) {
                // Le pedimos paciencia y que espere lo que tenga que esperar.
                return view('setup.exam.wait');
            } else {
                // El usuario todavía no tiene ningún examen.
                // Mostrarle la página para que genere uno.
                return view('setup.exam.new');
            }
        }
    }

    public function exam(Request $request, $page)
    {
        $pageNumber = $page;
        if ($page == 0) {
            abort(405);
        } else {
            $user = Auth::user();
            $exam = $user->exams()->whereNull('passed')->orderByDesc('created_at')->first();
            // Si el examen ha terminado hace más de un minuto o ya ha sido terminado
            if ($exam->end_at->addMinutes(1) < Carbon::now() || $exam->finished) {
                return "next";
            }
            $structure = $exam->structure;
            if ($page != $exam->getCurrentQuestionNumber()) {
                return "next";
            }
            $count = 1;
            $groupCount = 0;
            foreach ($structure as $group) {
                $questionCount = 0;
                foreach ($group['questions'] as $question) {
                    if ($pageNumber == $count) {
                        if (is_null($question['answer_id'])) {
                            // Aquí, si no había respondido
                            // Creamos la respuesta
                            // Si, gracias a PHP, hacemos todo eso
                            // Simplemente para sustituir una parte de un array
                            // ...
                            $answer = new Answer();
                            $answer->exam_id = $exam->id;
                            $answer->question_id = $question['id'];
                            $answer->question_text = Question::find($question['id'])->question;
                            $answer->user_problem_message = $request->input('message');
                            // Si no responde, le ponemos un cero ya de serie (o si se pasa de longitud)
                            if (is_null($request->input('answer')) || strlen($request->input('answer')) > 500) {
                                $answer->score = 0;
                            } else {
                                $answer->answer = $request->input('answer');
                            }
                            $answer->save();
                            $question['answer_id'] = $answer->id;
                            $structure[$groupCount]['questions'][$questionCount] = $question;
                            $exam->structure = $structure;

                            // Comprobamos si era la última pregunta
                            if ($exam->getQuestionCount() == $count) {
                                $exam->finished = true;
                                $exam->finished_at = Carbon::now();
                                $exam->save();
                                Cache::forget('user.'. $exam->user->id . '.getSetupStep');
                                dispatch(new GradeExam($exam));
                                return "next";
                            }

                            $exam->save();
                            Cache::forget('user.'. $exam->user->id . '.getSetupStep');
                            return "next";
                        } else {
                            // Si ya había respondido, le mandamos a la siguiente también.
                            // Por si son unos listillos.
                            return "next";
                        }
                    }
                    $questionCount++;
                    $count++;
                }
                $groupCount++;
            }
        }
    }


    public function forumPage()
    {
        return view('setup.forum');
    }

    public function forumRedirect()
    {
        return Socialite::with('ipb')->scopes(['user.profile', 'user.email', 'user.groups'])->redirect();
    }

    public function forumCallback(Request $request)
    {
        $user = Auth::user();
        try {
            $oauth = Socialite::driver('ipb')->scopes(['user.profile', 'user.email', 'user.groups'])->user();
        } catch (InvalidStateException $exception) {
            return view('setup.forumerror');
        } catch (ServerException $exception) {
            return view('setup.forumerror');
        }
        if (!is_null($user->ipb_token)) {
            return redirect(route('setup-interview'));
        }
        $user->ipb_token = $oauth->token;
        $user->ipb_refresh = $oauth->refreshToken;
        $user->ipb_id = $oauth->getID();
        $user->timestamps = false;
        $user->save();
        $user->timestamps = true;
        Cache::forget('user.'. $user->id . '.getSetupStep');
        return redirect(route('setup-interview'));
    }

    public function interviewPage(Request $request)
    {
        if ($request->user()->hasInterviewOngoing()) {
            $exam = $request->user()->getInterviewExam();
            return view('setup.interview_on')->with('exam', $exam);
        }
        return view('setup.interview');
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
