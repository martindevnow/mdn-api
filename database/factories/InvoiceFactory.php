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

$factory->define(\Martin\Billing\Invoice::class, function (Faker $faker) {
    $start_date = Carbon::now()->subDays($faker->numberBetween(100,120));

    $xrate = $faker->numberBetween(115, 130) / 100;
    $cad = $faker->numberBetween(100, 250);
    $usd = $cad * $xrate;

    return [
        'project_id'        => factory(\Martin\Projects\Project::class)->create()->id,
        'invoice_no'        => $faker->numberBetween(1000000, 2514654),

        'amount_cad'        => $cad,
        'amount_usd'        => $usd,
        'usd_to_cad_rate'   => $xrate,

        'generated_at'      => $start_date,
        'sent_at'           => $start_date->addDays(2),
        'paid_at'           => $start_date->addDays(17),
    ];
});
