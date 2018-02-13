<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(\Martin\Clients\Client::class, function (Faker $faker) {
    return [
        'name'          => $faker->name,
        'code'          => $faker->words(1, true),
        'description'   => $faker->sentences(1, true),
        'status'        => 'active',
    ];
});
