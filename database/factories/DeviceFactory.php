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

$factory->define(\Martin\Tracking\Device::class, function (Faker $faker) {
    return [
        'name'          => $faker->name,
        'description'   => $faker->sentences(1, true),
        'purchased_at'  => Carbon::now()->subDays(10)->format('Y-m-d'),
        'cost'          => $faker->numberBetween(100, 1000),
        'notes'         => 'nothing',
    ];
});
