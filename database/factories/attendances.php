<?php

use Faker\Generator;
use Whatsloan\Repositories\Attendances\Attendance;

$factory->define(Attendance::class, function (Generator $faker) {
    return [
        'uuid'       => $faker->uuid,
        'start_time' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now'),
    ];
});