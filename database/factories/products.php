<?php

use Faker\Generator;
use Whatsloan\Repositories\Products\Product;

$factory->define(Product::class, function (Generator $faker) {
    $products = ['HL', 'PL'];

    return [
        'uuid'            => $faker->uuid,
        'product'         => $faker->randomElement($products),        
    ];
});

