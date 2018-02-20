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

$factory->define(\Martin\Billing\Payment::class, function (Faker $faker) {
    $xrate = $faker->numberBetween(115, 130) / 100;
    $cad = $faker->numberBetween(100, 250);
    $usd = $cad * $xrate;

    return [
        'client_id'     => factory(\Martin\Clients\Client::class)->create()->id,
        'cheque_number' => $faker->numberBetween(100000, 999999),
        'amount_cad'    => $cad,
        'amount_usd'    => $usd,
        'usd_to_cad_rate'   => $xrate,
        'received_at'       => Carbon::now()->subDays($faker->numberBetween(100,120))->format('Y-m-d'),
    ];
});
