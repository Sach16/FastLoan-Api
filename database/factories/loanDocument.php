<?php

use Faker\Generator;
use Whatsloan\Repositories\LoanDocuments\LoanDocument;


$factory->define(LoanDocument::class, function (Generator $faker) {
    return [
        'uuid' => $faker->uuid
    ];
});