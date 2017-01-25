<?php

use Faker\Generator;
use Whatsloan\Repositories\Calendars\Calendar;
use Carbon\Carbon;

$factory->define(Calendar::class, function (Generator $faker) {
    return [
        'uuid'         => $faker->uuid,
        'date'         => Carbon::createFromTimestamp($faker->dateTimeBetween($startDate = 'now', $endDate = '+1 years')->getTimeStamp()),
        'description'  => $faker->sentence,
    ];
});