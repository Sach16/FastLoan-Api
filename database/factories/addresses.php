<?php

use Faker\Generator;
use Whatsloan\Repositories\Addresses\Address;

$factory->define(Address::class, function (Generator $faker) {
    return [
        'uuid'         => $faker->uuid,
        'email'        => $faker->email,
        'phone'        => $faker->phoneNumber,
        'alpha_street' => $faker->streetName,
        'beta_street'  => $faker->streetAddress,
        'state'        => $faker->city,
        'country'      => $faker->country,
        'zip'          => $faker->numberBetween(111111, 999999),
        'latitude'     => $faker->latitude($min = -90, $max = 90),
        'longitude'    => $faker->longitude($min = -180, $max = 180),
    ];
});