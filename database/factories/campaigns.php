<?php

use Faker\Generator;
use Whatsloan\Repositories\Campaigns\Campaign;
use Carbon\Carbon;

$factory->define(Campaign::class, function (Generator $faker) {
    return [
        'uuid'         => $faker->uuid,
        'organizer'    => $faker->name,
        'name'         => ucfirst($faker->word),
        'description'  => $faker->sentence,
        'promotionals' => $faker->sentence,
        'type'         => $faker->randomElement(['GROUND_EVENT', 'SMS_CAMPAIGN', 'EMAIL_CAMPAIGN']),
        'from'         => Carbon::createFromTimestamp($faker->dateTimeBetween($startDate = '+2 days', $endDate = '+1 week')->getTimeStamp()),
        'to'           => Carbon::createFromTimestamp($faker->dateTimeBetween($startDate = '+3 days', $endDate = '+2 week')->getTimeStamp()),
    ];
});