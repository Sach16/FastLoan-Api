<?php

use Faker\Generator;
use Whatsloan\Repositories\Queries\Query;
use Carbon\Carbon;

$factory->define(Query::class, function (Generator $faker) {

    $status = ['SUBMITTED','PENDING', 'REJECTED','APPROVED'];
     
    return [
        'uuid'         => $faker->uuid,
        'query'        => $faker->sentence,
        'start_date'   => Carbon::createFromTimestamp($faker->dateTimeBetween($startDate = 'now', $endDate = '+1 years')->getTimeStamp()),
        'end_date'     => Carbon::createFromTimestamp($faker->dateTimeBetween($startDate = 'now', $endDate = '+1 years')->getTimeStamp()),
        'due_date'     => Carbon::createFromTimestamp($faker->dateTimeBetween($startDate = 'now', $endDate = '+1 years')->getTimeStamp()),
        'status'       => $faker->randomElement($status),
        'pending_with' => $faker->word,
        
    ];
});


