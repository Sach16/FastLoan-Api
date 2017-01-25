<?php

use Faker\Generator;
use Whatsloan\Repositories\Cities\City;

$factory->define(City::class, function (Generator $faker) {
    return [
        'uuid'      => $faker->uuid,
        'name'      => $faker->city,
        'latitude'  => $faker->latitude($min = -90, $max = 90),
        'longitude' => $faker->longitude($min = -180, $max = 180),
    ];
});