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

$factory->define(\Martin\Projects\Domain::class, function (Faker $faker) {
    return [
        'project_id'    => factory(\Martin\Projects\Project::class)->create()->id,
        'name'          => $faker->name . ".com",
        'registrar'     => $faker->words(1, true),

        'originally_registered_at' => Carbon::now()->subDays($faker->numberBetween(100,120)),
        'expires_at'    => Carbon::now()->subDays($faker->numberBetween(100,120)),
    ];
});
