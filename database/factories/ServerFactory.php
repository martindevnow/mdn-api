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

$factory->define(\Martin\Projects\Server::class, function (Faker $faker) {
    return [
        'name'          => $faker->name,
        'host'          => $faker->words(1, true),
        'os'            => $faker->randomElement(['CentOS 6.9', 'CentOS 7', 'Ubuntu 14', 'Ubuntu 16']),
        'username'      => $faker->userName,
        'email'         => $faker->email,

        'purchased_at'  => Carbon::now()->subDays($faker->numberBetween(100,120)),
        'expires_at'    => Carbon::now()->addDays($faker->numberBetween(100,120)),

        'cost_monthly'  => $faker->numberBetween(10000, 15000),
        'currency'      => $faker->randomElement(['USD', 'CAD', 'JPY']),
        'billing_cycle' => $faker->randomElement(['yearly', 'monthly']),

        'active'        => true,
    ];
});
