<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'email'          => $faker->unique()->safeEmail,
        'steamid'        => '7656119'.rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9),
        'remember_token' => str_random(10),
    ];
});

/*
 * Factory de App\Name
 */
$factory->define(\App\Name::class, function (\Faker\Generator $faker) {
    return [
       'name'         => $faker->name,
       'needs_review' => false,
    ];
});

$factory->state(\App\Name::class, 'active', function ($faker) {
    return [
        'needs_review' => false,
        'invalid'      => false,
        'active_at'    => \Carbon\Carbon::now(),
        'end_at'       => null,
    ];
});
