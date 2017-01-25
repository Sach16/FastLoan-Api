<?php

use Faker\Generator;
use Whatsloan\Repositories\Projects\Project;
use Whatsloan\Repositories\ProjectStatuses\ProjectStatus;
use Carbon\Carbon;

$factory->define(Project::class, function (Generator $faker) {
    $status = ['under construction', 'completed'];

    return [
        'uuid'            => $faker->uuid,
        'name'            => $faker->company,
        'status_id'       => rand(1, 2),
        'possession_date' => Carbon::createFromTimestamp($faker->dateTimeBetween($startDate = 'now', $endDate = '+1 years')->getTimeStamp()),
       // 'unit_details'    => rand(5, 100),
    ];
});

$factory->define(ProjectStatus::class, function (Generator $faker) {
    return [
        'uuid' => $faker->uuid,
    ];
});
