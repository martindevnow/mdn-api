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

$factory->define(\Martin\Tracking\Software::class, function (Faker $faker) {
    $xrate = $faker->numberBetween(115, 130) / 100;
    $cad = $faker->numberBetween(100, 250);
    $usd = $cad * $xrate;

    return [
        'name'                  => $faker->name,
        'description'           => $faker->sentences(1, true),
        'purchased_at'          => Carbon::now()->subDays(10)->format('Y-m-d'),
        'cancelled_at'          => null,
        'purchased_from'        => 'LaraCasts',
        'license_information'   => 'nothing',

        'amount_cad'            => $cad,
        'amount_usd'            => $usd,
        'usd_to_cad_rate'       => $xrate,
        'billing_cycle'         => $faker->randomElement(['yearly', 'monthly']),
    ];
});
