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

$factory->define(\Martin\Projects\Work::class, function (Faker $faker) {
    return [
        'project_id'    => factory(\Martin\Projects\Project::class)->create()->id,

        'details'       => $faker->sentences(1, true),
        'duration'      => $faker->numberBetween(30,90),
        'performed_at'  => Carbon::now()->subDays($faker->numberBetween(1,8))->format('Y-m-d'),
        'billable'      => true,
        'type'          => $faker->randomElement([
            'programming',
            'sysadmin',
            'consulting',
        ]),
    ];
});
