<?php

use Faker\Generator;
use Whatsloan\Repositories\Loans\Loan;


$factory->define(Loan::class, function (Generator $faker) {
    return [
        'uuid' => $faker->uuid,
        'amount' => $faker->randomNumber(),
        'eligible_amount' => $faker->numberBetween($min = 10000, $max = 900000),
        'approved_amount' => $faker->numberBetween($min = 1000, $max = 900000),
        'interest_rate' => $faker->numberBetween($min = 0, $max = 15),
        'applied_on' => date('Y-m-d H:i:s'),
        'approval_date' => date('Y-m-d H:i:s'),
        'emi' => $faker->numberBetween($min = 1000, $max = 5000),
        'emi_start_date' => date('Y-m-d H:i:s'),
        'appid' => $faker->word,
    ];
});



