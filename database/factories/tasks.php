<?php

use Faker\Generator;
use Whatsloan\Repositories\Tasks\Task;
use Whatsloan\Repositories\TaskStatuses\TaskStatus;
use Whatsloan\Repositories\TaskStages\TaskStage;
use Whatsloan\Repositories\TaskHistories\TaskHistory;
use Carbon\Carbon;

$factory->define(Task::class, function (Generator $faker) {

    $status = [
        'To be Started',
        'In Progress',
        'On Hold',
        'Completed',
        'Cancelled',
        'OverDue',
    ];
    
    $stage = [
        'New',
        'Follow Up',
        'Office Login',
        'Bank Login',
        'Sanction',
        'First Disb',
        'Part Disb',
        'Final Disb',
        'Cust Decline',
        'Bank Decline',
        'Re-Login',
        'Logout',
    ];
    
    $priority = [
        'High',
        'Medium',
        'Low',
    ];

    return [
        'uuid'   => $faker->uuid,
        'task_status_id' => $faker->randomElement($status),
        'priority' => $faker->randomElement($priority),
        'description' => $faker->sentence,
        'from' => Carbon::createFromTimestamp($faker->dateTimeBetween($startDate = '+2 days', $endDate = '+1 week')->getTimeStamp()),
        'to' => Carbon::createFromTimestamp($faker->dateTimeBetween($startDate = '+3 days', $endDate = '+2 week')->getTimeStamp()),
    ];
});

$factory->define(TaskStatus::class, function (Generator $faker) {
    return [
        'uuid' => $faker->uuid,
    ];
});

$factory->define(TaskStage::class, function (Generator $faker) {
    return [
        'uuid' => $faker->uuid,        
    ];
});

$factory->define(TaskHistory::class, function (Generator $faker) {
    return [
        'uuid' => $faker->uuid,        
    ];
});