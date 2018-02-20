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

$factory->define(\Martin\Billing\Charge::class, function (Faker $faker) {
    $chargeable = factory($faker->randomElement([
        \Martin\Projects\Work::class,
        \Martin\Clients\Service::class,
    ]))->create();

    $hourlyRate         = $faker->numberBetween(55, 75);
    $quantityHours  = $faker->numberBetween(1, 4);

    return [
        'project_id'        => factory(\Martin\Projects\Project::class)->create()->id,
        'invoice_id'        => factory(\Martin\Billing\Invoice::class)->create()->id,

        'chargeable_id'     => $chargeable->id,
        'chargeable_type'   => get_class($chargeable),

        'rate'              => $hourlyRate,
        'quantity'          => $quantityHours,

        'total_cost'        => $hourlyRate * $quantityHours,
        'billable_as_of'    => Carbon::now()->subDays($faker->numberBetween(100,120)),
        'billed_at'         => null,
    ];
});
