<?php

use Illuminate\Database\Seeder;

class QuestionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // 1
        DB::table('questions')->insert([
            'type'       => 'text',
            'question'   => 'Invéntate una historia para tu personaje.',
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            'enabled'    => true,
        ]);

        $question = new \App\Question();
        $question->type = 'single';
        $question->category_id = 1;
        $question->question = 'Enunciado de la pregunta';
        $question->enabled = true;
        $question->options = [
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Opción A',
                'correct' => true,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Opción B',
                'correct' => false,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Opción C',
                'correct' => false,
            ],
        ];
        $question->save();

        $question = new \App\Question();
        $question->type = 'single';
        $question->category_id = 1;
        $question->question = '¿Verdadero o falso?';
        $question->enabled = true;
        $question->options = [
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Verdadero',
                'correct' => true,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Falso',
                'correct' => false,
            ],
        ];
        $question->save();

        $question = new \App\Question();
        $question->type = 'single';
        $question->category_id = 1;
        $question->question = '¿Qué opción es correcta?';
        $question->enabled = true;
        $question->options = [
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Todas',
                'correct' => true,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Ninguna',
                'correct' => false,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Opción C',
                'correct' => false,
            ],
        ];
        $question->save();

        $question = new \App\Question();
        $question->type = 'single';
        $question->category_id = 1;
        $question->question = 'Ser Policía corrupto está permitido. Si me pillan, sin embargo, podrían meterme a la cárcel.';
        $question->enabled = true;
        $question->options = [
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Verdadero',
                'correct' => true,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Falso',
                'correct' => false,
            ],
        ];
        $question->save();

        $question = new \App\Question();
        $question->type = 'single';
        $question->category_id = 1;
        $question->question = 'En las zonas seguras está permitido delinquir siempre que no nos pille la Policía.';
        $question->enabled = true;
        $question->options = [
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Todas',
                'correct' => true,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Ninguna',
                'correct' => false,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Opción C',
                'correct' => false,
            ],
        ];
        $question->save();

        $question = new \App\Question();
        $question->type = 'single';
        $question->category_id = 1;
        $question->question = '¿Se le puede robar el arma a un Policía?';
        $question->enabled = true;
        $question->options = [
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Sí',
                'correct' => true,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'No',
                'correct' => false,
            ],
        ];
        $question->save();

        $question = new \App\Question();
        $question->type = 'single';
        $question->category_id = 1;
        $question->question = 'El Metagaming está permitido.';
        $question->enabled = true;
        $question->options = [
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Todas',
                'correct' => true,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Ninguna',
                'correct' => false,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Opción C',
                'correct' => false,
            ],
        ];
        $question->save();

        $question = new \App\Question();
        $question->type = 'single';
        $question->category_id = 1;
        $question->question = '¿Cómo se llama mi perro?';
        $question->enabled = true;
        $question->options = [
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Gato',
                'correct' => true,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Perro',
                'correct' => false,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'No tengo perro',
                'correct' => false,
            ],
        ];
        $question->save();

        $question = new \App\Question();
        $question->type = 'single';
        $question->category_id = 1;
        $question->question = '¿Qué opción es correcta?';
        $question->enabled = true;
        $question->options = [
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Todas',
                'correct' => true,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Ninguna',
                'correct' => false,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Opción A',
                'correct' => false,
            ],
        ];
        $question->save();

        $question = new \App\Question();
        $question->type = 'single';
        $question->category_id = 1;
        $question->question = '¿Qué opción es correcta?';
        $question->enabled = true;
        $question->options = [
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Todas',
                'correct' => true,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Ninguna',
                'correct' => false,
            ],
            [
                'id'      => \Illuminate\Support\Str::random(),
                'text'    => 'Opción C',
                'correct' => false,
            ],
        ];
        $question->save();

        // 3
        DB::table('questions')->insert([
            'type'        => 'text',
            'category_id' => 2,
            'question'    => 'Explica el concepto de zona segura y pon un ejemplo de una situación que incumpla la normativa relacionada.',
            'created_at'  => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'  => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            'enabled'     => true,
        ]);

        // 4
        DB::table('questions')->insert([
            'type'        => 'text',
            'category_id' => 2,
            'question'    => 'Pon ejemplos de situaciones en las que se haga Metagaming.',
            'created_at'  => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'  => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            'enabled'     => true,
        ]);

        // 5
        DB::table('questions')->insert([
            'type'        => 'text',
            'category_id' => 2,
            'question'    => 'Profundiza el concepto de "Valorar la vida" y pon varios ejemplos de cuándo una persona no estaría valorando su vida.',
            'created_at'  => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'  => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            'enabled'     => true,
        ]);

//        // 4
//        DB::table('questions')->insert([
//            'type' => 'short',
//            'question' => 'Enunciado de la pregunta.',
//            'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
//            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
//            'enabled' => true,
//        ]);
//
//        $question = \App\Question::find(2);
//        $question->answers = [
//            [
//                'regex' => 'Opción A',
//                'correct' => 'true',
//                'value' => 0.25
//            ],
//            [
//                'id' => \Illuminate\Support\Str::random(),
//                'text' => 'Opción B',
//                'correct' => 'false',
//                'value' => 0
//            ],
//            [
//                'id' => \Illuminate\Support\Str::random(),
//                'text' => 'Opción C',
//                'correct' => 'false',
//                'value' => 0
//            ],
//        ];
//        $question->save();
    }
}
