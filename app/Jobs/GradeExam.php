<?php

namespace App\Jobs;

use App\Answer;
use App\Exam;
use App\Notifications\ExamFailed;
use App\Notifications\ExamPassed;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GradeExam implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $exam;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Exam $exam)
    {
        $this->exam = $exam;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::debug('Corrigiendo examen #' . $this->exam->id);
        $structure = $this->exam->structure;
        $total = 0;
        $graded = 0;
        // Recorrer el examen entero pregunta a pregunta computando la nota
        foreach ($structure as $group) {
            foreach ($group['questions'] as $question) {
                if (!is_null($question['answer_id'])) {
                    // Si la pregunta vale más de cero puntos, miramos la respuesta
                    if ($question['value'] > 0) {
                        // Comprobamos que la respuesta exista. Si no, cero puntos.
                        $answer = Answer::find($question['answer_id']);
                        if (!is_null($answer)) {
                            // Si ya se ha corregido, lo miramos
                            if (!is_null($answer->score)) {
                                $computedScore = round(($answer->score / 100), 2);
                                $total = $total + ($computedScore * $question['value']);
                                Log::debug('#' . $answer->id . ' ' . $computedScore . ' * ' . $question['value'] . ' = ' . $computedScore * $question['value']);
                                $graded = $graded + $question['value'];
                            } else {
                                Log::debug('#' . $answer->id . ' corregir ' . $answer->question->type);
                                if ($answer->question->type != 'text') {
                                    dispatch(new GradeAnswer($answer));
                                }
                            }
                        } else {
                            Log::debug('Pregunta vale cero');
                        }
                    }
                } else {
                    Log::debug('Sin respuesta, así que cero.');
                    $graded = $graded + $question['value'];
                }
            }
        }

        // Comprobamos si es siquiera posible aprobar por el usuario con la nota que tiene
        // y el número de respuestas corregidas.
        // (puntostotales - puntoscorregidos) + nota
        if (($this->exam->getTotalQuestionValue() - $graded) + $total >= $this->exam->getTotalQuestionValue() / 2) {
            // El usuario tiene oportunidad de aprobar.
            // Esperamos a que estén todas las respuestas corregidas.
            if ($graded == $this->exam->getTotalQuestionValue() && $total >= $this->exam->getTotalQuestionValue() / 2) {
                // Todas las preguntas corregidas y la mitad o más bien.
                // Aprobado
                Log::debug('++ APROBADO ' . $total . '/' . $this->exam->getTotalQuestionValue());
                $this->exam->passed = true;
                $this->exam->score = round(($total/$this->exam->getTotalQuestionValue()) * 100);
                $this->exam->passed_at = Carbon::now();
                $this->exam->expires_at = $this->exam->expires_at->addDays(3); // le damos tres días más de los que tenía
                $this->exam->save();
                $this->exam->user->notify(new ExamPassed($this->exam));
                Cache::forget('user.'. $this->exam->user->id . '.getSetupStep');
            } else {
                Log::debug('?? Faltan correcciones p.pos.:'. $total . '/' . (($this->exam->getTotalQuestionValue() - $graded) + $total) . ' c:' . $graded . '/' . $this->exam->getTotalQuestionValue());
            }
        } else {
            // No hay nada que hacer. Al hoyo. Haber estudiao...
            // Al usuario no le darían los puntos ni con todas las respuestas bien desde ahora...
            // Suspenso
            Log::debug('-- Suspenso (no tenía ni posibilidades) p.pos:' . (($this->exam->getTotalQuestionValue() - $graded) + $total) . ' ' . $total . '/' . $this->exam->getTotalQuestionValue());
            $this->exam->passed = false;
            $this->exam->score = round(($total/$this->exam->getTotalQuestionValue()) * 100);
            $this->exam->passed_at = Carbon::now();
            $this->exam->save();
            $this->exam->user->notify(new ExamFailed($this->exam));
            if ($this->exam->user->getExamTriesRemaining() == 0) {
                $user = $this->exam->user;
                $user->disabled = 1;
                $user->disabled_reason = '@tries';
                $user->disabled_at = Carbon::now();
                $user->save();
                Cache::forget('user.'. $user->id . '.getSetupStep');
            }
        }
    }
}
