<?php

use Illuminate\Database\Seeder;

class NewQuestionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $question = new \App\Question();
        $question->type = 'single';
        $question->category_id = 1;
        $question->question = 'Como norma general, ¿cuándo deben leerse los derechos a un detenido?';
        $question->enabled = true;
        $question->options = [
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Al esposarle',
                'correct' => false,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'En el coche patrulla',
                'correct' => false,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Antes de entrar en comisaría',
                'correct' => true,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Antes de esposarle',
                'correct' => false,
            ],
        ];
        $question->save();

        $question = new \App\Question();
        $question->type = 'single';
        $question->category_id = 1;
        $question->question = '¿Cuál de las siguientes roles puede ser corrupto?';
        $question->enabled = true;
        $question->options = [
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'EMS',
                'correct' => false,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Policía',
                'correct' => false,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Juez',
                'correct' => false,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Ninguno de los anteriores',
                'correct' => true,
            ],
        ];
        $question->save();

        $question = new \App\Question();
        $question->type = 'single';
        $question->category_id = 1;
        $question->question = '¿Con qué fin está permitido atropellar a un jugador?';
        $question->enabled = true;
        $question->options = [
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Matarle',
                'correct' => false,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Huir de él',
                'correct' => true,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Neutralizarle',
                'correct' => false,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'No está permitido atropellar',
                'correct' => false,
            ],
        ];
        $question->save();

        $question = new \App\Question();
        $question->type = 'single';
        $question->category_id = 1;
        $question->question = '¿Con qué fin está permitido atropellar a un jugador?';
        $question->enabled = true;
        $question->options = [
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Matarle',
                'correct' => false,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Huir de él',
                'correct' => true,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Neutralizarle',
                'correct' => false,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'No está permitido atropellar',
                'correct' => false,
            ],
        ];
        $question->save();

        $question = new \App\Question();
        $question->type = 'single';
        $question->category_id = 1;
        $question->question = '¿En qué situaciones puedes estar en otro programa de comunicación que no sea TeamSpeak 3 jugando?';
        $question->enabled = true;
        $question->options = [
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Estando inconsciente',
                'correct' => false,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Estando esposado',
                'correct' => false,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'No se puede',
                'correct' => true,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'En cualquier momento',
                'correct' => false,
            ],
        ];
        $question->save();

        /*
         * Desarrollo
         */

        $question = new \App\Question();
        $question->type = 'text';
        $question->category_id = 2;
        $question->question = '¿Cuándo te puedes meter en un rol ajeno? Pon ejemplos.';
        $question->enabled = true;
        $question->save();

        $question = new \App\Question();
        $question->type = 'text';
        $question->category_id = 2;
        $question->question = "Explica el concepto de 'Nueva Vida', sus implicaciones y pon ejemplos.";
        $question->enabled = true;
        $question->save();
    }
}
