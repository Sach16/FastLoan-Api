<?php

use Faker\Generator;
use Whatsloan\Repositories\Attachments\Attachment;

$factory->define(Attachment::class, function (Generator $faker) {
    return [
        'uuid'        => $faker->uuid,
        'name'        => $faker->name,
        'description' => $faker->sentence,
        'uri'         => $faker->imageUrl(),
        'type'        => $faker->randomElement(['CHECKLIST', 'BANNER', 'ID_PROOF', 'ADDRESS_PROOF','PRODUCT_DOCUMENT','EXPERIENCE_DOCUMENT','HL','PL', 'CUSTOMER_DOCUMENT', 'PROFILE_PICTURE']),
    ];
});