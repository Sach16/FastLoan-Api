<?php

use Faker\Generator;
use Whatsloan\Repositories\Banks\Bank;

$factory->define(Bank::class, function (Generator $faker) {
    return [
        'uuid'      => $faker->uuid,
        'name'      => $faker->company,
        'branch'    => $faker->city . $faker->citySuffix,
        'ifsc_code' => strtoupper($faker->word),
    ];
});