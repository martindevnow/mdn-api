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

$factory->define(\Martin\Clients\Service::class, function (Faker $faker) {
    $start_date = Carbon::now()->subDays($faker->numberBetween(100,120));

    return [
        'project_id'        => factory(\Martin\Projects\Project::class)->create()->id,
        'description'       => $faker->words(5, true),
        'rate'              => $faker->numberBetween(55, 100),
        'billing_frequency' => $faker->randomElement([
            'yearly',
            'monthly',
        ]),

        'activated_at'      => $start_date->format('Y-m-d'),
        'deactivated_at'    => null,
        'valid_from_date'   => $start_date->format('Y-m-d'),
        'valid_until_date'  => $start_date->addYear()->format('Y-m-d'),
    ];
});
