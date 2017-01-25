<?php

use Faker\Generator;
use Whatsloan\Repositories\Sources\Source;

$factory->define(Source::class, function (Generator $faker) {
    
    $name = [
        'Referral',
        'Campaign',        
    ];
    
    $key = [
        'REFERRAL',
        'CAMPAIGN',        
    ];
    
    return [
        'uuid'      => $faker->uuid,
        'name'      => $faker->randomElement($name),
        'key'       => $faker->randomElement($key),
        'description' => $faker->sentence,
    ];
});