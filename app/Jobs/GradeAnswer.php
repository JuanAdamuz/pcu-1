<?php

namespace App\Jobs;

use App\Answer;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class GradeAnswer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $answer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Answer $answer)
    {
        $this->answer = $answer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $question = $this->answer->question;
        // Si no encontramos la pregunta... cero.
        if (is_null($question)) {
            Log::debug($this->answer->id . ' no existe, 0');
            $this->answer->score = 0;
            $this->answer->save();
            return;
        }

        if ($question->type == 'single') {
            foreach ($question->options as $option) {
                // Si es la respuesta correcta..
                if ($option['id'] == $this->answer->answer) {
                    if ($option['correct']) {
                        Log::debug($this->answer->id . ' correcta, 100');
                        $this->answer->score = 100;
                        $this->answer->save();
                        return;
                    }
                }
            }
            // Respuesta incorrecta
            // Algoritmo: si falla, le restamos 100/el número de opciones menos 1
            $subtract = round(100 / (sizeof($question->options) - 1)); // Algoritmo cono en los exámenes de la uni
            $this->answer->score = -$subtract; // restamos la mitad
            $this->answer->save();
            Log::debug($this->answer->id . ' incorrecta, ' . -$subtract);
            return;
        }
    }
}
