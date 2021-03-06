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

$factory->define(\Martin\Clients\Contact::class, function (Faker $faker) {
    $client = factory(\Martin\Clients\Client::class)->create();
    return [
        'name'          => $faker->name,
        'email'         => $faker->email,
        'client_id'     => $client->id,
    ];
});
