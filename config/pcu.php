<?php

return [

    'version' => 'v1.5.7',

    /**
     * Execute scheduled tasks
     */
    'enable_schedule' => env('PCU_ENABLE_SCHEDULE', false),

    /**
     * Whether or not register new users on login.
     */
    'registrations_enabled' => env('PCU_REGISTRATIONS_ENABLED', true),

    /**
     * Si está activado, no pide que enlaces tu cuenta del foro.
     * Pensado para cuando temporalmente el foro no funcione, poder seguir.
     * Luego cuando se activa, los que no tengan la cuenta enlazada tendrán que enlazarla.
     */
    'forum_skip' => env('PCU_FORUM_SKIP', false),

    'disabled_reasons' => [
        '@pegui' => 'No cumples con la edad mínima para jugar.',
        '@tries' => 'Has alcanzado el máximo de intentos de aprobar el examen.',
        '@nametries' => 'Has alcanzado el máximo de intentos para elegir un nombre.'
    ],

    'name_reasons' => [
        '@pop4' => 'Cambio de versión de POPLife.'
    ],

    'pop_opening' => env('POP_OPENING', null),
    'pop_opened' => env('POP_OPENED', false),

    'imported_name_changes_allow' => env('POP_IMPORTED_NAME_CHANGES_ALLOW', false),

    'ts3_link' => env('TS3_LINK', ''),

    /**
     * ALTIS promo
     * /altis
     */
    'altis_enabled' => env('ALTIS_ENABLED', false),
    'altis_ip' => env('ALTIS_IP', null),
    'altis_forum' => env('ALTIS_FORUM', null),
    'altis_rules' => env('ALTIS_RULES', null),

    'analytics' => env('ANALYTICS_ID', null),


    /**
     * NOMBRES
     */

    'nombres' => [
        // Nombres
        'Raul' => 'Raúl',
        'Oscar' => 'Óscar',
        'Alvaro' => 'Álvaro',
        'Andres' => 'Andrés',
        'Angel' => 'Ángel',
        'Jesus' => 'Jesús',
        'Adrian' => 'Adrián',
        'Guzman' => 'Guzmán',
        'Ivan' => 'Iván',
        'Sebastian' => 'Sebastián',
        'Ruben' => 'Rubén',
        'Julian' => 'Julián',
        'Fermin' => 'Fermín',
        'Cesar' => 'César',
        'Matias' => 'Matías',
        'Agustin' => 'Agustín',
        'Joaquin' => 'Joaquín',
        'Martin' => 'Martín',
        'Tobias' => 'Tobías',
        // Apellidos
        'Rodriguez' => 'Rodríguez',
        'Hernandez' => 'Hernández',
        'Fernandez' => 'Fernández',
        'Martinez' => 'Martínez',
        'Gonzalez' => 'González',
        'Gonzales' => 'González',
        'Garcia' => 'García',
        'Casarin' => 'Casarín',
        'Benitez' => 'Benítez',
        'Gomez' => 'Gómez',
        'Sanchez' => 'Sánchez',
        'Lopez' => 'López',
        'Perez' => 'Pérez',
        'Marquez' => 'Márquez',
        'Gutierrez' => 'Gutiérrez',
        'Diaz' => 'Díaz',
        'Avila' => 'Ávila',
        'Suarez' => 'Suárez',
        'Ramirez' => 'Ramírez',
        'Beltran' => 'Beltrán',
        'Ibañez' => 'Ibáñez',
        'Vazquez' => 'Vázquez',
        'Millan' => 'Millán',
        'Lazaro' => 'Lázaro',
        'Cardenas' => 'Cárdenas',
        // Diminutivos

        // Troll
        'Yesus' => 'Jesús',
        'Yisus' => 'Jesús',
        'Jesulín' => 'Jesús',
    ],

    'except' => [
        'Ivánov' => 'Ivanov',
        'Ivánero' => 'Ivanero'
    ],

    'whitelist_key' => env('WHITELIST_KEY'),

    'enable_integration' => env('PCU_ENABLE_INTEGRATION', false),

    'discourse_secret' => env('DISCOURSE_SECRET', null),
];
