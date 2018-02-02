<?php

return [
    /**
     * Permite empezar nuevos exámenes.
     * Si está en false les para en la página de generación.
     */
    'enabled' => env('EXAMS_ENABLED', true),

    /**
    * Duración de cada examen en minutos.
    */
    'duration' => '30',

    /**
     * Enlace de URI ts3server://[...] que sale en la página
     */
    'ts_link' => env('EXAM_TS_LINK', 'ts3server://ts3.plataoplomo.wtf'),
    'ts_room_name' => env('EXAM_TS_ROOM_NAME', '⌛ Sala de Espera | Entrevista PoPLife ⌛'),
    'ts_room_password' => env('EXAM_TS_ROOM_PASSWORD', 'sjkfowe24fu0efjopk'),

    /**
     * La estructura a generar de los exámenes.
     */
    'structure' => [
        [
            'name' => 'Personaje',
            'description' => 'Primero, invéntate una historia para tu personaje',
            'questions' => [
                [
                    'type' => 'question',
                    'id' => 1,
                    'value' => 4
                ]
            ]
        ],
        [
            'name' => 'Preguntas cortas',
            'description' => 'Preguntas de tipo test para ver si has entendido la normativa',
            'questions' => [
                [
                    'type' => 'category',
                    'id' => 1,
                    'value' => 1
                ],
                [
                    'type' => 'category',
                    'id' => 1,
                    'value' => 1
                ],
                [
                    'type' => 'category',
                    'id' => 1,
                    'value' => 1
                ],
                [
                    'type' => 'category',
                    'id' => 1,
                    'value' => 1
                ],
                [
                    'type' => 'category',
                    'id' => 1,
                    'value' => 1
                ],
            ]
        ],
        [
            'name' => 'Preguntas de desarrollo',
            'description' => 'Preguntas con respuestas más largas para ver si has entendido los conceptos básicos',
            'questions' => [
                [
                    'type' => 'category',
                    'id' => 2,
                    'value' => 4
                ],
                [
                    'type' => 'category',
                    'id' => 2,
                    'value' => 4
                ],
                [
                    'type' => 'category',
                    'id' => 2,
                    'value' => 4
                ],
            ]
        ],
    ]
];
