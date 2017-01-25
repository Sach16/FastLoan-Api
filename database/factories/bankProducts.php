<?php

use Faker\Generator;
use Whatsloan\Repositories\Banks\BankProduct;
use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Repositories\Types\Type;
use Whatsloan\Repositories\Products\Product;

$factory->define(BankProduct::class, function (Generator $faker) {

    return [
        'uuid' => $faker->uuid,
        'bank_id' => $faker->randomElement(Bank::lists('id')->all()),
        'product_id' => $faker->randomElement(Type::lists('id')->all()),
    ];
});
