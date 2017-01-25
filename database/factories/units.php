<?php

use Faker\Generator;
use Whatsloan\Repositories\Units\Unit;

$factory->define(Unit::class, function (Generator $faker) {
    return [
        'uuid'  => $faker->uuid,
        'number'=> rand(10,100)
    ];
});