<?php

use Faker\Generator;
use Whatsloan\Repositories\Builders\Builder;

$factory->define(Builder::class, function (Generator $faker) {
    return [
        'uuid'         => $faker->uuid,
        'name'         => $faker->company,
    ];
});