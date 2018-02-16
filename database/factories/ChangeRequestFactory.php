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

$factory->define(\Martin\Clients\ChangeRequest::class, function (Faker $faker) {
    return [
        'project_id'    => factory(\Martin\Projects\Project::class)->create()->id,
        'user_id'       => factory(\Martin\ACL\User::class)->create()->id,
        'description'   => $faker->words(20, true),

        'fulfilled_at' => Carbon::now()->subDays($faker->numberBetween(100,120)),
        'requested_at'    => Carbon::now()->subDays($faker->numberBetween(100,120)),
    ];
});
