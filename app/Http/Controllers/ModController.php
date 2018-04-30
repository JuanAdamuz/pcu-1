<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Exam;
use App\Name;
use App\Notifications\AbuseSuspension;
use App\Notifications\InterviewFailed;
use App\Notifications\InterviewPassed;
use App\Question;
use App\Review;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ModController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'setup_required']);
    }

    public function dashboardPage()
    {
        if (! Auth::user()->hasPermission(['mod-search', 'mod-review*', 'mod-interview', 'mod-supervise*'])) {
            abort(403);
        }
        $count = Cache::remember('mod.dashboard.'.\auth()->user()->id.'count', 10, function () {
            $count = 0;
            if (Auth::user()->hasPermission('mod-review-names')) {
                $count = $count + Name::reviewable()->count();
            }
            if (Auth::user()->hasPermission('mod-review-answers')) {
                $count = $count + Answer::reviewable()->count();
            }

            return $count;
        });
        $supervisionCount = Cache::remember('mod.dashboard.'.\auth()->user()->id.'totalCount', 10, function () {
            $supervisionCount = 0;
            if (Auth::user()->hasPermission('mod-supervise-answers')) {
                $supervisionCount = $supervisionCount + Answer::where('needs_supervisor', true)->count();
            }

            return $supervisionCount;
        });

        return view('mod.dashboard')
            ->with('count', $count)
            ->with('supervisionCount', $supervisionCount);
    }

    public function reviewPage()
    {
        if (! Auth::user()->hasPermission(['mod-review-answers', 'mod-review-names'])) {
            abort(403);
        }

        return view('mod.review');
    }

    public function reviewGet()
    {
        if (! Auth::user()->hasPermission(['mod-review-answers', 'mod-review-names'])) {
            abort(403);
        }

        $options = [];
        if (Auth::user()->hasPermission('mod-review-answers')) {
            $exists = Answer::reviewable()->count() > 0;
            if ($exists) {
                $options[] = \App\Answer::class;
            }
        }
        if (Auth::user()->hasPermission('mod-review-names')) {
            $exists = Name::reviewable()->count() > 0;
            if ($exists) {
                $options[] = \App\Name::class;
            }
        }

        // Si hay algo que revisar
        if (sizeof($options) > 0) {
            // Escogemos uno al azar
            $chosen = $options[rand(0, sizeof($options) - 1)];

            // App\Answer
            if (\App\Answer::class == $chosen) {
                $answer = Answer::reviewable()
//                    ->random()
//                    ->orderByRaw("RAND()")
                    ->with('question')
                    ->take(10)
                    ->first();
                if (is_null($answer)) {
                    return [];
                }

                return ['answer' => $answer->makeHidden([
                    'question_id',
                    'exam_id',
                    'user_problem_message',
                    'needs_supervisor',
                    'needs_supervisor_reason',
                    'supervisor_at',
                    'supervisor_action',
                    'supervisor_id',
                    'created_at',
                    'updated_at',
                    'answer_id',
                    'score',
                ])->toArray()];
            }
            if (\App\Name::class == $chosen) {
                $name = Name::reviewable()
//                    ->random()
//                    ->orderByRaw("RAND()")
                    ->take(100)
                    ->first();
                if (is_null($name)) {
                    return [];
                }

                return ['name' => $name->makeHidden(['user_id', 'created_at', 'updated_at', 'deleted_at', 'active_at', 'end_at', 'needs_review', 'invalid', 'type'])->toArray()];
            }
        }

        // Si no, no hay nada y no devolvemos.
        return [];
    }

    /**
     * Añadir una review a una respuesta.
     *
     * @param Request $request
     *
     * @return string
     */
    public function review(Request $request)
    {
        if (! Auth::user()->hasPermission(['mod-review-answers', 'mod-review-names'])) {
            abort(403);
        }
        // Validar que ningún listillo nos pase algo mal para probar
        $this->validate($request, [
            'type'         => 'required',
            'id'           => 'required|integer|min:1',
            'score'        => 'required|integer|min:0|max:100',
            'abuse'        => 'required|boolean',
            'abuseMessage' => 'nullable|max:200',
            'abuseId'      => 'nullable|max:200',
        ]);

        $user = Auth::user();

        $type = null;
        $id = 0;
        // App\Answer
        if ('answer' == $request->input('type')) {
            if (! Auth::user()->hasPermission(['mod-review-answers'])) {
                abort(403);
            }
            $type = \App\Answer::class;
            // Encontramos la respuesta con la id
            $answer = Answer::findOrFail($request->input('id'));
            $id = $answer->id;

            // Si ya ha terminado de ser revisada, abortamos
//            if($answer->reviewed) {
//                abort(403, 'Already finished review phase');
//            }
        }

        // App\Name
        if ('name' == $request->input('type')) {
            if (! Auth::user()->hasPermission(['mod-review-names'])) {
                abort(403);
            }
            $type = \App\Name::class;
            $name = Name::findOrFail($request->input('id'));
            $id = $name->id;

            // Si nos pone una score que no sea la que toque
//            if($request->input('score') != 0 && $request->input('score') != 1) {
//                abort(422);
//            }

            // Si no necesita revisión
            if (! $name->needs_review) {
                abort(403);
            }
        }

        if (Review::where('user_id', $user->id)->where('reviewable_type', $type)->where('reviewable_id', $id)->count() > 0
            || Review::where('reviewable_type', $type)->where('reviewable_id', $id)->count() >= 3) {
            abort(403);
        }
        if (is_null($type)) {
            abort(422);
        }

        // Creamos y asignamos la review
        $review = new Review();
        $review->user_id = $user->id;
        $review->reviewable_type = $type;
        $review->reviewable_id = $id;
        if ($request->input('abuse')) { // Si hay abuso, le ponemos un cerapio y miramos el mensaje
            $review->score = 0;
            if (100 == $request->input('abuseId')) {
                $review->abuse_message = 'Otro: "'.$request->input('abuseMessage').'"';
            } else {
                $review->abuse_message = $request->input('abuseId');
            }
        } else {
            $review->score = $request->input('score');
        }
        $review->abuse = $request->input('abuse');
        $review->save();

        // Da igual lo que devolvamos, la página va a comportarse igual
        return 'ok';
    }

    public function searchPage(Request $request)
    {
        if (! Auth::user()->hasPermission(['mod-search', 'mod-interview'])) {
            abort(403);
        }
        $results = User::query();
        if ($request->has('q')) {
            $results->whereHas('names', function ($query) use ($request) {
                $query->where('name', 'LIKE', '%'.$request->input('q').'%');
            });
            $results->orWhere('steamid', 'LIKE', '%'.$request->input('q').'%');
            $results->orWhere('guid', 'LIKE', '%'.$request->input('q').'%');
            $results->orWhere('name', 'LIKE', '%'.$request->input('q').'%');
        }

        $results = $results->orderBy('updated_at', 'desc');
        $results = $results->paginate(15);

        return view('mod.search')->with('results', $results);
    }

    public function userPage($id)
    {
        if (! Auth::user()->hasPermission(['mod-search', 'mod-interview'])) {
            abort(403);
        }
        $user = User::findOrFail($id);

        return view('mod.user')->with('user', $user);
    }

    public function revealBirthDate($id)
    {
        if (! Auth::user()->hasPermission('mod-reveal-birthdate')) {
            abort(403);
        }
        $user = User::findOrFail($id);
        if ($user->hasPermission('protection-level-1') && ! Auth::user()->hasPermission('protection-level-1-bypass')) {
            abort(403);
        }

        return $user->birth_date->format('d/m/Y').' ('.$user->birth_date->age.' años)';
    }

    public function revealEmail($id)
    {
        if (! Auth::user()->hasPermission('mod-reveal-email')) {
            abort(403);
        }
        $user = User::findOrFail($id);
        if ($user->hasPermission('protection-level-1') && ! Auth::user()->hasPermission('protection-level-1-bypass')) {
            abort(403);
        }

        return $user->email;
    }

    public function interviewPage(Request $request, $id)
    {
        if (! Auth::user()->hasPermission(['mod-interview'])) {
            abort(403);
        }
        $exam = Exam::findOrFail($id);
        if (is_null($exam->interview_at) || is_null($exam->interview_user_id)) {
            abort(403, 'Entrevista no empezada todavía');
        }
        if (! $exam->interviewer->is($request->user())) {
            abort(403, 'Otra persona está realizando la entrevista');
        }
        if (! is_null($exam->interview_passed)) {
            abort(403, 'Entrevista finalizada');
        }

        return view('mod.interview')->with('exam', $exam);
    }

    public function interview(Request $request, $id)
    {
        if (! Auth::user()->hasPermission(['mod-interview'])) {
            abort(403);
        }
        $exam = Exam::findOrFail($id);
        if (! is_null($exam->interview_at) || ! is_null($exam->interview_user_id)) {
            if (! $exam->interviewer->is($request->user())) {
                abort(403, 'Entrevista empezada por otra persona');
            }

            return redirect(route('mod-interview', $exam));
        }
        $exam->interview_at = Carbon::now();
        $exam->interview_user_id = Auth::user()->id;
        $exam->interview_code = Str::random(32);
        $exam->save();

        return redirect(route('mod-interview', $exam))->with('status', 'Entrevista comenzada');
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function interviewCode(Request $request, $id)
    {
        if (! Auth::user()->hasPermission(['mod-interview'])) {
            abort(403);
        }
        $exam = Exam::findOrFail($id);
        // Comprobar si es el que está haciendo la entrevista
        if (! $request->user()->is($exam->interviewer)) {
            abort(403, 'No eres el que está haciendo la entrevista');
        }
        // Comprobar si el examen ha empezado
        if (! is_null($exam->interview_code_at) && ! is_null($exam->interview_passed)) {
            return redirect(route('mod-interview', $exam))->with('status', 'Ya habías introducido el código...');
        }
        $this->validate($request, [
           'code' => 'required|min:32|max:32',
        ]);

        if ($request->input('code') != $exam->interview_code) {
            return redirect(route('mod-interview', $exam))->withErrors(['code' => 'El código proporcionado es incorrecto.']);
        }

        // Correcto, lo guardamos y movemos.
        $exam->interview_code_at = Carbon::now();
        $exam->save();

        return redirect(route('mod-interview', $exam))->with('status', 'Código correcto');
    }

    public function interviewCancel(Request $request, $id)
    {
        if (! Auth::user()->hasPermission(['mod-interview'])) {
            abort(403);
        }
        $exam = Exam::findOrFail($id);
        // Comprobar si es el que está haciendo la entrevista
        if (! $request->user()->is($exam->interviewer)) {
            abort(403, 'No eres el que está haciendo la entrevista');
        }
        if (! is_null($exam->interview_passed)) {
            return redirect(route('mod-user', $exam->user))->with('status', 'Entrevista finalizada');
        }
        $exam->interview_at = null;
        $exam->interview_code = null;
        $exam->interview_code_at = null;
        $exam->interview_user_id = null;
        $exam->save();

        return redirect(route('mod-user', $exam->user))->with('status', 'Entrevista cancelada');
    }

    public function interviewGrade(Request $request, $id)
    {
        if (! Auth::user()->hasPermission(['mod-interview'])) {
            abort(403);
        }
        $exam = Exam::findOrFail($id);
        // Comprobar si es el que está haciendo la entrevista
        if (! $request->user()->is($exam->interviewer)) {
            abort(403, 'No eres el que está haciendo la entrevista');
        }
        if (! is_null($exam->interview_passed)) {
            return 'OK...';
        }
        $this->validate($request, [
            'pass' => 'required|boolean',
            'pegi' => 'required|boolean',
        ]);
        if ($request->input('pegi')) {
            $exam->interview_passed = false;
            $exam->interview_end_at = Carbon::now();
            $exam->save();
            // Desactivamos el usuario
            $user = $exam->user;
            // Le bloqueamos instantáneamente la cuenta con motivo especial @pegi
            $user->disabled = true;
            $user->disabled_reason = '@pegi'; // Motivo especial que le indica al login que debe mostrar la pág del pegi
            $user->disabled_at = Carbon::now();
            $user->save();
            Cache::forget('user.'.$user->id.'.getSetupStep');

            return 'OK';
        }

        if ($request->input('pass')) {
            $exam->interview_passed = true;
            $exam->user->notify(new InterviewPassed($exam));
        } else {
            $exam->interview_passed = false;
            if (0 == $exam->user->getExamTriesRemaining()) {
                $user = $exam->user;
                $user->disabled = 1;
                $user->disabled_reason = '@tries';
                $user->disabled_at = Carbon::now();
                $user->save();
                Cache::forget('user.'.$user->id.'.getSetupStep');
            }
            $exam->user->notify(new InterviewFailed($exam));
        }
        $exam->interview_end_at = Carbon::now();
        $exam->save();
        Cache::forget('user.'.$exam->user->id.'.getSetupStep');

        return 'OK';
    }

    public function supervisePage()
    {
        if (! Auth::user()->hasPermission(['mod-review-answers'])) {
            abort(403);
        }

        return view('mod.supervise');
    }

    public function superviseGet()
    {
        if (! Auth::user()->hasPermission(['mod-supervise-answers'])) {
            abort(403);
        }

        $options = [];
        if (Auth::user()->hasPermission('mod-review-answers') && Answer::reviewable()->count() > 0) {
            $options[] = \App\Answer::class;
        }
//        if(Auth::user()->hasPermission('mod-review-names') && Name::reviewable()->count() > 0) {
//            $options[] = 'App\Name';
//        }

        // Si hay algo que revisar
        if (sizeof($options) > 0) {
            // Escogemos uno al azar
            $chosen = $options[rand(0, sizeof($options) - 1)];

            // App\Answer
            if (\App\Answer::class == $chosen) {
                $answer = Answer::where('needs_supervisor', true)
                    ->orderByRaw('RAND()')
                    ->take(10)
                    ->with(['question', 'reviews', 'reviews.user'])
                    ->first();
                if (is_null($answer)) {
                    return [];
                }

                return ['answer' => $answer->makeHidden(['question_id', 'exam_id'])->toArray()];
            }
//            if($chosen == 'App\Name'){
//                $name = Name::reviewable()
//                    ->orderByRaw("RAND()")
//                    ->first();
//                if(is_null($name)) {
//                    return [];
//                }
//                return ['name' => $name->makeHidden(['user_id', 'created_at', 'updated_at', 'deleted_at', 'active_at', 'end_at', 'needs_review', 'invalid', 'type'])->toArray()];
//            }
        }

        // Si no, no hay nada y no devolvemos.
        return [];
    }

    /**
     * Añadir una review a una respuesta.
     *
     * @param Request $request
     *
     * @return string
     */
    public function supervise(Request $request)
    {
        if (! Auth::user()->hasPermission(['mod-supervise-answers'])) {
            abort(403);
        }
        // Validar que ningún listillo nos pase algo mal para probar
        $this->validate($request, [
            'type'         => 'required',
            'id'           => 'required|integer|min:1',
            'score'        => 'required|integer|min:0|max:100',
            'abuse'        => 'required|boolean',
            'abuseMessage' => 'nullable|max:200',
            'abuseId'      => 'nullable|max:200',
        ]);

        $user = Auth::user();

        $type = null;
        $id = 0;
        // App\Answer
        if ('answer' == $request->input('type')) {
            if (! Auth::user()->hasPermission(['mod-supervise-answers'])) {
                abort(403);
            }
            // Encontramos la respuesta con la id
            $answer = Answer::findOrFail($request->input('id'));

            // Vemos a ver si hay abuso.
            if ($request->input('abuse')) {
                // Abuso. Baneamos al usuario del tirón. o/

                // Marcamos como revisado por el supervisor
                $answer->score = 0;
                $answer->needs_supervisor = false;
                $answer->needs_supervisor_reason = null;
                $answer->supervisor_at = Carbon::now();
                $answer->supervisor_action = 'abuse';
                $answer->supervisor_id = Auth::user()->id;
                $answer->save();

                // Suspendemos el examen sin notificarle
                $exam = $answer->exam;
                $exam->passed = 0;
                $exam->passed_at = Carbon::now();
                $exam->save();

                // Desactivamos al usuario del tirón
                $user = $answer->exam->user;
                $user->disabled = true;
                if (100 == $request->input('abuseId')) { // El motivo de desactivación
                    $user->disabled_reason = $request->input('abuseMessage');
                } else {
                    $user->disabled_reason = $request->input('abuseId');
                }
                $user->disabled_at = Carbon::now();
                $user->save();

                // Notificar al usuario
                $user->notify(new AbuseSuspension($answer));

                return 'ok';
            }

            // Si hemos llegado hasta aquí, suponemos que no hay abuso.
            $answer->score = $request->input('score'); // Sobreescribimos la puntuación
            $answer->needs_supervisor = false;
            $answer->needs_supervisor_reason = null;
            $answer->supervisor_at = Carbon::now();
            $answer->supervisor_action = 'score';
            $answer->supervisor_id = Auth::user()->id;
            $answer->save();
            // Y esto sería un poco la cosa.
            return 'ok';
        }
        abort(403); // Si el usuario es un listillo, se lo decimos.
    }

    public function disableName(Request $request, $id)
    {
        if (! Auth::user()->hasPermission('mod-name-reject')) {
            abort(403);
        }
        $this->validate($request, [
            'nameid' => 'required|integer',
        ]);
        $name = Name::findOrFail($request->input('nameid'));
        if (is_null($name->active_at)) {
            abort(403);
        }
        if ($name->user->hasPermission('protection-level-1') && ! Auth::user()->hasPermission('protection-level-1-bypass')) {
            abort(403);
        }
        $name->invalid = true;
        $name->active_at = null;
        $name->needs_review = false;
        $name->end_at = Carbon::now();
        $name->save();
        Cache::forget('user.'.$name->user->id.'.getSetupStep');

        return redirect(route('mod-user', $name->user))->with('status', 'Nombre desactivado');
    }

    public function enableName(Request $request, $id)
    {
        if (! Auth::user()->hasPermission('mod-name-accept')) {
            abort(403);
        }
        $this->validate($request, [
            'nameid' => 'required|integer',
        ]);

        $name = Name::findOrFail($request->input('nameid'));

        if (! ($name->invalid || ! is_null($name->end_at) || $name->needs_review)) {
            abort(403);
        }

        foreach ($name->user->names()->whereNotNull('active_at')->whereNull('end_at')->where('invalid', false)->get() as $item) {
            $item->end_at = Carbon::now();
            $item->save();
        }

        $name->invalid = false;
        $name->active_at = Carbon::now();
        $name->end_at = null;
        $name->needs_review = false;
        $name->save();
        Cache::forget('user.'.$name->user->id.'.getSetupStep');

        return redirect(route('mod-user', $name->user))->with('status', 'Nombre activado');
    }
}
