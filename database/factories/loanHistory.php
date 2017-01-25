<?php

use Faker\Generator;
use Whatsloan\Repositories\LoanHistories\LoanHistory;


$factory->define(LoanHistory::class, function (Generator $faker) {
    return [
        'uuid' => $faker->uuid
    ];
});