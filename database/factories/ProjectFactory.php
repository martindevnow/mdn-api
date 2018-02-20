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

$factory->define(\Martin\Projects\Project::class, function (Faker $faker) {
    return [
        'client_id'     => factory(\Martin\Clients\Client::class)->create()->id,
        'server_id'     => factory(\Martin\Projects\Server::class)->create()->id,

        'name'          => $faker->name,
        'code'          => $faker->words(1, true),
        'description'   => $faker->sentences(1, true),
        'status'        => 'active',

        'started_at'        => Carbon::now()->subDays($faker->numberBetween(100,120))->format('Y-m-d'),

        'git_repo_url'      => $faker->url,
        'production_url'    => $faker->url,
        'development_url'   => $faker->url,
    ];
});
