<?php

use Faker\Generator;
use Whatsloan\Repositories\Localities\Locality;

$factory->define(Locality::class, function (Generator $faker) {
    return [
        'uuid'        => $faker->uuid,
        'name'        => $faker->name,
        'description' => $faker->sentence,
    ];
});