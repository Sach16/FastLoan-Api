<?php

use Faker\Generator;
use Whatsloan\Repositories\Payouts\Payout;

$factory->define(Payout::class, function (Generator $faker) {
    return [
        'uuid'            => $faker->uuid,
        'percentage'      => rand(0,30),
    ];
});

