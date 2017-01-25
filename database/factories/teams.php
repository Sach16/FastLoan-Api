<?php

use Faker\Generator;
use Whatsloan\Repositories\Teams\Team;

$factory->define(Team::class, function (Generator $faker) {
    return [
        'uuid'        => $faker->uuid,
        'name'        => $faker->word,
        'description' => $faker->sentence,
    ];
});