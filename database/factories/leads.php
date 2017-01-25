<?php

use Faker\Generator;
use Whatsloan\Repositories\Leads\Lead;
use Whatsloan\Repositories\Sources\Source;
use Whatsloan\Repositories\Types\Type;
use Whatsloan\Repositories\Cities\City;

$factory->define(Lead::class, function (Generator $faker) {
    return [
        'uuid'              => $faker->uuid,
        'user_id'           => 1,
        'loan_id'           => rand(1,10),
       // 'referral_id'       => 1,
        'source_id'         => 1,
        'created_by'        => rand(1,10),
        'existing_loan_emi' => $faker->numberBetween(0, 50000),
    ];
});

$factory->define(Source::class, function (Generator $faker) {
    return [
        'uuid'        => $faker->uuid,
        'name'        => $faker->word,
        'description' => $faker->sentence,
    ];
});

$factory->define(Type::class, function (Generator $faker) {
    return [
        'uuid'        => $faker->uuid,
        'name'        => $faker->word,
        'description' => $faker->sentence,
    ];
});

$factory->define(City::class, function (Generator $faker) {
    return [
        'uuid'        => $faker->uuid,
        'name'        => $faker->word,        
    ];
});