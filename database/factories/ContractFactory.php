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

$factory->define(\Martin\Clients\Contract::class, function (Faker $faker) {
    return [
        'project_id'                => factory(\Martin\Projects\Project::class)->create()->id,
        'programming_hourly_rate'   => $faker->numberBetween(40, 70),
        'sysadmin_hourly_rate'      => $faker->numberBetween(40, 70),
        'consulting_hourly_rate'    => $faker->numberBetween(40, 70),
        'activated_at'              => Carbon::now()->subDays($faker->numberBetween(10,18))->format('Y-m-d'),
        'valid_from_date'           => Carbon::now()->subDays($faker->numberBetween(18,29))->format('Y-m-d'),
    ];
});
