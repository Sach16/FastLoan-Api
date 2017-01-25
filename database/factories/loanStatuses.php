<?php

use Faker\Generator;
use Whatsloan\Repositories\LoanStatuses\LoanStatus;

$factory->define(LoanStatus::class, function (Generator $faker) {

    return [
        'uuid' => $faker->uuid,
    ];
});
