<?php

use Faker\Generator;
use Whatsloan\Repositories\States\State;

$factory->define(State::class, function (Generator $faker) {
    return [
        'uuid'        => $faker->uuid,
        'name'        => $faker->state,
        'description' => $faker->sentence,
    ];
});