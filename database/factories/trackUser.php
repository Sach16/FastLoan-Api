<?php

use Faker\Generator;
use Whatsloan\Repositories\Users\TrackUser;

$factory->define(TrackUser::class, function (Generator $faker) {
    $track_status = ['TRUE', 'FALSE'];
    
    return [
        'uuid'           => $faker->uuid,        
        'key'           => $faker->randomElement($track_status),        
    ];
});
