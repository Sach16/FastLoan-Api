<?php

use Faker\Generator;
use Whatsloan\Repositories\Designations\Designation;

$factory->define(Designation::class, function (Generator $faker) {
    $designations = [
        'Lead',
        'Owner',
        'Loan Associate',
        'Loan Desk',
        'Bank Coordinator',
        'Team Leader',
        'Owner',
        'Team Leader',
        'Referral Associate',
        'Lead',
		'Consumer',
    ];
    return [
        'uuid'        => $faker->uuid,
        'name'        => $faker->randomElement($designations),
        'description' => $faker->sentence,
    ];
});